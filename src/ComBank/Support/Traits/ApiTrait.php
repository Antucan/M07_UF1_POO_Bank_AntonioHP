<?php
namespace ComBank\Support\Traits;

use PHPUnit\Util\Json;

trait ApiTrait
{
    //public function validateEmail(string):bool{}
    public function convertBalance(float $amount): float
    {
        $api = 'https://www.amdoren.com/api/currency.php?api_key=ejXNJDM4zEKSUnKMsnu7nfN85cFbua&from=EUR&to=USD';
        $ch = curl_init();
        //mando los datos para el GET request
        curl_setopt($ch, CURLOPT_URL, $api);
        //ejecuto curl
        $result = curl_exec($ch);
        //cierro curl
        curl_close($ch);
        return 0;
    }
    //public function detectFraud(BankTransactionInterface):bool{}
}