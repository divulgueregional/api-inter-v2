# EXCLUIR WEBHOOK-INTER

## Excluindo o webhook cadastrado


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
        $excluirWebhook = $bankingInter->excluirWebhook();
        print_r($excluirWebhook);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```