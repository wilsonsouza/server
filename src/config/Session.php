<?php
/*
 * Project uget web server
 * Source session.php
 * 
 * Modified by wilson.souza
 * Last updated 2/3/2018
 * 
 * description:
 * 
 * 
 * 
 */
class Session
{
   protected $m_auth;
   protected $m_token;
   protected $m_customer;
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   public function __construct()
   {
      $this->m_auth = Flight::request ()->getVar ( HTTP_AUTH );
      Logger::info ( "Token -> " . $this->m_auth );
   }
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   public function validate()
   {
      if ($this->token () == null)
      {
         throw new UnauthorizedException ();
      }
   }
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   public function token()
   {
      if ($this->m_token == null)
      {
         $this->m_token = Flight::db ()->conn ()->select ( 'token', '*', [ 'token' => $this->m_auth ] ) [0];
      }
      
      return $this->m_token;
   }
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   public function customer()
   {
      if ($this->m_token == null)
      { 
         $this->m_token ();
      }
      
      if ($this->m_customer == null)
      {
         // $this->customer = Flight::customer()->get($this->token['customer_id']);
         $this->m_customer = Flight::customer ()->get ( 1 );
      }
      
      return $this->m_customer;
   }
}

?>