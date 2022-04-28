# CONSULTAR SALDO-INTER

## Consultar Saldo
Para trazer o saldo da conta

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
        'token' => '',//informe o token
    ];
    $filters = [
        'dataSaldo' => '',//YY-MM-DD caso não informar traz o saldo do dia
    ];

    try {
        $bankingInter = new InterBanking();

        echo "<pre>";
        $saldo = $bankingInter->checkSaldo($config, $filters);
        print_r($saldo);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```