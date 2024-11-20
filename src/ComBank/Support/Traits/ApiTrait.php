<?php
namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;
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
        if ($response['format'] == true && $response['disposable'] == false && $response['dns'] == true) {
            return true;
        } else {
            return false;
        }

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
    public function detectFraud(BankTransactionInterface $transaction): bool
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://6737864c4eb22e24fca56ed4.mockapi.io/fraudDetection/fraud",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $fraud = false;
        foreach ($response as $key => $value) {
            if ($response[$key]['type'] == $transaction->getTransactionInfo()) {
                if ($response[$key]['balance'] <= $transaction->getAmount()) {
                    if ($response[$key]['action'] == true) {
                        $fraud = true;
                    } else {
                        $fraud = false;
                    }
                }
            }
        }
        return $fraud;
    }

    // public function validatePhoneNumber(int $phone): bool
    // {
    //     $curl = curl_init();

    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => "https://phonevalidation.abstractapi.com/v1?api_key=51f973b849734ef086499da78a7f7fd2&phone=" . $phone . "&country=ES",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "GET",
    //     ]);

    //     $response = json_decode(curl_exec($curl), true);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     return $response['valid'];
    // }
}