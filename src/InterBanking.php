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
    public function getToken(string $client_id, string $client_secret, $scope = 'extrato.read boleto-cobranca.read boleto-cobranca.write pagamento-boleto.read pagamento-boleto.write pagamento-pix.write cob.write cob.read pix.read pix.write webhook.write webhook.read cobv.write cobv.write cobv.read')
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

    public function IncluirPix($filters, ?string $xIdIdempotente = null)
    {
        $options = $this->optionsRequest;
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        if ($xIdIdempotente !== null && $xIdIdempotente !== '') {
            $options['headers']['x-id-idempotente'] = $xIdIdempotente;
        }
        $options['body'] = json_encode($filters);
        try {
            $response = $this->client->request(
                'POST',
                "/banking/v2/pix",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao incluir Pix: {$response}"];
        }
    }

    public function consultarPagamentoPix(string $codigoSolicitacao)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/pix/{$codigoSolicitacao}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Pix: {$response}"];
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
    ######## WEBHOOK BANKING #####################
    ##############################################
    public function criarWebhookBanking(string $tipoWebhook, string $webhookUrl)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode(['webhookUrl' => $webhookUrl]);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'PUT',
                "/banking/v2/webhooks/{$tipoWebhook}",
                $options
            );
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $result = $body !== '' ? json_decode($body) : null;
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar Webhook Banking: {$response}"];
        }
    }

    public function obterWebhookCadastradoBanking(string $tipoWebhook)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/webhooks/{$tipoWebhook}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao obter Webhook Banking: {$response}"];
        }
    }

    public function excluirWebhookBanking(string $tipoWebhook)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'DELETE',
                "/banking/v2/webhooks/{$tipoWebhook}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $result = $body !== '' ? json_decode($body) : null;
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao excluir Webhook Banking: {$response}"];
        }
    }

    public function consultarCallbacksWebhookBanking(string $tipoWebhook, $filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/webhooks/{$tipoWebhook}/callbacks",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar callbacks (Webhook Banking): {$response}"];
        }
    }

    public function reenviarCallbacksWebhookBanking(string $tipoWebhook, $body)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($body);

        try {
            $response = $this->client->request(
                'POST',
                "/banking/v2/webhooks/{$tipoWebhook}/callbacks/retry",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao reenviar callbacks (Webhook Banking): {$response}"];
        }
    }

    ##############################################
    ######## WEBHOOK COBRANÇA PIX ################
    ##############################################
    public function criarWebhookCobPIx(string $webhookUrl)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode(['webhookUrl' => $webhookUrl]);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'PUT',
                "/cobranca/v3/cobrancas/webhook",
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

    public function obterWebhookCadastradoCobPIx()
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v3/cobrancas/webhook",
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

    public function excluirWebhookCobPIx()
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Accept'] = "application/problem+json";
        try {
            $response = $this->client->request(
                'DELETE',
                "/cobranca/v3/cobrancas/webhook",
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

    public function callbacksCobPIx($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v3/cobrancas/webhook/callbacks",
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
    ######## COBRANÇAS BOLETO PIX ################
    ##############################################
    public function ObterCobrancaPix(string $codigoCobranca)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";

        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v3/cobrancas/{$codigoCobranca}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao Obter Cobranca Pix: {$response}"];
        }
    }

    public function editarCobrancaPix(string $codigoSolicitacao, $dadosEdicao)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode($dadosEdicao);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";

        try {
            $response = $this->client->request(
                'PATCH',
                "/cobranca/v3/cobrancas/{$codigoSolicitacao}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao Editar Cobranca Pix: {$response}"];
        }
    }

    public function consultarStatusEdicaoCobrancaPix(string $codigoEdicao)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";

        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v3/cobrancas/edicao/{$codigoEdicao}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao Consultar Status da Edicao da Cobranca Pix: {$response}"];
        }
    }

    public function pagarCobrancaPix(string $codigoSolicitacao, string $pagarCom)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode(['pagarCom' => $pagarCom]);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";

        try {
            $response = $this->client->request(
                'POST',
                "/cobranca/v3/cobrancas/{$codigoSolicitacao}/pagar",
                $options
            );

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $result = ($body === '') ? null : json_decode($body);
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao Pagar Cobranca Pix: {$response}"];
        }
    }

    public function pagarCobrancaPixSandbox(string $codigoSolicitacao, string $pagarCom)
    {
        return $this->pagarCobrancaPix($codigoSolicitacao, $pagarCom);
    }

    public function boletoPDFPix(string $codigoCobranca)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v3/cobrancas/{$codigoCobranca}/pdf",
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

    public function cancelarBoletoPix(string $codigoCobranca, string $motivo)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode(['motivoCancelamento' => $motivo]);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'POST',
                "/cobranca/v3/cobrancas/{$codigoCobranca}/cancelar",
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

    public function sumarioBoletosPix($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v3/cobrancas/sumario",
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

    public function colecaoBoletosPix($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca/v3/cobrancas",
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

    public function incluirBoletoCobrancaPix($dadosBoleto)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode($dadosBoleto);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'POST',
                '/cobranca/v3/cobrancas',
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao incluir Boleto Cobranca pix: {$response}"];
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
            return ['error' => "Falha ao consultar informacao de pagamento: {$response}"];
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
            return ['error' => "Falha ao consultar informacao de pagamento DARF: {$response}"];
        }
    }

    public function incluirPagamentoLote($filters)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode($filters);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'POST',
                "/banking/v2/pagamento/lote",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao incluir pagamento em lote: {$response}"];
        }
    }

    public function buscarPagamentoLote(string $idLote)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/banking/v2/pagamento/lote/{$idLote}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar pagamento em lote: {$response}"];
        }
    }

    public function cancelarAgendamentoPagamento(string $codigoTransacao)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'DELETE',
                "/banking/v2/pagamento/{$codigoTransacao}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao cancelar agendamento: {$response}"];
        }
    }

    ##############################################
    ######## PIX - COBRANÇA IMEDIATA #############
    ##############################################
    // REQUEST BODY SCHEMA
    public function criarCobrancaImediata($txid, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'PUT',
                "/pix/v2/cob/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar Cobranca imediata: {$response}"];
        }
    }

    // REQUEST BODY SCHEMA
    public function atualizarCobrancaImediata($txid, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'PATCH',
                "/pix/v2/cob/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao revisar Cobranca imediata: {$response}"];
        }
    }

    public function consultarCobrancaImediata($txid)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/cob/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Cobranca imediata: {$response}"];
        }
    }

    // REQUEST BODY SCHEMA
    public function criarCobrancaImediataPSP($filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'POST',
                "/pix/v2/cob",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar Cobranca imediata (PSP): {$response}"];
        }
    }

    // QUERY PARAMS
    public function consultarListaCobrancaImediata($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/cob",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar lista de Cobrancas imediatas: {$response}"];
        }
    }

    // SANDBOX - REQUEST BODY SCHEMA
    public function pagarCobrancaImediata($txid, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'POST',
                "/pix/v2/cob/pagar/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao pagar Cobranca imediata (Sandbox): {$response}"];
        }
    }

    public function pagarCobrancaImediataSandbox($txid, $filter)
    {
        return $this->pagarCobrancaImediata($txid, $filter);
    }

    // SANDBOX - REQUEST BODY SCHEMA
    public function pagarCobrancaImediataQRCodeSandbox($filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'POST',
                "/pix/v2/sandbox/cob/pagamento",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao pagar Cobranca imediata via QRCode (Sandbox): {$response}"];
        }
    }

    ##############################################
    ######## PIX - COBRANÇA VENCIMENTO ###########
    ##############################################
    // REQUEST BODY SCHEMA
    public function criarCobrancaVencimento($txid, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'PUT',
                "/pix/v2/cobv/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar Cobranca vencimento: {$response}"];
        }
    }

    // REQUEST BODY SCHEMA
    public function atualizarCobrancaVencimento($txid, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'PATCH',
                "/pix/v2/cobv/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao revisar Cobranca vencimento: {$response}"];
        }
    }

    public function consultarCobrancaVencimento($txid)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/cobv/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Cobranca vencimento: {$response}"];
        }
    }

    // QUERY PARAMS
    public function consultarListaCobrancaVencimento($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/cobv",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar lista de Cobrancas vencimento: {$response}"];
        }
    }

    public function pagarCobrancaVencimento($txid, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'POST',
                "/pix/v2/cobv/pagar/{$txid}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao pagar Cobranca vencimento (Sandbox): {$response}"];
        }
    }

    public function pagarCobrancaVencimentoSandbox($txid, $filter)
    {
        return $this->pagarCobrancaVencimento($txid, $filter);
    }

    public function pagarCobrancaVencimentoQRCodeSandbox($filter)
    {
        return $this->pagarCobrancaImediataQRCodeSandbox($filter);
    }

    ##############################################
    ######## PIX #################################
    ##############################################
    // PATH PARAMS
    public function consultarPix($e2eId)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/pix/{$e2eId}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao Consultar Cobranca imediata: {$response}"];
        }
    }

    // QUERY PARAMS
    public function consultarPixRecebidos($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/pix",
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

    // PATH PARAMS - BETA
    public function solicitardevolucao($e2eId, $id, $filter)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($filter);
        try {
            $response = $this->client->request(
                'PUT',
                "/pix/v2/pix/{$e2eId}/devolucao/{$id}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao Consultar Cobranca imediata: {$response}"];
        }
    }

    // PATH PARAMS
    public function consultadevolucao($e2eId, $id)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/pix/{$e2eId}/devolucao/{$id}",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao Consultar Cobranca imediata: {$response}"];
        }
    }

    ##############################################
    ######## WEBHOOK PIX #########################
    ##############################################
    // PATH PARAMETERS
    public function criarWebhookPix(string $chave, string $webhookUrl)
    {
        $options = $this->optionsRequest;
        $options['body'] = json_encode(['webhookUrl' => $webhookUrl]);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'PUT',
                "/pix/v2/webhook/{$chave}",
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao criar Webhook Pix: {$response}"];
        }
    }

    // PATH PARAMETERS
    public function obterWebhookCadastradoPix(string $chave)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/webhook/{$chave}",
                $options
            );
            https: //cdpj.partners.bancointer.com.br/pix/v2/webhook/{chave}

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

    // PATH PARAMETERS
    public function excluirWebhookPix(string $chave)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        try {
            $response = $this->client->request(
                'DELETE',
                "/pix/v2/webhook/{$chave}",
                $options
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao excluir Webhook Pix: {$response}"];
        }
    }

    // QUERY PARAMETERS
    public function callbacksPix($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        try {
            $response = $this->client->request(
                'GET',
                "/pix/v2/webhook/callbacks",
                $options
            );

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            return $this->parseResultClient($e);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao buscar callBack Pix: {$response}"];
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

    private function soNumeros($string)
    {
        $somenteNumeros = preg_replace('/[^0-9]/', '', $string);
        return $somenteNumeros;
    }
}
