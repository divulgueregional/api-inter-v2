# OBTER DADOS CIP-INTER

## Obter dados CIP
Método para obter os dados completos de um título a partir do código de barras ou da linha digitável<br>
Este endpoint está implementado com um rate-limit de 10 chamadas por minuto.

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
        $obterDadosCIP = $bankingInter->obterDadosCIP($codBarrasLinhaDigitavel);
        print_r($obterDadosCIP);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```