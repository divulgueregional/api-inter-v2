# CONSULTAR STATUS DA EDIÇÃO DA COBRANÇA PIX-INTER

## Consultar status da edição da cobrança (BETA)

Consultar edição da cobrança de acordo com `codigoEdicao` informado.

## Escopo

Escopo requerido: boleto-cobranca.read<br>
Rate limit: 10 chamadas por minuto

## Exemplo

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $token = '';//seu token
    $codigoEdicao = '';//codigoEdicao retornado ao editar a cobranca

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->consultarStatusEdicaoCobrancaPix($codigoEdicao);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
