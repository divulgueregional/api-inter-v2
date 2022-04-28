# BOLETO PDF-INTER

## Boleto PDF
Gera o boleto em formato de pdf<br>
Necessita informar o nossoNumero.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
        'token' => '8a5f79d1-30bb-4099-8f72-299f549c6a90',//informe o token
    ];
    $filters = [
        'nossoNumero' => '00811230921',//nossoNumero - obrigatorio
    ];

    try {
        $bankingInter = new InterBanking();

        $boletoPDF = $bankingInter->boletoPDF($config, $filters);
        // print_r($boletoPDF);
        // echo $boletoPDF->pdf;
        $pdf = base64_decode($boletoPDF->pdf);

        header('Content-Type: application/pdf');
        echo $pdf;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```