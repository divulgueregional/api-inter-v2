# Obter Cobrança Pix-INTER

## Obter Cobrança Pix

Retorna os dados da cobrança de acordo com identificadorCobranca informado.<br>
Necessita informar o nossoNumero.

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
    $codigoCobranca = '';//informe o nossoNumero
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->ObterCobrancaPix($codigoCobranca);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
