<?php
/*
 * Project uget web server
 * Source db.php
 *
 * Modified by wilson.souza
 * Last updated 2/3/2018
 *
 * description:
 *
 *
 *
 */
require 'vendor/autoload.php';
use Medoo\Medoo;

class DB
{
   private $m_conn;
   //------------------------------------------------------------------------------------------------------//
   //
   //
   //
   //------------------------------------------------------------------------------------------------------//
   public function __construct()
   {
      $this->m_conn = new Medoo ( [ 
            'database_type' => 'pgsql',
            'database_name' => 'uget',
            'server' => 'sportstogodb.ctddile8aoma.us-west-2.rds.amazonaws.com',
            'port' => 5432,
            'username' => 'sportstogo',
            'password' => 'hrgcFDdYNBtgcqd',
            'logging' => true 
      ] );
   }
   //------------------------------------------------------------------------------------------------------//
   // method: conn
   // parameters: none
   // return: handler of database
   //------------------------------------------------------------------------------------------------------//
   public function conn()
   {
      return $this->m_conn;
   }
}

?>