# CANCELAR BOLETO-INTER

## Cancelar Boleto
Cancelar o boleto<br>
Necessita informar o nossoNumero.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
        'token' => '',//informe o token
        'nossoNumero' => '',//nossoNumero - obrigatorio
    ];
    $filters = [
        'motivoCancelamento' => 'ACERTOS', //obrigatorio: ACERTOS - APEDIDODOCLIENTE - DEVOLUCAO - PAGODIRETOAOCLIENTE - SUBSTITUICAO
    ];

    try {
        $bankingInter = new InterBanking();

        $cancelarBoleto = $bankingInter->cancelarBoleto($config, $filters);
        print_r($cancelarBoleto);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```