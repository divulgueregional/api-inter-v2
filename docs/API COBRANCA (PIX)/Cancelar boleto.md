# CANCELAR BOLETO PIX-INTER

## Cancelar Boleto pix

Cancela uma cobrança com o codigoCobranca informado.

## Escopo

Escopo requerido: boleto-cobranca.write<br>
Rate limit: 120 chamadas por minuto

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $token = '';//seu token
    $codigoCobranca = '';
    $motivo = 'ACERTOS';//<= 50 characters; Motivo pelo qual a cobrança está sendo cancelada
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $response = $bankingInter->cancelarBoletoPix($codigoCobranca, $motivo);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```

Status: 204 - Sucesso, boleto cancelado
