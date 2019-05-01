<?php

namespace VoguePay;

//Include merchant namespace
//use src\merchantConfiguration;
include dirname(__FILE__).'/merchant.php';

// Include connection namespace
//use src\connection;
include dirname(__FILE__).'/connection.php';

//include response namespace
//use src\responses;
include dirname(__FILE__).'/responses.php';

class voguepay {

    function card ($data) {
        // get the global namespaces
        global $merchant, $response, $connection;
        // making data an std class
        $data = json_decode(json_encode($data));
        $reference = time().mt_rand(0,9999999);

        //generate confirmation hash
        $hash = hash('sha512', $data->merchant->apiToken.$merchant->card().$data->merchant->merchantEmail.$reference);
        // process card details
        $cardDetails = (object) [
            "card" => [
                "name" => $data->card->name, // Name of card holder
                "pan" => trim($data->card->pan), // Card Pan
                "month" => trim($data->card->month), // Expiry month 01-12
                "year" => trim($data->card->year), // expiry year, expected 2 digits e.g 21
                "cvv" => trim($data->card->cvv) // card cvv details
            ]
        ];
        
        //expected data request
        $payLoad = (object) [
            "merchant" => $data->merchant->merchantID, // merchant ID
            "task" => $merchant->card(), //Operation to be performed
            "ref" => $reference, // Random Reference
            "hash" => $hash, // Transaction Hash
            "version" => (!empty($data->version)) ? $data->version : '',
            "email" => $data->customer->email, // Transacting customer email
            "phone" => $data->customer->phone, // customer phone details
            "customerAddress" => $data->customer->address, // customer phone details
            "customerState" => $data->customer->state, // customer phone details
            "customerZip" => $data->customer->zipCode, // customer phone details
            "customerLocation" => $data->customer->country, // customer phone details
            "total" => $data->transaction->amount, // Transaction amount, round to 2 digits 10.00
            "merchant_ref" => $data->transaction->reference, // Unique transaction reference
            "currency" => $data->transaction->currency, // Trasnaction currency - {Optional}
            "memo" => $data->transaction->description, // Transaction description
            "response_url" => $data->notification->callbackUrl, // Callback Url - transaction response is sent here
            "redirect_url" => $data->notification->redirectUrl, // Redirection URL - Customer is redirected here when a transaction is completed
            "company" => (!empty($data->descriptor->companyName)) ? $data->descriptor->companyName : '', // Company name - Max allowed 100 {Optional}
            "country" => (!empty($data->descriptor->countryIso)) ? $data->descriptor->countryIso : '', // Company operational country - 3 letter ISO {Optional}
            "params" => $connection->encrypt(json_encode($cardDetails), $data->merchant->publicKey), // encrypted card data
            "riskAssessment" => json_encode($_SERVER), // Risk assesment
            "demo" => ($data->demo === true) ? true : false, // Set to true to do a testing transaction
        ];
        //initiate connection to VoguePay
        $receivedResponse = $connection->connect($payLoad);
        // validate the response received
        return $response->getResponse($receivedResponse, $data->merchant);
    }

    function getResponse($data){
        global $merchant, $connection, $response;
        $reference = time().mt_rand(0,9999999); 
        $data = json_decode(json_encode($data));      
        //generate hash
        $hash = hash('sha512',$data->merchant->apiToken.$merchant->getResponse().$data->merchant->merchantEmail.$reference);
         //process details needed for the hashing
        $payload = (object) [
            "merchant" => $data->merchant->merchantID,
            "merchant_email" => $data->merchant->merchantEmail,
            "hash" => $hash,
            "transaction_id" => $data->transactionID,
            "task" => $merchant->getResponse(),
            "ref" => $reference
        ];
        
        $receivedResponse = $connection->connect($payload);
        return $response->getResponse($receivedResponse, $data->merchant);
    }

    function chargeToken ($data) {
        global $merchant, $connection, $response;
        $data = json_decode(json_encode($data));
        $reference = time().mt_rand(0,9999999);
        //generate confirmation hash
        $hash = hash('sha512', $data->merchant->apiToken.$merchant->card().$data->merchant->merchantEmail.$reference);
        $card_details = (object) [
            "card" => [
                "cvv" => trim($data->card->cvv) // card cvv details
            ]
        ];

        //expected data request
        $payLoad = (object) [
            "version" => (!empty($data->version)) ? $data->version : '',
            "merchant" => $data->merchant->merchantID, // merchant ID
            "task" => $merchant->card(), //Operation to be performed
            "ref" => $reference, // Random Reference
            "hash" => $hash, // Transaction Hash
            "total" => $data->transaction->amount, // Transaction amount, round to 2 digits 10.00
            "email" => $data->customer->email, // Transacting customer email
            "merchant_ref" => $data->transaction->reference, // Unique transaction reference
            "currency" => $data->transaction->currency, // Trasnaction currency - {Optional}
            "memo" => $data->transaction->description, // Transaction description
            "company" => (!empty($data->descriptor->companyName)) ? $data->descriptor->companyName : '', // Company name - Max allowed 100 {Optional}
            "country" => (!empty($data->descriptor->countryIso)) ? $data->descriptor->countryIso : '', // Company operational country - 3 letter ISO {Optional}
            "params" => $connection->encrypt(json_encode($card_details), $data->merchant->publicKey), // encrypted card data
            "riskAssessment" => json_encode($_SERVER), // risk assessment evaluation
            "token" => $data->card->token
        ];
        unset ($data->version);
        unset ($data->merchant->publicKey);
        unset ($data->card);
        unset ($data->customer);
        unset ($data->transaction);
        unset ($data->descriptor);
        $receivedResponse = (object) $connection->connect($payLoad);
        $data->transactionID = $receivedResponse->reference;
        if (!empty($receivedResponse->reference)) return $this->getResponse($data);
        else return $response->getResponse($receivedResponse, $data->merchant);
    }

}

$voguepay = new voguepay;