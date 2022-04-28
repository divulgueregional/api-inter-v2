# CONSULTAR EXTRATO-INTER

## Consultar Extrato
Precisa informar a data inicial e final para poder trazer o extrato da conta.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
        'token' => '',//informe o token
    ];
    $filters = [
        'dataInicio' => '2022-04-20',//obrigatorio
        'dataFim' =>  '2022-04-28',//obrigatorio
    ];

    try {
        $bankingInter = new InterBanking();

        echo "<pre>";
        $extratos = $bankingInter->checkExtrato($config, $filters);
        foreach ($extratos->transacoes as $extrato) {
            // print_r($extrato);
            echo $extrato->dataEntrada.
            ' - '.$extrato->tipoTransacao.
            ' - '.$extrato->tipoOperacao.
            ' - '.$extrato->titulo.
            ' - '.$extrato->descricao.
            ' - '.$extrato->valor.
            ' - '.$extrato->codigoHistorico.'<br>';
        }
        // print_r($extratos);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```