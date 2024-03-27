<?php

namespace Divulgueregional\cielolioremoto;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;
use JetBrains\PhpStorm\NoReturn;

class CieloLioRemoto
{
    protected $header;
    protected $headers;
    private $client;
    protected $optionsRequest = [];

    function __construct(array $config, $sandbox = false)
    {
        $url = 'https://api.cielo.com.br/order-management/v1';
        if ($sandbox) {
            $url = 'https://api.cielo.com.br/sandbox-lio/order-management/v1';
        }
        $this->client = new Client([
            'base_uri' => $url,
        ]);

        $this->optionsRequest = [
            'headers' => [
                'accept' => 'application/json',
                'client-id' => $config['client-id'],
                'access-token' => $config['access-token'],
                'merchant-id' => $config['merchant-id'],
            ],
        ];
    }

    ##############################################
    ######## ORDEM ###############################
    ##############################################
    public function criarPedido($filter)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'POST',
                "/orders",
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar pagamento: {$response}"];
        }
    }

    public function cancelarPedido(string $id)
    {
        $options = $this->optionsRequest;
        try {
            $response = $this->client->request(
                'DELETE',
                "/orders/{$id}",
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao cancelar intenção de pagamento: {$response}"];
        }
    }

    public function buscarPedido(string $id)
    {
        $options = $this->optionsRequest;
        try {
            $response = $this->client->request(
                'GET',
                "/orders/{$id}",
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar intenção de pagamento: {$response}"];
        }
    }

    public function listarPedidos()
    {
        $options = $this->optionsRequest;
        try {
            $response = $this->client->request(
                'GET',
                "/orders",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao listar os pagamentos: {$response}"];
        }
    }
}
