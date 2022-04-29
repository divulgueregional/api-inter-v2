# CONSULTAR EXTRATO PDF-INTER

## Consultar Extrato PDF
Consultar extrato da conta e mostrar em PDF.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        'dataInicio' => '2022-04-20',//obrigatorio
        'dataFim' =>  '2022-04-28',//obrigatorio
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $extratosPDF = $bankingInter->checkExtratoPDF($filters);
        // print_r($extratosPDF);
        $pdf = base64_decode($extratosPDF['pdf']);

        header('Content-Type: application/pdf');
        echo $pdf;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```