# RECUPERAR COMPROVANTE EM PDF - FOI RETIRADO DA APAI

## Recuperar comprovante em PDF
Busca o comprovante de pagamento através do código de barras ou linha digitável e retorna o base64 do PDF.<br>
Este endpoint está implementado com um rate-limit de 120 chamadas por minuto.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $codBarrasLinhaDigitavel = '';
    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $recuperarComprovantePDF = $bankingInter->recuperarComprovantePDF($codBarrasLinhaDigitavel);
        print_r($recuperarComprovantePDF);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```