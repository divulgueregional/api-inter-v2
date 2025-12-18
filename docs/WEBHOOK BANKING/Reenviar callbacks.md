# REENVIAR CALLBACKS WEBHOOK BANKING-INTER

## Reenviar callbacks
Método utilizado para reenviar uma mensagem de callback de um pagamento de boleto ou pagamento pix, através de seu código identificador.

- boleto-pagamento: codigoTransacao
- pix-pagamento: codigoSolicitacao

A funcionalidade permite o envio de até 50 códigos identificadores, por tipo de webhook, para o reenvio de seus respectivos callbacks.

## Escopo

Escopo requerido: webhook-banking.write<br>

## Rate limit

5 chamadas por minuto

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (path)

- tipoWebhook (obrigatório): pix-pagamento, boleto-pagamento

## Header

- x-conta-corrente (opcional): string ^[1-9][0-9]*$

## Request (JSON)

- Para tipoWebhook=pix-pagamento:
  - codigoSolicitacao (obrigatório): array de uuid (1..50)
- Para tipoWebhook=boleto-pagamento:
  - codigoTransacao (obrigatório): array de string (1..50)

## Responses

- 200 Sucesso
- 400 Problemas na requisição.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
- 500 Serviço não está disponível no momento.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $token = '';//seu token
    $tipoWebhook = 'pix-pagamento';

    $body = [
        'codigoSolicitacao' => [
            '5aa9e369-fc6c-4e03-82b7-aa09fdbaa210',
        ],
    ];

    // Se tipoWebhook=boleto-pagamento:
    // $tipoWebhook = 'boleto-pagamento';
    // $body = [
    //     'codigoTransacao' => ['c42f0787-02cb-4b31-827e-459ec9d7ece1'],
    // ];

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->reenviarCallbacksWebhookBanking($tipoWebhook, $body);
        print_r($response);
        // $response['response']->foundIds
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
