<?php
/*
 * Project uget web server
 * Source customerservice.php
 *
 * Modified by wilson.souza
 * Last updated 2/3/2018
 *
 * description:
 *
 *
 *
 */
class CustomerService extends Service
{
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function status()
   {
      echo json_encode ( [ 
            status => Flight::session ()->customer () ['status'] ] );
   }
   // ------------------------------------------------------------------------------------------------------//
   // method: add
   // parameters: json 
   // return: void
   // description: first search by field customer_id, case already exists update if not insert new record
   // ------------------------------------------------------------------------------------------------------//
   public function add()
   {
      $user_id = Flight::session()->token()['id']
      $result = Flight::db ()->conn ()->select ( "customer", [ 
            "customer_id" ], [ 
            "customer_id" => $user_id ] );
      /*
       * case the variable $result is empty, then insert new record or update
       */
      if (empty ( $result ))
      {
         Flight::db ()->conn ()->insert ( 'customer', [ 
               "id" => $user_id,
               "email" => Flight::session ()->customer () ['email'],
               "name" => Flight::session ()->customer () ['name'],
               "status" => "AUTHORIZED",
               "birth" => Flight::session ()->customer () ['birth'],
               "phone" => Flight::session ()->customer () ['phone'] ] );
         Flight::db ()->conn ()->insert ( "address", [ 
               "street" => Flight::session ()->address () ['street'],
               "complement" => Flight::session ()->address () ['complement'],
               "neighborhood" => Flight::session ()->address () ['neighborhood'],
               "city" => Flight::session ()->address () ['city'],
               "country" => Flight::session ()->address () ['country'],
               "zipcode" => Flight::session ()->address () ['zipcode'],
               "customer_id" => Flight::db ()->conn->id () ] );
         Flight::db ()->conn->insert ( "billing", [ 
               "name" => Flight::session ()->billing () ['name'],
               "cc_last_4" => Flight::session ()->billing () ['cvv'],
               "expiration" => Flight::session ()->billing () ['expiration'],
               "default" => Flight::session ()->billing () ['number'],
               "customer_id" => Flight::db ()->conn->id () ] );
         $customer_id = Flight::db ()->conn ()->id ();
      }
      else
      {
         Flight::db ()->conn ()->update ( 'customer', [
               "id" => $user_id,
               "email" => Flight::session ()->customer () ['email'],
               "name" => Flight::session ()->customer () ['name'],
               "status" => "AUTHORIZED",
               "birth" => Flight::session ()->customer () ['birth'],
               "phone" => Flight::session ()->customer () ['phone']], 
               ["customer_id" => $user_id] );
         Flight::db ()->conn ()->update ( "address", [ 
               "street" => Flight::session ()->address () ['street'],
               "complement" => Flight::session ()->address () ['complement'],
               "neighborhood" => Flight::session ()->address () ['neighborhood'],
               "city" => Flight::session ()->address () ['city'],
               "country" => Flight::session ()->address () ['country'],
               "zipcode" => Flight::session ()->address () ['zipcode'],
               "customer_id" => Flight::db ()->conn->id () ],
               ["customer_id" => $user_id ] );
         Flight::db ()->conn->update ( "billing", [ 
               "name" => Flight::session ()->billing () ['name'],
               "cc_last_4" => Flight::session ()->billing () ['cvv'],
               "expiration" => Flight::session ()->billing () ['expiration'],
               "default" => Flight::session ()->billing () ['number'],
               "customer_id" => Flight::db ()->conn->id () ],
               ["customer_id" => $user_id ] );
         $customer_id = Flight::db ()->conn ()->id ();
      }
      Flight::db ()->conn ()->update ( "token", 
            ["customer_id" => $customer_id ], 
            ["id" => $user_id ] );
   }
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function billing($customerId)
   {
      return Flight::db ()->conn ()->select ( 'billing', '*', [ 
            'customer_id' => $customerId ] ) [0];
   }
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function get($customerId)
   {
      return Flight::db ()->conn ()->select ( 'customer', '*', [ 
            'id' => $customerId ] ) [0];
   }
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function address($customerId)
   {
      return Flight::db ()->conn ()->select ( 'address', '*', [ 
            'customer_id' => $customerId ] ) [0];
   }
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function setPicture()
   {
      Flight::db ()->conn ()->update ( "customer", [ 
            "picture" => 1 ], [ 
            "id" => Flight::session ()->token () ['id'] ] );
   }
}

?>