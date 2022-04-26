<?php
namespace Divulgueregional\ApiInterV2;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class InterBanking
{
    protected $certificate;
    protected $certificateKey;

    protected $client;
    protected $token;
    protected $ok;

    function __construct($dd)
    {
        $this->client = new Client([
            'base_uri' => 'https://cdpj.partners.bancointer.com.br',
        ]);
        $this->dd = $dd;
        if(isset($dd->token)){
            if($dd->token==''){
                if($dd->token_auto>1){
                    if(isset($_SESSION['tokenInter']['token'])){
                        $this->controlToken();//verifica a geração do token
                    }else{
                        //gerar o token
                        $this->gerarToken();
                    }
                }else{
                    $this->ok = "Informe o token";//é obrigatório informar o token
                }
            }else{
                $this->token = $dd->token;
            }
        }
    }

    private function controlToken(){
        date_default_timezone_set('America/Sao_Paulo');
        if($_SESSION['tokenInter']['token'] !=''){
            //token gerado, conferir validade
            if($_SESSION['tokenInter']['data'] == date('Y-m-d')){
                //data válida, verificar horário
                $hora_decorridas = gmdate('H:i:s', strtotime( date('H:i:s') ) - strtotime( $_SESSION['tokenInter']['hora'] ) );
                $hora = explode(":", $hora_decorridas);
                if($hora[0]=='00'){
                    if($hora[1]<'56'){
                        $this->token = $_SESSION['tokenInter']['token'];
                    }else{
                        //passou de 56 min, gerar novo token
                        $this->gerarToken();
                    }
                }else{
                    //passou de 1 hora, gerar token
                    $this->gerarToken();
                }
            }else{
                //data inválida, gerar token
                $this->gerarToken();
            }
        }else{
            $this->gerarToken();
        }
    }

    public function gerarToken(){
        $_SESSION['tokenInter'] = [];
        $_SESSION['tokenInter']['data'] = date('Y-m-d');
        $_SESSION['tokenInter']['hora'] = date('H:i:s');
        $_SESSION['tokenInter']['token'] = $this->getToken();
    }

    public function getToken() {
        try {
            $response = $this->client->request(
                'POST',
                '/oauth/v2/token',
                [
                    'headers' => [
                        'Accept' => 'application/json'
                    ],
                    'cert' => $this->dd->certificate,
                    'ssl_key' => $this->dd->certificateKey,
                    'form_params' => [
                        'client_id' => $this->dd->client_id,
                        'client_secret' => $this->dd->client_secret,
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

    public function checkSaldo()
    {
        if($this->ok==''){
            try {
                $response = $this->client->request(
                    'GET',
                    "/banking/v2/saldo?dataSaldo={$this->dd->dataSaldo}",
                    [
                        'verify' => $this->dd->certificate,
                        'cert' => $this->dd->certificate,
                        'ssl_key' => $this->dd->certificateKey,
                        'headers' => [
                            'Accept' => 'application/json',
                            'Authorization' => "Bearer {$this->token}"
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
        }else{
            return $this->ok;
        }
    }

    public function checkExtrato()
    {
        if($this->ok==''){
            try {
                $response = $this->client->request(
                    'GET',
                    "/banking/v2/extrato?dataInicio={$this->dd->dataInicio}&dataFim={$this->dd->dataFim}",
                    [
                        'verify' => $this->dd->certificate,
                        'cert' => $this->dd->certificate,
                        'ssl_key' => $this->dd->certificateKey,
                        'headers' => [
                            'Accept' => 'application/json',
                            'Authorization' => "Bearer {$this->token}"
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
        }else{
            return $this->ok;
        }
    }
}