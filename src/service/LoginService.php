<?php
/*
 * Project uget web server
 * Source loginservice.php
 *
 * Modified by wilson.souza
 * Last updated 2/3/2018
 *
 * description:
 *
 *
 *
 */
class LoginService
{
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function add()
   {
      $data = Flight::request ()->data;
      $token = $this->generateAuthToken ();
      
      Flight::db ()->conn ()->insert ( "token", [ 
            "type" => $data->type,
            "social_id" => $data->id,
            "push_token" => $data->push_token,
            "social_token" => $data->token,
            "platform" => $data->platform,
            "token" => $token ] );
      
      echo json_encode ( [ 
            "auth" => $token ] );
   }
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   private function generateAuthToken()
   {
      return bin2hex ( openssl_random_pseudo_bytes ( 64 ) );
   }
}

?>