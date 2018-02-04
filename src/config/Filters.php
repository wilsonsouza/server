<?php
/*
 * Project uget web server
 * Source filters.php
 *
 * Modified by wilson.souza
 * Last updated 2/3/2018
 *
 * description:
 *
 *
 *
 */
//------------------------------------------------------------------------------------------------------//
//
//
//
//------------------------------------------------------------------------------------------------------//
Flight::before ( 'start', function (&$params, &$output)
{
   Logger::info ( "Starting request -> " . Flight::request ()->method . ' ' . Flight::request ()->url );
} );
//------------------------------------------------------------------------------------------------------//
//
//
//
//------------------------------------------------------------------------------------------------------//
Flight::after ( 'start', function (&$params, &$output)
{
   Logger::info ( "Finishing request -> " . Flight::request ()->method . ' ' . Flight::request ()->url );
} );

?>