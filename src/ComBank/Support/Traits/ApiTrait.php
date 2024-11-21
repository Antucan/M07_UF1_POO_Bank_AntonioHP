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
    public function detectFraud(BankTransactionInterface $transaction): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://6737864c4eb22e24fca56ed4.mockapi.io/fraudDetection/fraud",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $risk = null;
        $fraud = false;
        foreach ($response as $key => $value) {
            if ($response[$key]['type'] == $transaction->getTransactionInfo()) {
                if ($response[$key]['balance'] <= $transaction->getAmount()) {
                    if ($response[$key]['action'] == true) {
                        $risk = $response[$key]['risk'];
                        $fraud = true;
                    } else {
                        $risk = $response[$key]['risk'];
                        $fraud = false;
                    }
                }
            }
        }
        return [$risk, $fraud];
    }

    public function validatePhoneNumber(int $phone): bool
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/number_verification/validate?number=34" . $phone,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: qPI0dqxHffXb9Mxu4Oz0MWWcw8VVcyAU"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);
        if ($response['valid'] == true) {
            return true;
        } else {
            return false;
        }
    }
}