<?php

namespace Divulgueregional\ApiInterV2;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;

class InterBanking
{
    protected $token;
    protected $optionsRequest = [];

    private $client;
    function __construct(array $config)
    {
        $this->client = new Client([
            'base_uri' => 'https://cdpj.partners.bancointer.com.br',
        ]);

        if(isset($config['verify'])){
            if($config['verify']==''){
                $verify = false;
            }elseif($config['verify'] !='' && $config['verify'] !=1){
                $verify = $config['verify'];
            }else{
                $verify = $config['certificate'];
            }
        }else{
            $verify = $config['certificate'];
        }

        $this->optionsRequest = [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'cert' => $config['certificate'], 
            'verify' => $verify,
            'ssl_key' => $config['certificateKey'],
        ];
    }

    ##############################################
    ######## TOKEN ###############################
    ##############################################
    public function getToken(string $client_id, string $client_secret, $scope = 'extrato.read boleto-cobranca.read boleto-cobranca.write pagamento-boleto.read pagamento-boleto.write')
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
    ######## FIM TOKEN ###########################
    ##############################################

    ##############################################
    ######## BANKING #############################
    ##############################################
    public function checkSaldo($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/saldo",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar saldo: {$response}"];
        }
    }

    public function checkExtrato($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/extrato",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar o extrato: {$response}"];
        }
    }

    public function checkExtratoPDF($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/extrato/exportar",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar Extrato em PDF: {$response}"];
        }
    }

    public function consultarExtratoEnriquecido($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/extrato/completo",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar o extrato Completo: {$response}"];
        }
    }
    ##############################################
    ######## FIM BANKING #########################
    ##############################################


    ##############################################
    ######## COBRANÇAS ###########################
    ##############################################
    public function boletoDetalhado(string $nossoNumero)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/{$nossoNumero}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar boleto Detalhado: {$response}"];
        }
    }

    public function boletoPDF(string $nossoNumero)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/{$nossoNumero}/pdf",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar boleto em PDF: {$response}"];
        }
    }

    public function cancelarBoleto(string $nossoNumero, string $motivo)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode(['motivoCancelamento' => $motivo]);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'POST',
                "/cobranca/v2/boletos/{$nossoNumero}/cancelar",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao cancelar Boleto: {$response}"];
        }
    }

    public function sumarioBoletos($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/sumario",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar o sumário: {$response}"];
        }
    }

    public function colecaoBoletos($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar coleção de boletos: {$response}"];
        }
    }

    public function incluirBoletoCobranca($dadosBoleto)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode($dadosBoleto);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'POST',
                '/cobranca/v2/boletos',
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao incluir Boleto Cobranca: {$response}"];
        }
    }

    ##############################################
    ######## WEBHOOK #############################
    ##############################################
    public function criarWebhook(string $webhookUrl)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode(['webhookUrl' => $webhookUrl]);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'PUT',
                "/cobranca/v2/boletos/webhook",
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar Webhook: {$response}"];
        }
    }

    public function obterWebhookCadastrado()
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/webhook",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter Webhook Cadastrado: {$response}"];
        }
    }

    public function excluirWebhook()
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'DELETE',
                "/cobranca/v2/boletos/webhook",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao excluir Webhook: {$response}"];
        }
    }

    public function callbacks($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v2/boletos/webhook/callbacks",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar Extrato em PDF: {$response}"];
        }
    }

    ##############################################
    ######## PAGAMENTOS ##########################
    ##############################################
    public function obterDadosCIP($codBarrasLinhaDigitavel)
    {
        $codBarrasLinhaDigitavel = $this->soNumeros($codBarrasLinhaDigitavel);
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/pagamento/{$codBarrasLinhaDigitavel}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter dados CIP: {$response}"];
        }
    }

    public function pagarBoleto($filters)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode($filters);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'POST',
                "/banking/v2/pagamento",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter dados CIP: {$response}"];
        }
    }

    public function informacaoPagamentoBoleto($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/pagamento",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar saldo: {$response}"];
        }
    }

    //essa função foi retirada
    public function recuperarComprovantePDF($codBarrasLinhaDigitavel)
    {
        $codBarrasLinhaDigitavel = $this->soNumeros($codBarrasLinhaDigitavel);
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/pagamento/comprovante/{$codBarrasLinhaDigitavel}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter dados CIP: {$response}"];
        }
    }

    public function pagarDARF($filters)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode($filters);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'POST',
                "/banking/v2/pagamento/darf",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter dados CIP: {$response}"];
        }
    }

    public function informacaoPagamentoDARF($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/pagamento/darf",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar saldo: {$response}"];
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
    
    private function soNumeros($string){
        $somenteNumeros = preg_replace('/[^0-9]/', '', $string);
        return $somenteNumeros;
    }
}