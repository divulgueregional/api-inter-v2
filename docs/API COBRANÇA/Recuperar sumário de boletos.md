# SUMÁRIO DE BOLETOS-INTER

## Sumário de Boletos
Sumário de boletos traz o resumo dos boletos de acordo com os de filtros informados

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        'dataInicial' => '2022-04-01',//obrigatório
        'dataFinal' => '2022-04-28',//obrigatório
        'filtrarDataPor' => 'VENCIMENTO', //VENCIMENTO - EMISSAO - SITUACAO
        'situacao' => 'PAGO,EMABERTO,VENCIDO', //EXPIRADO - VENCIDO - EMABERTO - PAGO - CANCELADO
        'nome' => '',
        'email' => '',
        'cpfCnpj' => '',
        'nossoNumero' => '',
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $sumarioBoletos = $bankingInter->sumarioBoletos($filters);
        print_r($sumarioBoletos);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```