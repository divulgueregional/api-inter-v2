# BOLETO DETALHADO-INTER

## Consultar boleto
Busca todos os dados do boleto<br>
Necessita informar o nossoNumero.

## Problema identificado
Consultar 5 boletos por minutos ou se enviar 10 requisições o Banco Inter retorna (Token inválido.) nas demais requisições / consultas.

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

        echo "<pre>";
        $boletoDetalhado = $bankingInter->boletoDetalhado($nossoNumero);
        print_r($boletoDetalhado);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```