# CONSULTAR COBRANÇA VENCIMENTO-INTER

## Consultar cobrança vencimento
Endpoint para consultar uma cobrança com vencimento através de um determinado txid.<br>
Traz um json com as informações do pix gerado<br>
Escopo requerido: cobv.read<br>
Rate limit: 120 chamadas por minuto<br>

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $txid = 320.01;

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $consultarCobrancaVencimento = $bankingInter->consultarCobrancaVencimento($txid);
        print_r($consultarCobrancaVencimento);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```