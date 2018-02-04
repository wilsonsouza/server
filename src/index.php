<?php
require 'vendor/autoload.php';

use Medoo\Medoo;

require 'config/DB.php';
require 'config/Filters.php';
require 'config/Logger.php';
require 'config/Exceptions.php';
require 'config/Session.php';
require 'service/Service.php';
require 'service/BuyService.php';
require 'service/LoginService.php';
require 'service/CustomerService.php';
require 'service/FullFaceService.php';

// Singletons
Flight::register ( 'db', 'DB' );
Flight::register ( 'session', 'Session' );
Flight::register ( 'buy', 'BuyService' );
Flight::register ( 'login', 'LoginService' );
Flight::register ( 'customer', 'CustomerService' );
Flight::register ( 'fullface', 'FullFaceService' );

// ------------------------------------------------------------------------------------------------------//
// method: route
// parameters: command of type string
// return: boolean
// ------------------------------------------------------------------------------------------------------//
Flight::route ( '*', function ()
{
   if (Flight::request ()->url !== "/login" && Flight::request ()->url !== "/info")
   {
      Flight::session ()->validate ();
   }
   return true;
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command login
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /login', function ()
{
   Flight::login ()->add ();
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command info
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'GET /info', function ()
{
   phpinfo ();
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command customer
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /customer', function ()
{
   Flight::customer ()->add ();
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command customer/status
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'GET /customer/status', function ()
{
   Flight::customer ()->status ();
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command customer/id
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /customer/id', function ()
{
   // TODO
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command customer/picture
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /customer/picture', function ()
{
   Flight::fullface ()->add ();
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command customer/check_picture
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /customer/check_picture', function ()
{
   Flight::fullface ()->auth ();
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command buy
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /buy', function ()
{
   Flight::buy ()->create ();
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command buy/id
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'GET /buy/@id:[0-9]+', function ($id)
{
   Flight::buy ()->get ( $id );
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command buy/id{mask}/contest
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'GET /buy/@id:[0-9]+/contest', function ($id)
{
   // TODO
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command comment
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /comment', function ()
{
   // TODO
} );
// ------------------------------------------------------------------------------------------------------//
// method: route -> command finish
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::route ( 'POST /finish', function ()
{
   // TODO
} );
// ------------------------------------------------------------------------------------------------------//
// method: map -> manager of exceptions
// parameters: command of type string
// return: void
// ------------------------------------------------------------------------------------------------------//
Flight::map ( 'error', function (Exception $ex)
{
   Logger::error ( "An error occurred while processing the request... " . $ex );
   
   if ($ex instanceof UnauthorizedException)
   {
      Flight::halt ( 401 );
   }
   else if ($ex instanceof NotFoundException)
   {
      Flight::halt ( 404 );
   }
   else if ($ex instanceof BadRequestException)
   {
      Logger::error ( "Request failed with: " . $ex->msg );
      Flight::halt ( 400, json_encode ( [ 
            "error" => [ 
                  "code" => $ex->code,
                  "msg" => $ex->msg 
            ] 
      ] ) );
   }
} );
// ------------------------------------------------------------------------------------------------------//
// start web server operations
// ------------------------------------------------------------------------------------------------------//
Flight::start ();

?>