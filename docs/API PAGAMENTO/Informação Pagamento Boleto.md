# INFORMAÇÃO PAGAMENTO BOLETO-INTER

## Informacao Pagamento Boleto
Endpoint responsável por buscar informações de pagamentos de boleto.

## Escopo

Escopo requerido: pagamento-boleto.read<br>

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O campo dataInicio é opcional, mas, caso seja informado, o campo dataFim se torna obrigatório para determinar o intervalo da busca
- O campo dataFim é opcional, mas, caso seja informado, o campo dataInicio se torna obrigatório para determinar o intervalo da busca
- Caso dataInicio e dataFim sejam nulos, por default será consultado os últimos 30 dias por data de inclusão
- Período máximo entre dataInicio e dataFim é de 90 dias
- Em sandbox, podem ser utilizados os seguintes códigos de barras:

03395988500000666539201493990000372830030102
82670000000653301602023123106000000002830894

- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (query)

- codBarraLinhaDigitavel (opcional): string (44..48)
- codigoTransacao (opcional): uuid
- dataInicio (opcional): YYYY-MM-DD
- dataFim (opcional): YYYY-MM-DD
- filtrarDataPor (opcional): INCLUSAO (default), PAGAMENTO, VENCIMENTO

## Responses

- 200 Sucesso
- 400 Requisição com formato inválido.
- 500 Serviço não está disponível no momento.

```php
    require '../../../vendor/autoload.php';
    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $bankingInter = new InterBanking($config);
    $token = '';//seu token
    $bankingInter->setToken($token);

    // filtrarDataPor
    // Os filtros de data inicial e data final se aplicarão a:
    // INCLUSAO - Data da operação que foi solicitado o pagamento do título. (Default)
    // PAGAMENTO - Data em que foi efetuado o pagamento do título
    // VENCIMENTO - Data do vencimento do título de pagamento.
    $codBarrasLinhaDigitavel = '123400000012345678922012345678964663362129356789';
    $filters = [
        'codBarraLinhaDigitavel' => $codBarrasLinhaDigitavel,
        'codigoTransacao' => null,
        'dataInicio' => null,
        'dataFim' => null,
        'filtrarDataPor' => 'INCLUSAO',
    ];
    try {
        echo "<pre>";
        $response = $bankingInter->informacaoPagamentoBoleto($filters);
        // print_r($response);
        foreach ($response['response'] as $dadosBoleto) {
            // print_r($dadosBoleto);
            echo $dadosBoleto->codigoTransacao.
            ' - '.$dadosBoleto->codigoBarra.
            ' - '.$dadosBoleto->tipo.
            ' - '.$dadosBoleto->dataVencimentoDigitada.
            ' - '.$dadosBoleto->dataVencimentoTitulo.
            ' - '.$dadosBoleto->dataInclusao.
            ' - '.$dadosBoleto->dataPagamento.
            ' - '.$dadosBoleto->valorPago.
            ' - '.$dadosBoleto->valorNominal.
            ' - '.$dadosBoleto->statusPagamento.
            ' - '.$dadosBoleto->nomeBeneficiario.'<br>';
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```