# CONSULTAR EXTRATO-INTER

## Consultar Extrato
Consultar extrato da conta.

## Escopo

Escopo requerido: extrato.read<br>
Rate limit: 10 chamadas por minuto

## Observações

- Consulta máxima de 90 dias entre dataInicio e dataFim
- Em sandbox, os dados retornados são fictícios (apenas para validação da requisição)
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Responses

- 200 Sucesso
- 400 Requisição com formato inválido.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
- 503 Serviço não está disponível no momento.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
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
        foreach ($extratos['response']->transacoes as $extrato) {
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