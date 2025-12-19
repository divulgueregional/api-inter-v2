<?php

namespace Divulgueregional\ApiInterV2;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;

class ApiForumInter
{
    protected $token;
    protected $optionsRequest = [];

    private $client;
    private const BASE_URI_PROD = 'https://cdpj.partners.bancointer.com.br';
    private const BASE_URI_SANDBOX = 'https://cdpj-sandbox.partners.uatinter.co';

    function __construct(array $config)
    {
        $baseUri = self::BASE_URI_PROD;
        if (isset($config['base_uri']) && $config['base_uri'] !== '') {
            $baseUri = $config['base_uri'];
        } elseif (!empty($config['sandbox']) || (isset($config['environment']) && strtolower((string) $config['environment']) === 'sandbox')) {
            $baseUri = self::BASE_URI_SANDBOX;
        }

        $this->client = new Client([
            'base_uri' => $baseUri,
        ]);

        if (isset($config['verify'])) {
            if ($config['verify'] == '') {
                $verify = false;
            } elseif ($config['verify'] != '' && $config['verify'] != 1) {
                $verify = $config['verify'];
            } else {
                $verify = $config['certificate'];
            }
        } else {
            $verify = $config['certificate'];
        }

        $headers = [
            'Accept' => 'application/json'
        ];

        $contaCorrente = null;
        if (isset($config['contaCorrente'])) {
            $contaCorrente = $config['contaCorrente'];
        } elseif (isset($config['conta_corrente'])) {
            $contaCorrente = $config['conta_corrente'];
        } elseif (isset($config['x_conta_corrente'])) {
            $contaCorrente = $config['x_conta_corrente'];
        } elseif (isset($config['x-conta-corrente'])) {
            $contaCorrente = $config['x-conta-corrente'];
        }

        if ($contaCorrente !== null && (string) $contaCorrente !== '') {
            $contaCorrente = preg_replace('/\D+/', '', (string) $contaCorrente);
            if ($contaCorrente !== '') {
                $headers['x-conta-corrente'] = $contaCorrente;
            }
        }

        $this->optionsRequest = [
            'headers' => $headers,
            'cert' => $config['certificate'],
            'verify' => $verify,
            'ssl_key' => $config['certificateKey'],
        ];
    }

    ##############################################
    ######## TOKEN ###############################
    ##############################################
    public function getToken(string $client_id, string $client_secret, $scope = 'publication.read publication.write publication.delete')
    {
        $options = $this->optionsRequest;
        $options['form_params'] = [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'client_credentials',
            'scope' => $scope
        ];

        try {
            $response = $this->client->request(
                'POST',
                '/oauth/v2/token',
                $options
            );

            return (array) json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => $response];
        }
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    ##############################################
    ######## FORUM - PUBLICATION #################
    ##############################################

    public function obterPublicacao($id)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['id'] = $id;

        try {
            $response = $this->client->request(
                'GET',
                '/forum/v1/publication',
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter publicacao: {$response}"];
        }
    }

    public function criarPublicacao($filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);

        try {
            $response = $this->client->request(
                'POST',
                '/forum/v1/publication',
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar publicacao: {$response}"];
        }
    }

    public function editarPublicacao($id, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['id'] = $id;
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);

        try {
            $response = $this->client->request(
                'PUT',
                '/forum/v1/publication',
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao editar publicacao: {$response}"];
        }
    }

    public function deletarPublicacao($id)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['id'] = $id;

        try {
            $response = $this->client->request(
                'DELETE',
                '/forum/v1/publication',
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao deletar publicacao: {$response}"];
        }
    }

    public function obterListaPublicacoes($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                '/forum/v1/publications',
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter lista de publicacoes: {$response}"];
        }
    }

    ##############################################
    ######## FERRAMENTAS #########################
    ##############################################
    private function parseResultClient($result)
    {
        $statusCode = $result->getResponse()->getStatusCode();
        $response = $result->getResponse()->getReasonPhrase();
        $body = $result->getResponse()->getBody()->getContents();

        return ['error' => $body, 'response' => $response, 'statusCode' => $statusCode];
    }
}
