# VoguePay API Library Suite
<p>
    <a href="https://php.net" rel="nofollow">PHP</a> 5.5+ and <a href="https://getcomposer.org" rel="nofollow">Composer</a> are required.
</p>

<p>Installation Process</p>

<div class="highlight highlight-source-shell">
    <pre>composer require voguepay/voguepay</pre>
</div>

<p>After installation, include the VoguePay class in your code. Example below</p>
<div>
    <pre>
        require_once './vendor/autoload.php'; // location of the autoload file
        use VoguePay\VoguePay;
    </pre>
</div>

<div>
    <p>The class would make available the following functions</p>
    <pre>
        VoguePay::card($payLoad);
        VoguePay::chargeToken($payLoad);
        VoguePay::getResponse($transactionDetails);
    </pre>
</div>

<div>
Using the PHP Library
<div>
    <h3>Initiating Payment</h3>
    <p>Using the VoguePay::card function</p>
</div>
<pre>
    VoguePay::card($payLoad);
</pre>
<pre>
    require_once './vendor/autoload.php'; // location to the autoload file of the composer
    use VoguePay\VoguePay;
    $payLoad = [];
    $payLoad = [
        "version" => "2", // version of the API to be called
        "merchant" => [
            "merchantUsername" => "***", // Username of Merchant On VoguePay
            "merchantID" => "***-***", // Merchant ID of account on VoguePay
            "merchantEmail" => "***@gmail.com", // Registered email of account on VoguePay
            "apiToken" => "TUDMQ735hNKNaQCBkZYVHvjHqNBk", // Command API Key of account on VoguePay
            "publicKey" => file_get_contents('key.crt') // Public Key of account on Voguepay. This is to be copied and save to a file. The location of the file is to be replaced.
        ],
        "card" => [
            "name" => "***", //Card holder name
            "pan" => "******************", //Card pan number
            "month" => "05", //Card expiry month e.g 06
            "year" => "21", //Card expiry year e.g 21
            "cvv" => "***" //Card CVV number
        ],
        "customer" => [
            "email" => "***@gmail.com", // Email of customer
            "phone" => "***********", // phone number of customer
            "address" => "*************", // address of customer
            "state" => "********", // state or province of customer
            "zipCode" => "100005", // zip code of customer
            "country" => "Nigeria" // country of country - Valid country or valid 3 letter ISO
        ],
        "transaction" => [
            "amount" => 100, //amount to be charged
            "description" => "Payment Description Goes Here", //Description of payment
            "reference" => "1x2345vbn", // Unique transaction reference, this is returned with the transaction details
            "currency" => "USD", //Supported currency USD, GBP, EUR, NGN
        ],
        "notification" => [
            "callbackUrl" => "https://yourdomain.com/", // Url where a transaction details will be sent on transaction completion
            "redirectUrl" => "https://yourdomain.com/inspection" // Url where the customer is redirected on transaction completion
        ],
        "descriptor" => [
            "companyName" => "****", // {Optional} - Company name
            "countryIso" => "NGA" //3 letter country ISO
        ],
        "demo" => false, // boolean (true / false) , set to true to initiate a demo transaction and false for live transaction
    ];
    print_r(VoguePay::card($data));
</pre>
<div>
<p>Sample successful response below</p>
<pre>
    stdClass Object
    (
        [description] => Redirection Required - 3D Authentication required. // Response code description
        [redirectUrl] => https://voguepay.com/?p=vpgate&ref=czoxMzoiNWNiZjQ2OTBlNDFkMCI7 // 3D redirection URL
        [reference] => 1x2345vbn // Transaction reference
        [response] => WL3D // Transaction response code
        [status] => OK // API query status
        [transactionID] => 5cbf4690e41d0 // Generated VoguePay transaction ID
    )
</pre>
<p>On a successful API call, this returns an array of data, which includes the 3D authentication url. [redirectUrl]</p>
<p>Redirect to the 3D authentication URL to complete transaction.</p>
<p>If there is an error, or a details prvoided is invalid, the status is represented as [status] => ERROR</p>
<p>Sample of an error response below</p>
<pre>
    stdClass Object
    (
        [description] => Incorrect CVV
        [field] => CVV
        [reference] => 1x2345vbn
        [response] => WL003
        [status] => ERROR
    )
</pre>

