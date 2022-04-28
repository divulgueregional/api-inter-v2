<?php

namespace Divulgueregional\ApiInterV2;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;

class InterBanking
{
    protected $certificate;
    protected $certificateKey;
    protected $token;
    protected $client;

    function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://cdpj.partners.bancointer.com.br',
        ]);
    }

    ##############################################
    ######## TOKEN ###############################
    ##############################################
    public function getToken($config)
    {
        try {
            $response = $this->client->request(
                'POST',
                '/oauth/v2/token',
                [
                    'headers' => [
                        'Accept' => 'application/json'
                    ],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'form_params' => [
                        'client_id' => $config['client_id'],
                        'client_secret' => $config['client_secret'],
                        'grant_type' => 'client_credentials',
                        'scope' => 'extrato.read boleto-cobranca.read boleto-cobranca.write'
                    ]
                ]
            );

            $retorno = json_decode($response->getBody()->getContents());
            if (isset($retorno->access_token)) {
                $this->token = $retorno->access_token;
            }

            return $this->token;
        } catch (\Exception $e) {
            new Exception("Falha ao gerar Token: {$e->getMessage()}");
        }
    }
    ##############################################
    ######## FIM TOKEN ###########################
    ##############################################

    ##############################################
    ######## BANKING #############################
    ##############################################
    public function checkSaldo($config, $filters)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/saldo",
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'query' => $filters,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );
            
            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();
            return ['error' => $response, 'statusCode' => $statusCode];
        } catch (\Exception $e) {
            throw new Exception("Falha ao consultar saldo: {$e->getMessage()}");
        }
    }

    public function checkExtrato($config, $filters)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/extrato",
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'query' => $filters,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();

            return ['error' => $response, 'statusCode' => $statusCode];
        } catch (\Exception $e) {
            throw new Exception("Falha ao consultar o extrato: {$e->getMessage()}");
        }
    }
    ##############################################
    ######## FIM BANKING #########################
    ##############################################


    ##############################################
    ######## COBRANÇAS ###########################
    ##############################################
    public function boletoDetalhado($config, $filters)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/{$filters['nossoNumero']}",
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();

            return ['error' => $response, 'statusCode' => $statusCode];
        } catch (\Exception $e) {
            throw new Exception("Falha ao consultar boleto detalhado: {$e->getMessage()}");
        }
    }

    public function boletoPDF($config, $filters)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/{$filters['nossoNumero']}/pdf",
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();

            return ['error' => $response, 'statusCode' => $statusCode];
        } catch (\Exception $e) {
            throw new Exception("Falha ao consultar pdf do boleto: {$e->getMessage()}");
        }
    }

    public function cancelarBoleto($config, $filters)
    {
        try {
            $response = $this->client->request(
                'POST',
                "/cobranca/v2/boletos/{$config['nossoNumero']}/cancelar",
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'body' => json_encode($filters),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();

            return ['error' => $response, 'statusCode' => $statusCode];
        } catch (\Exception $e) {
            throw new Exception("Falha ao cancelar o boleto: {$e->getMessage()}");
        }
    }

    public function sumarioBoletos($config, $filters)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/sumario",
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'query' => $filters,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();

            return ['error' => $response, 'statusCode' => $statusCode];
        } catch (\Exception $e) {
            throw new Exception("Falha ao consultar o sumário: {$e->getMessage()}");
        }
    }

    public function colecaoBoletos($config, $filters)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos",
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'query' => $filters,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();

            return ['error' => $response, 'statusCode' => $statusCode];
        } catch (\Exception $e) {
            throw new Exception("Falha ao consultar coleção de boletos: {$e->getMessage()}");
        }
    }

    public function incluirBoletoCobranca($config, $dadosBoleto)
    {
        try {
            $response = $this->client->request(
                'POST',
                '/cobranca/v2/boletos',
                [
                    'verify' => $config['certificate'],
                    'cert' => $config['certificate'],
                    'ssl_key' => $config['certificateKey'],
                    'body' => json_encode($dadosBoleto),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => "Bearer {$config['token']}"
                    ]
                ]
            );
            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $e->getResponse()->getReasonPhrase();
            $body = $e->getResponse()->getBody();
            $Version = $e->getResponse()->getProtocolVersion();
            $message = Message::toString($e->getResponse());
            return ['error' => $response, 'message' => $message, 'statusCode' => $statusCode, 'body' => $body, 'Version' => $Version, 'seek' => $body->seek(0), 'read' => $body->read(0)];
        } catch (\Exception $e) {
            throw new Exception("Falha ao incluir o boleto: {$e->getMessage()}");
        }
    }

    ##############################################
    ######## WEBHOOK #############################
    ##############################################
}