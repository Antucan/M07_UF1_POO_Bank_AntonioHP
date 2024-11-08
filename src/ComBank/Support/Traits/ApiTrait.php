<?php
namespace ComBank\Support\Traits;
trait ApiTrait
{
    //public function validateEmail(string):bool{}
    public function convertBalance(float $amount): float
    {
        $api = 'https://www.amdoren.com/api/currency.php?api_key=ejXNJDM4zEKSUnKMsnu7nfN85cFbua&from=EUR&to=USD&amount=' . $amount;
        $ch = curl_init();
        //mando los datos para el GET request
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => 'ejXNJDM4zEKSUnKMsnu7nfN85cFbua',
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        //ejecuto curl
        $result = curl_exec($ch);

        //cierro curl
        curl_close($ch);
        return $result;

    }
    //public function detectFraud(BankTransactionInterface):bool{}
}