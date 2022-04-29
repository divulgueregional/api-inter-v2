# CRIAR WEBHOOK-INTER

## Como criar o webhook
Crie um arquivo para receber as notificações, após informe a url desse arquivo para criar o webhook

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
        $criarWebhook = $bankingInter->criarWebhook($webhookUrl);
        print_r($criarWebhook);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```