<?php
//declare file namespace
namespace merchantConfiguration;
// declare holding array details for merchant details

class merchantConfiguration {
   private $card = 'card';
   private $getResponse = 'query';
   private $api = 'https://voguepay.com/api/';


   function api () { return $this->api; }
   function card () { return $this->card; }
   function getResponse () { return $this->getResponse; }
}
$merchant = new merchantConfiguration;
?>