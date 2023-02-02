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

$app->addBodyParsingMiddleware();

$session_middleware = function ($request, $handler): \Psr\Http\Message\ResponseInterface {

    $route_context = \Slim\Routing\RouteContext::fromRequest($request);
    $route = $route_context->getRoute();

    $route_name    = $route->getName();
    $route_pattern = $route->getPattern();
    $public_routes = array('public_page','api','special_file'); // List of routes that do not require a SESSION Token, therefore not redirected
    $route_parser  = $route_context->getRouteParser();

    // Getting details for the web_session_log table
    $h = new \helpers\headers($request);

    $method       = $request->getMethod();
    $content_type = $h->get_content_type();
    $payload      = serialize($request->getParsedBody());
    if ($payload == "N;") {
        $payload = null;
    }

    $endpoint = $request->getUri();
    $endpoint = str_replace(CONF_base_url . ':80','',$endpoint); // required as apache behind cloudflare seems to add the port
    $endpoint = str_replace(CONF_base_url,'',$endpoint);

    if (empty($route)) {

        $wsl = new \models\web_session_log();
        $wsl->session_token     = $_SESSION['login_token'];
        $wsl->user_id           = null;
        $wsl->ip                = $h->get_ip();
        $wsl->endpoint          = $endpoint;
        $wsl->method            = $method;
        $wsl->content_type      = $content_type;
        $wsl->payload           = $payload;
        $wsl->http_result_code  = null;
        $wsl->created_timestamp = date('Y-m-d H:i:s');
        $wsl->amended_timestamp = date('Y-m-d H:i:s');
        $result = $wsl->saveRecord();

        if ($result !== true) {
            throw new \Exception($result);
        }

        throw new \Slim\Exception\HttpNotFoundException($request->withAttribute("web_session_log_id", $wsl->inserted_id), $response);
    }

    if (empty($_SESSION['login_token'])) {

        $wsl = new \models\web_session_log();
        $wsl->session_token     = null;
        $wsl->user_id           = null;
        $wsl->ip                = $h->get_ip();
        $wsl->endpoint          = $endpoint;
        $wsl->method            = $method;
        $wsl->content_type      = $content_type;
        $wsl->payload           = $payload;
        $wsl->http_result_code  = null;
        $wsl->created_timestamp = date('Y-m-d H:i:s');
        $wsl->amended_timestamp = date('Y-m-d H:i:s');
        $result = $wsl->saveRecord();

        if ($result !== true) {
            throw new \Exception($result);
        }

        if (in_array($route_name, $public_routes)) {
            return $handler->handle(
                $request->withAttribute("is_logged"           , false)
                        ->withAttribute("current_user_id"     , null)
                        ->withAttribute("current_user_name"   , null)
                        ->withAttribute("current_user_picture", null)
                        ->withAttribute("current_user_type"   , null)
                        ->withAttribute("current_account_id"  , null)
                        ->withAttribute("current_account_name", null)
                        ->withAttribute("web_session_log_id"  , $wsl->inserted_id)
                );
        }
        else {
            $response = new \Slim\Psr7\Response();
            return $response->withHeader('Location', '/log-in')->withStatus(302);
        }
    }

    // Checking if it is a valid existing session
    $user = new \models\users();
    $user->getRecordByLogin_token($_SESSION['login_token']);

    if (count($user->recordSet) == 0) {

        if ($route_name != 'special_file') {
            $wsl = new \models\web_session_log();
            $wsl->session_token     = $_SESSION['login_token'];
            $wsl->user_id           = null;
            $wsl->ip                = $h->get_ip();
            $wsl->endpoint          = $endpoint;
            $wsl->method            = $method;
            $wsl->content_type      = $content_type;
            $wsl->payload           = $payload;
            $wsl->http_result_code  = null;
            $wsl->created_timestamp = date('Y-m-d H:i:s');
            $wsl->amended_timestamp = date('Y-m-d H:i:s');
            $result = $wsl->saveRecord();

            if ($result !== true) {
                throw new \Exception($result);
            }
        }

        $response = $handler->handle(
            $request->withAttribute("is_logged"           , false)
                    ->withAttribute("current_user_id"     , null)
                    ->withAttribute("current_user_name"   , null)
                    ->withAttribute("current_user_picture", null)
                    ->withAttribute("current_user_type"   , null)
                    ->withAttribute("current_account_id"  , null)
                    ->withAttribute("current_account_name", null)
                    ->withAttribute("web_session_log_id"  , $wsl->inserted_id)
            );
    }
    else {

        if ($route_name != 'special_file') {
            $wsl = new \models\web_session_log();
            $wsl->session_token     = $_SESSION['login_token'];
            $wsl->user_id           = $user->id;
            $wsl->ip                = $h->get_ip();
            $wsl->endpoint          = $endpoint;
            $wsl->method            = $method;
            $wsl->content_type      = $content_type;
            $wsl->payload           = $payload;
            $wsl->http_result_code  = null;
            $wsl->created_timestamp = date('Y-m-d H:i:s');
            $wsl->amended_timestamp = date('Y-m-d H:i:s');
            $result = $wsl->saveRecord();

            if ($result !== true) {
                throw new \Exception($result);
            }
        }

        if ($user->picture == '') {
            $user->picture = "https://eu.ui-avatars.com/api/?background=0D8ABC&color=fff&size=250&name=" . urlencode(trim($user->first_name . ' ' . $user->last_name));
        }

        $account_id   = null;
        $account_name = null;

        if ($user->type == 'client') {

            $dbal = new \Nextras\Dbal\Connection([
                'driver'       => 'mysqli',
                'host'         => CONF_mysql_host,
                'username'     => CONF_mysql_user,
                'password'     => CONF_mysql_password,
                'database'     => CONF_mysql_database,
                'connectionTz' => \Nextras\Dbal\Drivers\IDriver::TIMEZONE_AUTO_PHP_OFFSET
            ]);

            $query = "SELECT *
                              FROM account_users as a , accounts as b
                              WHERE a.account_id = b.id
                              and a.user_id = %i";
            $query_result = $dbal->query($query, $user->id);
            $account = $query_result->fetch();

            $account_id   = $account->id;
            $account_name = $account->name;

        }

        $response = $handler->handle(
            $request->withAttribute("is_logged"           , true)
                    ->withAttribute("current_user_id"     , $user->id)
                    ->withAttribute("current_user_name"   , trim($user->first_name . ' ' . $user->last_name))
                    ->withAttribute("current_user_picture", $user->picture)
                    ->withAttribute("current_user_type"   , $user->type)
                    ->withAttribute("current_account_id"  , $account_id)
                    ->withAttribute("current_account_name", $account_name)
                    ->withAttribute("web_session_log_id"  , null)
            );

    }

    return $response;

};

