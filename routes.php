<?php

// =============================================================== //
// Static Pages                                                    //
// =============================================================== //

    $app->get('/', function ($request, $response, $args) {

        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render('home');
        
    });
    
    $app->get('/terms-of-service', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render('terms_of_service');
        
    });

// =============================================================== //
// Tools - Compress                                                //
// =============================================================== //
    
    $app->get('/compress-pdf', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render('compress_pdf');
        
    });

    $app->post('/compress-pdf', function ($request, $response, $args) {
        
        $c = new \controllers\compress($this,$request, $response, $args);
        return $c->post();
        
    });
        
// =============================================================== //
// Registration / Login / Logout                                   //
// =============================================================== //

    $app->get('/log-in', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render('log_in');
        
    });

    $app->get('/log-in/github', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_github();

    });

    $app->get('/log-in/google', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_google();
        
    });
            
    $app->get('/log-in/facebook', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_facebook();
        
    });

    $app->post('/log-in/email', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_email();

    });
    
    $app->get('/sign-up', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render('sign_up');
        
    });

    $app->post('/sign-up', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->sign_up();
        
    });

    $app->post('/sign-up/email-validation', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->email_validation();
        
    });
    
    $app->get('/reset-password', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render('reset_password');
        
    });
                                                           
    $app->post('/reset-password', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->reset_password();

    });
    
    $app->post('/set-password', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->set_password();
        
    });

    $app->get('/set-password/{token}', function ($request, $response, $args) {
        // Which one of the 2 is working
        $token = $request->getAttribute('token');
        $token = $args['token'];
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify_password_token($token);

    });
                                                            
    $app->get('/verify/{token}', function ($request, $response, $args) {
        // Which one of the 2 is working
        $token = $request->getAttribute('token');
        $token = $args['token'];
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify($token);
        
    });
                    
    $app->get('/log-out', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->log_out();

    });
    
// =============================================================== //
// Special Files                                                   //
// =============================================================== //

    $app->get('/site.webmanifest', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_manifest();
        
    });