<?php

namespace Divulgueregional\ApiInterV2;

use Exception;
use GuzzleHttp\Client;

class InterBanking
{
    protected $certificate = '../cert/Inter_API_Certificado.crt';
    protected $certificateKey = '../cert/Inter_API_Chave.key';

    protected $client;
    protected $token;

    function __construct($certificate = null, $certificateKey)
    {
        $this->client = new Client([
            'base_uri' => 'https://cdpj.partners.bancointer.com.br',
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function getToken(
        $client_id = '50481fa5-c60b-49cf-9766-3324b8efc3a5',
        $client_secret = '621079a8-db72-4da4-887c-0d6c06011c92'
    ) {
        try {
            $response = $this->client->request(
                'POST',
                '/oauth/v2/token',
                [
                    'cert' => $this->certificate,
                    'ssl_key' => $this->certificateKey,
                    'body' => [
                        'client_id' => $client_id,
                        'client_secret' => $client_secret,
                        'grant_type' => 'client_credentials',
                        'scope' => 'extrato.read boleto-cobranca.read boleto-cobranca.write'
                    ]
                ]
            );

            return $response->getBody();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function checkSaldo(string $data)
    {
        try {
            $response = $this->client->request(
                'GET',
                '/banking/v2/saldo',
                [
                    'cert' => $this->certificate,
                    'ssl_key' => $this->certificateKey,
                    'header' => [
                        'authorization' => "bearer {$this->token}"
                    ]
                ]
            );

            return $response->getBody();
        } catch (\Exception $e) {
            new Exception("Falha ao consultar saldo: {$e->getMessage()}");
        }
    }
}