$app->add($session_middleware);

$app->addRoutingMiddleware();

// Add Twig-View Middleware
// $app->add(\Slim\Views\TwigMiddleware::createFromContainer($app,'twig'));

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

        // Identify if application/json is an accept type
        $h = new \helpers\headers($request);
        $accept = $h->get_accept();
        $pos = strrpos($accept,'application/json');
        if ($pos !== false) {
            $result =  [
                "success" => false,
                "message" => $exception->getMessage()
            ];
            $payload = json_encode($result);
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write($payload);    
            if ($exception instanceof \Slim\Exception\HttpException){
                $exception_code = $exception->getCode();
            }
            else {
                $exception_code = 500;
            }
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus($exception_code);
        }
        
        // Return an html version
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
                    'screen_title'              => 'Unable to handle this request',
                    'exception_message'         => $exception->getMessage(),
                    'exception_code'            => $exception->getCode(),
                    'exception_file'            => $exception->getFile(),
                    'exception_line'            => $exception->getLine(),
                    'exception_previous'        => $exception->getPrevious(),
                    'exception_trace_as_string' => $exception->getTraceAsString()
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
//if (CONF_configuration_profile == 'Live') {
    $errorMiddleware->setDefaultErrorHandler($custom_error_handler);
//}

// Define all the routes
require_once('../routes.php');

// Run app
$app->run();