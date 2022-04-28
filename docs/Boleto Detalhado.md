# BOLETO DETALHADO-INTER

## Consultar boleto
Busca todos os dados do boleto<br>
Necessita informar o nossoNumero.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
        'token' => '8bbf3cc6-f832-44bf-a316-18e5bf58cc0b',//informe o token
    ];
    $filters = [
        'nossoNumero' => '00811230921',//nossoNumero - obrigatorio
    ];

    try {
        $bankingInter = new InterBanking();

        echo "<pre>";
        $boletoDetalhado = $bankingInter->boletoDetalhado($config, $filters);
        print_r($boletoDetalhado);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```