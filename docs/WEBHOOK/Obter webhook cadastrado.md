# OBTER WEBHOOK CADASTRADO-INTER

## Obtendo o webhook cadastrado
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
        $obterWebhookCadastrado = $bankingInter->obterWebhookCadastrado();
        print_r($obterWebhookCadastrado);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```