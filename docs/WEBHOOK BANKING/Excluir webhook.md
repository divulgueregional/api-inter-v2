# EXCLUIR WEBHOOK BANKING-INTER

## Excluir webhook
Exclui o webhook.

## Escopo

Escopo requerido: webhook-banking.write<br>

## Rate limit

10 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (path)

- tipoWebhook (obrigatório): pix-pagamento, boleto-pagamento

## Header

- x-conta-corrente (opcional): string ^[1-9][0-9]*$

## Responses

- 204 Sucesso
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

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->excluirWebhookBanking($tipoWebhook);
        print_r($response);
        // Em sucesso: status 204 e response null
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
