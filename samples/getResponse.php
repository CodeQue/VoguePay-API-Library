<?php
use src\VoguePay;
include 'VoguePay/src/voguepay.php'; // change to match the location of the file
?>
<pre>
    <?php 
    $data = [
        "transactionID" => "5cc91f7ae6d54",
        "merchant" => [
            "merchantUsername" => "***", // merchant username on VoguePay
            "merchantID" => "***-***", // merchant merchantID on VoguePay
            "merchantEmail" => "****@gmail.com", // registered email address on VoguePay
            "apiToken" => "TUDMQ735hNKNaQCBkZYVHvjHqNBk", // Command API token
        ],
    ];
    echo print_r($voguepay->getResponse($data), true);
    /*
     stdClass Object
    (
        [apiProcessTime] => 0.002098 // Time take to fetch response from the API
        [buyerDetails] => stdClass Object
            (
                [name] => Ife Ojikutu // Buyer Name
                [email] => khaliqojikutu@gmail.com //Buyer email address
                [phone] => 08097040050 // buyer phone number
                [maskedPan] => 537010******8144 // Masked card details used for payment
                [cardType] => Mastercard // Card Type
            )

        [description] => API query sucessful // Api query description
        [response] => OK // API response
        [status] => OK // API Status
        [transaction] => stdClass Object
            (
                [total] => 0.27 // Transaction amount
                [status] => Approved // Transaction Status
                [token] => 5cb86b79aaf10 // Transaction Token
                [currencySymbol] => $ // Transaction Currency Symbol
                [currency] => USD // Transaction Currency ISO
                [merchantID] => 14188-19883 // Merchant ID
                [transactionID] => 5cb86b78408f4 // Transaction ID
                [transactionTime] => 13:20:08 // Time of transaction
                [transactionDate] => 2019-04-18 // Date of transaction
                [reference] => 1x2345vbn // Merchant Reference
                [description] => This is a test payment // Payment description
                [totalPaidByCustomer] => 0.27 // Total amount paid by customer
                [creditedToMerchant] => 0.27 // Total credited to merchant account
                [chargesPaid] => 0.00 // Charges paid on transaction
                [extraConfiguredCharges] => 0.00 // Extra configured charges if available
                [fundsMaturity] => 2019-04-19 // Date of funds maturity
                [responseCode] => 00 // Transaction response code
                [responseDescription] => Transaction Approved // Response code description
            )

    )
     */
    ?>
</pre>

