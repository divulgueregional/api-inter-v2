# OBTER WEBHOOK CADASTRADO-INTER

## Obtendo o webhook cadastrado

Obtém o webhook cadastrado, caso exista.<br>
Você só pode ter 1 webhook

## Escopo

Escopo requerido: boleto-cobranca.write<br>
Rate limit: 5 chamadas por minuto

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
        $response = $bankingInter->obterWebhookCadastradoCobPIx();
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
