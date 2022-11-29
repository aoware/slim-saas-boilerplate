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

    $app->get('/dashboard', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render('dashboard','client');
        
    });



// =============================================================== //
// Backoffice pages                                                //
// =============================================================== //

    $app->group('/backoffice', function (\Slim\Routing\RouteCollectorProxy $backoffice) {

        $backoffice->get('', function ($request, $response, $args) {
            
            $c = new \controllers\static_pages($this,$request, $response, $args);
            return $c->render('backoffice','agent');
            
        });
        
        $backoffice->get('/administrators', function ($request, $response, $args) {
            
            $c = new \controllers\administrators($this,$request, $response, $args);
            return $c->list();
            
        });

        $backoffice->group('/administrator', function (\Slim\Routing\RouteCollectorProxy $administrator) {
        
            $administrator->get('/new', function ($request, $response, $args) {
                
                $c = new \controllers\administrators($this,$request, $response, $args);
                return $c->display();
                
            });

            $administrator->post('/new', function ($request, $response, $args) {
                
                $c = new \controllers\administrators($this,$request, $response, $args);
                return $c->insert();
                
            });
            
            $administrator->get('/{id}/update', function ($request, $response, $args) {
                
                $id = $args['id'];
                
                $c = new \controllers\administrators($this,$request, $response, $args);
                return $c->display($id);
                
            });
        
            $administrator->post('/{id}/update', function ($request, $response, $args) {
                
                $id = $args['id'];
                
                $c = new \controllers\administrators($this,$request, $response, $args);
                return $c->update($id);
                
            });

            $administrator->get('/{id}/delete', function ($request, $response, $args) {
                
                $id = $args['id'];
                
                $c = new \controllers\administrators($this,$request, $response, $args);
                return $c->delete($id);
                
            });
            
        });

        $backoffice->get('/users', function ($request, $response, $args) {
            
            $c = new \controllers\users($this,$request, $response, $args);
            return $c->list();
            
        });
        
        $backoffice->get('/configuration', function ($request, $response, $args) {
            
            $c = new \controllers\configuration($this,$request, $response, $args);
            return $c->list();
            
        });
        
        $backoffice->get('/my-profile', function ($request, $response, $args) {
            
            $c = new \controllers\my_profile($this,$request, $response, $args);
            return $c->display('backoffice');
            
        });
        
        $backoffice->post('/my-profile', function ($request, $response, $args) {
            
            $c = new \controllers\my_profile($this,$request, $response, $args);
            return $c->update('backoffice');
            
        });

        $backoffice->post('/my-profile-password', function ($request, $response, $args) {
            
            $c = new \controllers\my_profile($this,$request, $response, $args);
            return $c->update_password('backoffice');
            
        });
        
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

        $token = $args['token'];
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify_password_token($token);

    });

    $app->get('/verify', function ($request, $response, $args) {
        
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify();
        
    });
    
    $app->get('/verify/{token}', function ($request, $response, $args) {

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
    
    $app->get('/global.js', function ($request, $response, $args) {
        
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_global();
        
    });