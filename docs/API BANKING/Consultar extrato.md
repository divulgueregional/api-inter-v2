# CONSULTAR EXTRATO-INTER

## Consultar Extrato
Consultar extrato da conta.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];
    
    //CONSULTA MÁXIMA DE 90 DIAS
    $filters = [
        'dataInicio' => '2022-04-20',//obrigatorio
        'dataFim' =>  '2022-04-28',//obrigatorio
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $extratos = $bankingInter->checkExtrato($filters);
        // print_r($extratos);
        foreach ($extratos['transacoes'] as $extrato) {
            // print_r($extrato);
            echo $extrato->dataEntrada.
            ' - '.$extrato->tipoTransacao.
            ' - '.$extrato->tipoOperacao.
            ' - '.$extrato->titulo.
            ' - '.$extrato->descricao.
            ' - '.$extrato->valor.
            ' - '.$extrato->codigoHistorico.'<br>';
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```