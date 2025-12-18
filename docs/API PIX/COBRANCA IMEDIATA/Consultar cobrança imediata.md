# CONSULTAR COBRANÇA IMEDIATA-INTER

## Consultar cobrança imediata
Endpoint para consultar uma cobrança através de um determinado txid.
Traz um json com as informações do pix gerado

## Escopo

Escopo requerido: cob.read

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (path)

- txid (obrigatório): string [a-zA-Z0-9]{26,35}

## Responses

- 200 Dados da cobrança imediata.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
- 503 Serviço não está disponível no momento.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $txid = '';//txid

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->consultarCobrancaImediata($txid);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```