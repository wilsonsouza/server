<?php
/*
 * Project uget web server
 * Source buyservice.php
 *
 * Modified by wilson.souza
 * Last updated 2/3/2018
 *
 * description:
 *
 *
 *
 */
class BuyService
{
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function validateCreate($customer, $billing, $address)
   {
      if (empty ( $billing ))
      {
         throw new BillingNotFoundException ();
      }
      else if (empty ( $address ))
      {
         throw new AddressNotFoundException ();
      }
      else if ($customer ['status'] !== 'AUTHORIZED')
      {
         throw new CustomerNotAuthorizedException ();
      }
   }
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function create()
   {
      $customer = Flight::session ()->customer ();
      $billing = Flight::customer ()->billing ( $customer ['id'] );
      $address = Flight::customer ()->address ( $customer ['id'] );
      $this->validateCreate ( $customer, $billing, $address );
      
      $machine_id = Flight::request ()->data->machine;
      $items = Flight::request ()->data->items;
      
      Flight::db ()->conn ()->insert ( "buy", [ 
            "machine_id" => $machine_id,
            "customer_id" => Flight::session ()->customer () ['id'] ] );
      $buy_id = Flight::db ()->conn ()->id ();
      if (empty ( $buy_id ))
      {
         throw new BadRequestException ();
      }
      $total = 0;
      foreach ( $items as $item )
      {
         $product = $this->getProduct ( $item ['code'], $machine_id );
         if (empty ( $product ))
         {
            throw new BadRequestException ();
         }
         Flight::db ()->conn ()->insert ( "buy_items", [ 
               "buy_id" => $buy_id,
               "product_id" => $item ['code'],
               "quantity" => $item ['quantity'] ] );
         $total = $total + $item ['quantity'] * $product ['value'];
      }
      
      Flight::db ()->conn ()->update ( "buy", [ 
            "total" => $total ], [ 
            "id" => $buy_id ] );
      
      echo json_encode ( [ 
            "buy_id" => $buy_id ] );
   }
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function getProduct($id, $machine)
   {
      return Flight::db ()->conn ()->select ( 'product', '*', [ 
            'id' => $id,
            'machine_id' => $machine ] ) [0];
   }
   // ------------------------------------------------------------------------------------------------------//
   //
   //
   //
   // ------------------------------------------------------------------------------------------------------//
   public function get($id)
   {
      $buy = Flight::db ()->conn ()->select ( 'buy', [ 
            "[>]machine" => [ 
                  "machine_id" => "id" ],
            "[>]customer" => [ 
                  "customer_id" => "id" ],
            "[>]billing" => [ 
                  "customer.id" => "customer_id" ] ], [ 
            "buy.id",
            "buy.total",
            "machine.location (machine)",
            "billing.cc_last_4" ], [ 
            'buy.id' => $id,
            'buy.customer_id' => Flight::session ()->customer () ['id'],
            'billing.default' => 1 ] );
      
      if (empty ( $buy ))
      {
         throw new NotFoundException ();
      }
      $items = Flight::db ()->conn ()->select ( 'buy_items', [ 
            "[>]product" => [ 
                  "product_id" => "id" ] ], [ 
            "product.name",
            "product.desc",
            "buy_items.quantity",
            "product.value" ], [ 
            'buy_items.buy_id' => $buy [0] ['id'] ] );
      
      $buy [0] ['items'] = $items;
      echo json_encode ( $buy [0] );
   }
}

?>