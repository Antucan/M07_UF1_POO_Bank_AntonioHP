<?php
namespace ComBank\Support\Traits;

use PHPUnit\Util\Json;

trait ApiTrait
{
    public function validateEmail(string $mail): bool
    {
        $curl = curl_init();
        //https://www.disify.com/api/email/your@example.com
        $data = [
            'email' => $mail,
        ];

        $post_data = http_build_query($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://disify.com/api/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_data,
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $response['format'];
    }
    public function convertBalance(float $amount): float
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fxratesapi.com/convert?from=EUR&to=USD&places=1&amount=" . $amount,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $response['result'];
        //preguntar si hace falta que el dominio exista o otros parametros
    }
    //public function detectFraud(BankTransactionInterface):bool{}
}