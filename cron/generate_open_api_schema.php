<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config.php');

// Create a tag for all the user endpoints.
$usersTag = \GoldSpecDigital\ObjectOrientedOAS\Objects\Tag::create()
    ->name('Users')
    ->description('All user related endpoints');

// Create the info section.
$info = \GoldSpecDigital\ObjectOrientedOAS\Objects\Info::create()
    ->title('API Specification')
    ->version('v1')
    ->description('For using the Example App API');

// Create the server section.
$servers = [];
$servers[] = \GoldSpecDigital\ObjectOrientedOAS\Objects\Server::create()
    ->url('/api')
    ->description('Test 1');
$servers[] = \GoldSpecDigital\ObjectOrientedOAS\Objects\Server::create()
    ->url('/api-x')
    ->description('Test 2');
    
// Create the user schema.
$userSchema = \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema::object()
    ->properties(
        \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema::string('id')->format(\GoldSpecDigital\ObjectOrientedOAS\Objects\Schema::FORMAT_UUID),
        \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema::string('name'),
        \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema::integer('age')->example(23),
        \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema::string('created_at')->format(\GoldSpecDigital\ObjectOrientedOAS\Objects\Schema::FORMAT_DATE_TIME)
    );

$schemas = [];
$schemas['user'] = $userSchema;

// Create the user response.
$unauthenticatedResponse = \GoldSpecDigital\ObjectOrientedOAS\Objects\Response::create()
    ->statusCode(401)
    ->description('Unauthenticated')
    ->content(
    \GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType::json()->schema($userSchema)
    );

// Create the user response.
$userResponse = \GoldSpecDigital\ObjectOrientedOAS\Objects\Response::create()
    ->statusCode(200)
    ->description('OK')
    ->content(
        \GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType::json()->schema($userSchema)
    );

$showUserResponse = [];
$showUserResponse[] = $userResponse;
$showUserResponse[] = $unauthenticatedResponse;
    
// Create the operation for the route (i.e. GET, POST, etc.).
$showUser = \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation::get()
    ->responses(...$showUserResponse)
    ->tags($usersTag)
    ->summary('Get an individual user')
    ->operationId('users.show');

// Define the /users path along with the supported operations.
$paths = [];
$paths[] = \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem::create()
    ->route('/users')
    ->operations($showUser);
$paths[] = \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem::create()
    ->route('/users-v2')
    ->operations($showUser);

// Create the main OpenAPI object composed off everything created above.
$openApi = \GoldSpecDigital\ObjectOrientedOAS\OpenApi::create()
    ->openapi(\GoldSpecDigital\ObjectOrientedOAS\OpenApi::OPENAPI_3_0_2)
    ->info($info)
    ->servers(...$servers)
    ->paths(...$paths)
    ->tags($usersTag);

file_put_contents(dirname(__FILE__) . '/../public/openapi.json',$openApi->toJson());