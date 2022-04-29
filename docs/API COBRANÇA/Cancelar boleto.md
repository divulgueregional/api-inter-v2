# CANCELAR BOLETO-INTER

## Cancelar Boleto
Necessita informar o nossoNumero.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $token = '';//seu token
    $nossoNumero = '';
    $motivo = 'ACERTOS';//ACERTOS - APEDIDODOCLIENTE - DEVOLUCAO - PAGODIRETOAOCLIENTE - SUBSTITUICAO
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $cancelarBoleto = $bankingInter->cancelarBoleto($nossoNumero, $motivo);
        print_r($cancelarBoleto);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```