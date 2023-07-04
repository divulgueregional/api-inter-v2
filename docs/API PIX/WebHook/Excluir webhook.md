# EXCLUIR WEBHOOK-INTER

## Excluindo o webhook cadastrado
Exclui o webhook.<br>
Escopo requerido: webhook.write<br>
Rate limit: 10 chamadas por minuto

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $chave '';//sua chave
    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $excluirWebHookPix = $bankingInter->excluirWebHookPix($chave);
        print_r($excluirWebHookPix);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```