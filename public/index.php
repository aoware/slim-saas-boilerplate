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
     
    global $db;
    return $db;
    
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
         'version'           => 0.001
     ];
     
     return $template_options;
     
 });
 
// Create App
$app = \Slim\Factory\AppFactory::create();

// Add Twig-View Middleware
$app->add(\Slim\Views\TwigMiddleware::createFromContainer($app,'twig'));
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

require_once('../routes.php');

// Run app
$app->run();