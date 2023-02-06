<?php

// =============================================================== //
// Static Pages for public pages                                   //
// =============================================================== //

    $app->get('/', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('home');
    })->setName('public_page');

    $app->get('/pricing', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('pricing');
    })->setName('public_page');

    $app->get('/team', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('team');
    })->setName('public_page');

    $app->get('/faq', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('faq');
    })->setName('public_page');

    $app->get('/blog', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('blog');
    })->setName('public_page');

    $app->get('/blog-details', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('blog_details');
    })->setName('public_page');

    $app->get('/contact', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('contact');
    })->setName('public_page');

    $app->get('/terms-of-service', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('terms_of_service');
    })->setName('public_page');

// =============================================================== //
// Dashboard pages - Client area                                   //
// =============================================================== //

    $app->group('/dashboard', function (\Slim\Routing\RouteCollectorProxy $dashboard) {

        $dashboard->get('', function ($request, $response, $args) {

            $c = new \controllers\static_pages($this,$request, $response, $args);
            return $c->render_page('dashboard','client');

        });

        $dashboard->get('/my-profile', function ($request, $response, $args) {

            $c = new \controllers\my_profile($this,$request, $response, $args);
            return $c->display('dashboard');

        });

        $dashboard->post('/my-profile', function ($request, $response, $args) {

            $c = new \controllers\my_profile($this,$request, $response, $args);
            return $c->update('dashboard');

        });

        $dashboard->post('/my-profile-password', function ($request, $response, $args) {

            $c = new \controllers\my_profile($this,$request, $response, $args);
            return $c->update_password('dashboard');

        });

    });

// =============================================================== //
// Backoffice pages - Administration area                          //
// =============================================================== //

    $app->group('/backoffice', function (\Slim\Routing\RouteCollectorProxy $backoffice) {

        $backoffice->get('', function ($request, $response, $args) {

            $c = new \controllers\static_pages($this,$request, $response, $args);
            return $c->render_page('backoffice','agent');

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

        $backoffice->group('/user', function (\Slim\Routing\RouteCollectorProxy $user) {

            $user->get('/{id}/update', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\users($this,$request, $response, $args);
                return $c->display($id);

            });

            $user->post('/{id}/update', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\users($this,$request, $response, $args);
                return $c->update($id);

            });

            $user->get('/{id}/delete', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\users($this,$request, $response, $args);
                return $c->delete($id);

            });

        });

        $backoffice->get('/accounts', function ($request, $response, $args) {

            $c = new \controllers\accounts($this,$request, $response, $args);
            return $c->list();

        });

        $backoffice->group('/account', function (\Slim\Routing\RouteCollectorProxy $account) {

            $account->get('/{id}/update', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\accounts($this,$request, $response, $args);
                return $c->display($id);

            });

            $account->post('/{id}/update', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\accounts($this,$request, $response, $args);
                return $c->update($id);

            });

            $account->get('/{id}/delete', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\accounts($this,$request, $response, $args);
                return $c->delete($id);

            });

        });

        $backoffice->get('/configurations', function ($request, $response, $args) {

            $c = new \controllers\configurations($this,$request, $response, $args);
            return $c->list();

        });

        $backoffice->group('/configuration', function (\Slim\Routing\RouteCollectorProxy $configuration) {

            $configuration->get('/new', function ($request, $response, $args) {

                $c = new \controllers\configurations($this,$request, $response, $args);
                return $c->display();

            });

            $configuration->post('/new', function ($request, $response, $args) {

                $c = new \controllers\configurations($this,$request, $response, $args);
                return $c->insert();

            });

            $configuration->get('/{id}/update', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\configurations($this,$request, $response, $args);
                return $c->display($id);

            });

            $configuration->post('/{id}/update', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\configurations($this,$request, $response, $args);
                return $c->update($id);

            });

            $configuration->get('/{id}/delete', function ($request, $response, $args) {

                $id = $args['id'];

                $c = new \controllers\configurations($this,$request, $response, $args);
                return $c->delete($id);

            });

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

        $backoffice->post('/my-profile-2fa', function ($request, $response, $args) {

            $c = new \controllers\my_profile($this,$request, $response, $args);
            return $c->update_2fa('backoffice');

        });

    });

// =============================================================== //
// Tools - Compress                                                //
// =============================================================== //

    $app->get('/compress-pdf', function ($request, $response, $args) {

        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('compress_pdf');

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
        return $c->render_public_page('log_in');
    })->setName('public_page');

    $app->post('/mfa', function ($request, $response, $args) {
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->display_mfa();
    })->setName('public_page');

    $app->post('/mfa-code', function ($request, $response, $args) {
        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify_mfa();
    })->setName('public_page');
    
    $app->get('/log-in/github', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_github();

    })->setName('public_page');

    $app->get('/log-in/google', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_google();

    })->setName('public_page');

    $app->get('/log-in/facebook', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_facebook();

    })->setName('public_page');

    $app->post('/log-in/email', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->login_email();

    })->setName('public_page');

    $app->get('/sign-up', function ($request, $response, $args) {

        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('sign_up');

    })->setName('public_page');

    $app->post('/sign-up', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->sign_up();

    })->setName('public_page');

    $app->post('/sign-up/email-validation', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->email_validation();

    })->setName('public_page');

    $app->get('/reset-password', function ($request, $response, $args) {

        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_public_page('reset_password');

    })->setName('public_page');

    $app->post('/reset-password', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->reset_password();

    })->setName('public_page');

    $app->post('/set-password', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->set_password();

    })->setName('public_page');

    $app->get('/set-password/{token}', function ($request, $response, $args) {

        $token = $args['token'];

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify_password_token($token);

    })->setName('public_page');

    $app->get('/verify', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify();

    })->setName('public_page');

    $app->get('/verify/{token}', function ($request, $response, $args) {

        $token = $args['token'];

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->verify($token);

    })->setName('public_page');

    $app->get('/log-out', function ($request, $response, $args) {

        $c = new \controllers\login($this,$request, $response, $args);
        return $c->log_out();

    });

// =============================================================== //
// API                                                             //
// =============================================================== //


    $app->post('/api/v1/token', function ($request, $response, $args) {

        $c = new \controllers\api_token($this,$request, $response, $args);
        return $c->validate_access_key();

    });

    $app->post('/api/v1/public-key/validate', function ($request, $response, $args) {

        $c = new \controllers\public_key($this,$request, $response, $args);
        return $c->validate();

    });

// =============================================================== //
// Special Files                                                   //
// =============================================================== //

    $app->get('/site.webmanifest', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_manifest();
    })->setName('special_file');

    $app->get('/global.js', function ($request, $response, $args) {
        $c = new \controllers\static_pages($this,$request, $response, $args);
        return $c->render_global();
    })->setName('special_file');