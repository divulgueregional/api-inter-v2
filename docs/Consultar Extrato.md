# CONSULTAR EXTRATO-INTER

## Consultar Extrato
Precisa informar a data inicial e final para poder trazer o extrato da conta.

```php
    $dd = new stdClass;
    $dd->certificate = '../cert/Inter_API_Certificado.crt';//local do certifiado crt
    $dd->certificateKey = '../cert/Inter_API_Chave.key';//local do certifiado key
    $dd->client_id = '';//seu client_id
    $dd->client_secret = '';//client_secret
    $dd->token_auto = 1;//1=não; 2=sim (caso tiver 1 é obrigado a informar o token, caso contrário a API irá gerar o token automaticamente)
    $dd->token = '';//informe o token
    $dd->dataInicio = '2022-04-01';// YYYY-MM-DD obrigatório.
    $dd->dataFim = '2022-04-26';// YYYY-MM-DD obrigatório.

    
    $bankingInter = new InterBanking($dd);

    echo "<pre>";
    $extratos = $bankingInter->checkExtrato();
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
```