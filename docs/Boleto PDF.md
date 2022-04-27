# BOLETO PDF-INTER

## Boleto PDF
Gera o boleto em formato de pdf<br>
Necessita informar o nossoNumero.

```php
    $dd = new stdClass;
    $dd->certificate = '../cert/Inter_API_Certificado.crt';//local do certifiado crt
    $dd->certificateKey = '../cert/Inter_API_Chave.key';//local do certifiado key
    $dd->client_id = '';//seu client_id
    $dd->client_secret = '';//client_secret
    $dd->token_auto = 1;//1=não; 2=sim (caso tiver 1 é obrigado a informar o token, caso contrário a API irá gerar o token automaticamente)
    $dd->token = '';//informe o token
    $dd->nossoNumero = '';//caso não informar traz o saldo do dia

    
    $bankingInter = new InterBanking($dd);

    $boletoPDF = $bankingInter->boletoPDF();
    // print_r($boletoPDF);
    // echo $boletoPDF->pdf;
    $pdf = base64_decode($boletoPDF->pdf);

    header('Content-Type: application/pdf');
    echo $pdf;
```