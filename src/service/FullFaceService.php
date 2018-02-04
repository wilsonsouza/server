<?php
/*
 * Project uget web server
 * Source fullfaceservice.php
 *
 * Modified by wilson.souza
 * Last updated 2/3/2018
 *
 * description:
 *
 *
 *
 */
class FullFaceService extends Service
{
   protected $baseurl = "http://fullfacelab.net/FFtestelab";
   protected $headers = array (
         'Content-Type' => 'application/json' 
   );
   protected $options = array (
         'timeout' => 30 
   );
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   public function auth()
   {
      $url = $this->baseurl . "/aut/api";
      $response = Requests::post ( $url, $headers, $this->data (), $this->options );
      $this->print_response ( $response );
   }
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   public function add()
   {
      $url = $this->baseurl . "/cad/api";
      $response = Requests::post ( $url, $headers, $this->data (), $this->options );
      
      if ($response->success)
      {
         Flight::customer ()->setPicture ();
      }
      
      $this->print_response ( $response );
   }
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   private function print_response($response)
   {
      echo sprintf ( '{ "status_code": %s, "body": %s }', $response->status_code, $response->body );
   }
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   private function data()
   {
      return array (
            "chaves" => array (
                  "id",
                  ( string ) Flight::session ()->customer () ['id'] 
            ),
            "fotos" => Flight::request ()->data->pictures 
      );
   }
}

?>