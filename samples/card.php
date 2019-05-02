<?php
require_once './vendor/autoload.php'; // location of the autoload file
use VoguePay\voguepay;
$data = [];
$data = [
    "version" => "2", // version of the API to be called 
    "merchant" => [
        "merchantUsername" => "***", // merchant username on VoguePay
        "merchantID" => "***-***", // merchant merchantID on VoguePay
        "merchantEmail" => "****@gmail.com", // registered email address on VoguePay
        "apiToken" => "TUDMQ735hNKNaQCBkZYVHvjHqNBk", // Command API token
        "publicKey" => file_get_contents('key.crt') // location of the stored public key
    ],
    "card" => [
        "name" => "**********", //Card holder name
        "pan" => "****************", //Card pan number
        "month" => "**", //Card expiry month e.g 06
        "year" => "**", //Card expiry year e.g 21
        "cvv" => "***" //Card CVV number
    ],
    "customer" => [
        "email" => "**********@gmail.com", // Email of country
        "phone" => "1234567890", // phone number of country
        "address" => "***************", // address of customer
        "state" => "*************", // state or province of customer
        "zipCode" => "*****", // zip code of customer
        "country" => "***" // country of country - Valid country or valid 3 letter ISO
    ],
    "transaction" => [
        "amount" => 100, //amount to be charged
        "description" => "Payment Description Goes Here", //Description of payment
        "reference" => "1x2345vbn", // Unique transaction reference, this is returned with the transaction details
        "currency" => "USD", //Supported currency USD, GBP, EUR, NGN
    ],
    "notification" => [
        "callbackUrl" => "https://triggerme.com/transaction_notification", // Url where a transaction details will be sent on transaction completion
        "redirectUrl" => "https://redirecttome.com/transaction_notification" // Url where the customer is redirected on transaction completion
    ],
    "descriptor" => [
        "companyName" => "*******", // {Optional} - Company name
        "countryIso" => "***" //3 letter country ISO
    ],
    "demo" => "false", // boolean (true / false) , set to true to imitate a demo transaction and false for live transaction
];
?>
<pre>
    <?php 
        echo print_r($voguepay->card($data), true); 
        /*
        stdClass Object
        (
            [description] => Redirection Required - 3D Authentication required. // Response code description
            [redirectUrl] => https://voguepay.com/?p=vpgate&ref=czoxMzoiNWNiZjQ2OTBlNDFkMCI7 // 3D redirection URL
            [reference] => 1x2345vbn // Transaction reference
            [response] => WL3D // Transaction response
            [status] => OK // API query status
            [transactionID] => 5cbf4690e41d0 // Generated VoguePay transaction ID
        )
        */
    ?>
</pre>