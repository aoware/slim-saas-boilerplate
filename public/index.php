<?php

require_once('../vendor/autoload.php');
require_once('../config.php');

// Start session
if(!session_id()){
    session_start();
}

// Create Container
$container = new \DI\Container();
\Slim\Factory\AppFactory::setContainer($container);

// Set view in Container
$container->set('twig', function() {

    return \Slim\Views\Twig::create('../views', [
        'cache' => false
    ]);

});

$container->set('email_template', function() {

    $loader = new \Twig\Loader\FilesystemLoader('../views/email_templates');

    return new \Twig\Environment($loader, [
        'cache' => false
    ]);

});

 $container->set('db', function() {

     $db = new mysqli(
         CONF_mysql_host,
         CONF_mysql_user,
         CONF_mysql_password,
         CONF_mysql_database
         );
     if ($db->connect_errno > 0){
         die('Unable to connect to database [' . $db->connect_error . ']');
     }

    return $db;

});

$container->set('dbal', function() {

    $dbal = new \Nextras\Dbal\Connection([
        'driver'       => 'mysqli',
        'host'         => CONF_mysql_host,
        'username'     => CONF_mysql_user,
        'password'     => CONF_mysql_password,
        'database'     => CONF_mysql_database,
        'connectionTz' => \Nextras\Dbal\Drivers\IDriver::TIMEZONE_AUTO_PHP_OFFSET
    ]);

    return $dbal;

});

 $container->set('template_options', function() {

     $template_options = [
         'base_url'          => CONF_base_url,
         'current_year'      => date('Y'),
         'current_month'     => date('m'),
         'brand_name'        => 'Slim Saas Boilerplate',
         'version'           => trim(file_get_contents("../.git/ORIG_HEAD"))
     ];

     return $template_options;

 });

// Create App
$app = \Slim\Factory\AppFactory::create();

$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();

// Add Twig-View Middleware
$app->add(\Slim\Views\TwigMiddleware::createFromContainer($app,'twig'));

// Add
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path"   => ["/api/v1"],
    "ignore" => ["/api/v1/token"],
    "secret" => CONF_jwt_secret,
    "secure" => CONF_jwt_secure,
    "before" => function ($request, $arguments) {

    $authorization_headers = $request->getHeader('Authorization');

    $token = null;
    foreach($authorization_headers as $header) {
        if (substr($header,0,7) == 'Bearer ') {
            $token = substr($header,7);
            continue;
        }
    }

    $token_id = null;

    $at = new \models\api_access_tokens();
    $at->getRecordByToken($token);

    if (count($at->recordSet) == 1) {
        $token_id =  $at->recordSet[0]->id;
    }

    return $request->withAttribute("api_access_token" , $token)
    ->withAttribute("access_token_id", $token_id);
    },
    "error" => function ($response, $arguments) {
    $result = [
        "success" => false,
        "message" => "401 Unauthorized"
    ];

    $response->getBody()->write(
        json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );

    return $response->withHeader("Content-Type", "application/json");
    }
]));

// Custom Error Handler which will be enable in Live mode to hide error details
$custom_error_handler = function (
    Psr\Http\Message\ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
    ) use ($container) {

        if ($exception instanceof \Slim\Exception\HttpNotFoundException) {
            $variable_content = array_merge($container->get('template_options'),
              [
                  'screen_title' => 'Page not Found'
              ]);
            $response = new \Slim\Psr7\Response();
            return $container->get('twig')->render($response->withStatus(404), '404.html',$variable_content );
        }
        else {
            $variable_content = array_merge($container->get('template_options'),
                [
                    'screen_title' => 'Unable to handle this request',
                ]);
            $response = new \Slim\Psr7\Response();
            return $container->get('twig')->render($response->withStatus(500), '500.html',$variable_content );
        }
        if ($exception instanceof \Slim\Exception\HttpMethodNotAllowedException) {
            $message = 'not allowed';
            $code = 403;
        }
        // ...other status codes, messages, or generally other responses for other types of exceptions

        $response->getBody()->write($message);
        return $response->withStatus($code);
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
if (CONF_configuration_profile == 'Live') {
    $errorMiddleware->setDefaultErrorHandler($custom_error_handler);
}

// Define all the routes
require_once('../routes.php');

// Run app
$app->run();