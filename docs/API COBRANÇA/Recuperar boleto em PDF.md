# BOLETO PDF-INTER

## Boleto PDF
Gera o boleto em formato de pdf<br>
Necessita informar o nossoNumero.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $token = '';//seu token
    $nossoNumero = '';//informe o nossoNumero
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $boletoPDF = $bankingInter->boletoPDF($nossoNumero);
        // print_r($boletoPDF);
        // echo $boletoPDF->pdf;
        $pdf = base64_decode($boletoPDF['response']->pdf);

        header('Content-Type: application/pdf');
        echo $pdf;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```