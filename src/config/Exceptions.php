<?php
/*
 * Project uget web server
 * Source exceptions.php
 *
 * Modified by wilson.souza
 * Last updated 2/3/2018
 *
 * description:
 *
 *
 *
 */
class UnauthorizedException extends Exception
{
}
class NotFoundException extends Exception
{
}
class BadRequestException extends Exception
{
   public $code = 0;
   public $msg = "";
}
class CreditCardNotFoundException extends BadRequestException
{
   public $code = 1;
   public $msg = "CreditCard not found";
}
class AddressNotFoundException extends BadRequestException
{
   public $code = 2;
   public $msg = "Address not found";
}
class BillingNotFoundException extends BadRequestException
{
   public $code = 3;
   public $msg = "Billing not found";
}
class CustomerNotAuthorizedException extends BadRequestException
{
   public $code = 3;
   public $msg = "Customer not authorized";
}

?>