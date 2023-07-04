# OBTER WEBHOOK CADASTRADO-INTER

## Obtendo o webhook cadastrado
Você só pode ter 1 webhook por chave<br>
Obtém o webhook cadastrado, caso exista.<br>
Escopo requerido: webhook.read<br>
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
        $obterWebHookPix = $bankingInter->obterWebHookPix($chave);
        print_r($obterWebHookPix);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```