<p>The status [status] => OK is not a representation of a successful transaction. To get transaction status check the usage for voguepay::getResponse()</p>
<p>After payment is completed. A POST request ($_POST['transaction_id]) is sent to the callback URL and redirect URL included in the payload. This is used to get the transaction response and validate if the transaction is successful</p>

</div>
<div>
<h3>Getting transaction response</h3>
<p>oguepay::getResponse($transactionDetails)</p>
<p>Sample code below</p>
<pre>
    require_once './vendor/autoload.php';
    use VoguePay\VoguePay;
    $data = [
        "transactionID" => "5cbf4690e41d0",
        "merchant" => [
            "merchantUsername" => "***", // merchant username on VoguePay
            "merchantID" => "***-***", // merchantID of account on VoguePay
            "merchantEmail" => "***@gmail.com", // registered email address of account on VoguePay
            "apiToken" => "TUDMQ735hNKNaQCBkZYVHvjHqNBk", // Command API token of account on VoguePay
        ],
    ];
    print_r(VoguePay::getResponse($data));
</pre>
<p>Sample Transaction response below</p>
        
<pre>
    stdClass Object
    (
        [apiProcessTime] => 0.002103 // API response time
        [buyerDetails] => stdClass Object
        (
            [email] => ***@gmail.com // Customer Email address
            [phone] => *********** // Customer Phone Number
            [maskedPan] => 537010******6414 // Masked Pan used for payment
            [cardType] =>  Mastercard // Card type 
        )

        [description] => API query sucessful // API response description
        [response] => OK // API response code
        [status] => OK // API status
        [transaction] => stdClass Object
        (
            [total] => 10.00 // Transaction Amount
            [status] => Approved // Transaction status
            [currencySymbol] => ₦ // Transaction currency symbol
            [currency] => NGN // Transaction currency Code
            [merchantID] => ***-*** // Merchant ID of merchant on VoguePay
            [transactionID] => 5cbf4690e41d0 // Transaction ID of transaction on VoguePay
            [transactionDate] => 2019-05-01 // Date of transaction
            [transactionTime] => 08:30:53 // Time of transaction
            [reference] => 1x2345vbn // Reference, returned as passed in the payload. This can be used to authenticate transaction on merchant side
            [description] => This is a test payment //Payment description
            [totalPaidByCustomer] => 10.00 // Total paid by the customer
            [creditedToMerchant] => 9.85 // Amount credited to merchant account on VoguePay
            [chargesPaid] => 0.15 // Total charges paid on transaction
            [extraConfiguredCharges] => 0.00 // Extra configured charges if applicable 
            [fundsMaturity] => 2019-05-02 // Date of transaction maturity
            [responseCode] => 00 // Transaction response code
            [responseDescription] => Transaction Approved // Transaction response decription
        )
    )
</pre>
<h3>Interpreting the transaction response</h3>
<p>As explained earlier. the API [status] and [response] when OK does not mean the transaction is approved.</p>
<p>An approved transaction is interpreted by checking the transaction details of the array. [transaction][status] and [transaction][responseCode]</p>
<pre>
<h3>
A transaction is only to be approved when the 
transaction status [transaction][status] is equals to Approved
and the transaction response code [transaction][responseCode] is equals to 00
</h3>
</pre>
<p>Sample code of a declined transaction</p>
<pre>
    stdClass Object
    (
        [apiProcessTime] => 0.002103 // API response time
        [buyerDetails] => stdClass Object
        (
            [email] => ***@gmail.com // Customer Email address
            [phone] => *********** // Customer Phone Number
            [maskedPan] => 537010******6414 // Masked Pan used for payment
            [cardType] =>  Mastercard // Card type 
        )

        [description] => API query sucessful // API response description
        [response] => OK // API response code
        [status] => OK // API status
        [transaction] => stdClass Object
        (
            [total] => 10.00 // Transaction Amount
            [status] => Declined // Transaction status
            [currencySymbol] => ₦ // Transaction currency symbol
            [currency] => NGN // Transaction currency Code
            [merchantID] => ***-*** // Merchant ID of merchant on VoguePay
            [transactionID] => 5cca90d020532 // Transaction ID of transaction on VoguePay
            [transactionDate] => 2019-05-01 // Date of transaction
            [transactionTime] => 08:30:53 // Time of transaction
            [reference] => 1x2345vbn // Reference, returned as passed in the payload. This can be used to authenticate transaction on merchant side
            [description] => This is a test payment //Payment description
            [totalPaidByCustomer] => 0 // Total paid by the customer
            [creditedToMerchant] => 0 // Amount credited to merchant account on VoguePay
            [chargesPaid] => 0 // Total charges paid on transaction
            [extraConfiguredCharges] => 0 // Extra configured charges if applicable 
            [fundsMaturity] => 2019-05-02 // Date of transaction maturity
            [responseCode] => EC0571 // Transaction response code
            [responseDescription] => Transaction not Permitted to Cardholder // Transaction response decription
        )
    )
</pre>
<p>Tokenized details of the card [transaction][token] can be saved and used for future debits using VoguePay::chargeToken()</p>
<h3>Charging a card with token</h3>
<p>Using VoguePay::chargeToken()</p>
<p>Sample code below</p>
<pre>
require_once './vendor/autoload.php';
use VoguePay\VoguePay;
$data = [];
$data = [
    "version" => "2", // version of the API to be called
    "merchant" => [
        "merchantUsername" => "***", // Username of Merchant On VoguePay
        "merchantID" => "***-***", // Merchant ID of account on VoguePay
        "merchantEmail" => "***@gmail.com", // Registered email of account on VoguePay
        "apiToken" => "TUDMQ735hNKNaQCBkZYVHvjHqNBk", // Command API Key of account on VoguePay
        "publicKey" => file_get_contents('key.crt') // Public Key of account on Voguepay. This is to be copied and save to a file. The location of the file is to be replaced.
    ],
    "card" => [
        "token" => "**********", // Transaction token
        "cvv" => "948" //Card CVV number
    ],
    "customer" => [
        "email" => "***@gmail.com", // Email of customer
    ],
    "transaction" => [
        "amount" => 100, //amount to be charged
        "description" => "This is a test payment", //Description of payment
        "reference" => "1x2345vbn", // Unique transaction reference, this is returned with the transaction details
        "currency" => "USD", //Supported currency USD, GBP, EUR, NGN
    ],
    "descriptor" => [
        "companyName" => "***", // {Optional} - Company name
        "countryIso" => "NGA" // 3 letter country iso
    ],
];
print_r(VoguePay::chargeToken($data));
</pre>
<p>For sample response of VoguePay::chargeToken() make reference to VoguePay::getResponse() sample response and explanation.</p>
</div>
</div>
