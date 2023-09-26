# BOLETO PIX PDF-INTER

## Boleto Pix PDF

Recupera uma cobrança com código de barras e QRCode em PDF.<br>

## Escopo

Escopo requerido: boleto-cobranca.read<br>
Rate limit: 120 chamadas por minuto

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $token = '';//seu token
    $codigoCobranca = '';//informe o codigoCobranca
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $boletoPDF = $bankingInter->boletoPDFPix($codigoCobranca);
        // print_r($boletoPDF);
        // echo $boletoPDF->pdf;
        $pdf = base64_decode($boletoPDF['response']->pdf);

        header('Content-Type: application/pdf');
        echo $pdf;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
