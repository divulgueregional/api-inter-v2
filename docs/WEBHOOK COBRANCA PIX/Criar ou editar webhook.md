# CRIAR/EDITAR WEBHOOK-INTER

## Como criar/editar o webhook

Cria/edita o webhook.

## Escopo

Escopo requerido: boleto-cobranca.read<br>
Rate limit: 5 chamadas por minuto

## criando o webhook

Você só pode ter 1 webhook

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $webhookUrl = 'https://www.seudominio/webhook/webhook.php';//recebe as notificações
        $response = $bankingInter->criarWebhookCobPIx($webhookUrl);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
