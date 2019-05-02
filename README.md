# VoguePay API Library Suit
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
        use VoguePay\voguepay;
    </pre>
</div>

<div>
    <p>The class would make available the following functions</p>
    <pre>
        voguepay::card
        voguepay::chargeToken
        voguepay::getResponse
    </pre>
    Class usage and examples below
</div>