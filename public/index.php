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

    return \Slim\Views\Twig::create('../views', compact('cache'));

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
         'tracking'          => CONF_tracking,
         'gtm'               => CONF_google_tag_manager,
         'current_year'      => date('Y'),
         'current_month'     => date('m'),
         'brand_name'        => 'Slim Saas Boilerplate',
     //  'is_mobile'         => $is_mobile,
         'version'           => 0.002
     ];

     return $template_options;

 });

// Create App
$app = \Slim\Factory\AppFactory::create();

$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();

// Add Twig-View Middleware
$app->add(\Slim\Views\TwigMiddleware::createFromContainer($app,'twig'));

//Error Middleware - Present clean and clear error messages within slim 4.
/*
$errorMiddleware = new \Slim\Middleware\ErrorMiddleware(
    $app->getCallableResolver(),
    $app->getResponseFactory(),
    true,
    false,
    false
    );

$errorMiddleware->setErrorHandler(\Slim\Exception\HttpNotFoundException::class, function($request, $exception) use ($container){
    
    $response = new \Slim\Psr7\Response();
    return $container->get('twig')->render($response->withStatus(404), '404.html');
});

$app->add($errorMiddleware);
*/

// https://stackoverflow.com/questions/57648078/replacement-for-notfoundhandler-setting

$customErrorHandler = function (
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
                    'message'      => $exception->getMessage()
                ]);
            $response = new \Slim\Psr7\Response();
            return $container->get('twig')->render($response->withStatus(404), '500.html',$variable_content );
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
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

// Define all the routes
require_once('../routes.php');

// Run app
$app->run();