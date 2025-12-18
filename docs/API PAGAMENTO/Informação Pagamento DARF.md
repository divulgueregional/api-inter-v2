# INFORMAÇÃO PAGAMENTO DARF-INTER

## Informacao Pagamento DARF
Endpoint responsável por buscar informações de pagamentos de DARF.

## Escopo

Escopo requerido: pagamento-boleto.read<br>

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O campo dataInicio é opcional, mas, caso seja informado, o campo dataFim se torna obrigatório para determinar o intervalo da busca
- O campo dataFim é opcional, mas, caso seja informado, o campo dataInicio se torna obrigatório para determinar o intervalo da busca
- Caso dataInicio e dataFim sejam nulos, por default será consultado os últimos 30 dias por data de inclusão
- Em sandbox, os dados retornados são fictícios (apenas para validação da requisição)
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (query)

- codigoSolicitacao (opcional): uuid
- codigoReceita (opcional): string
- dataInicio (opcional): YYYY-MM-DD
- dataFim (opcional): YYYY-MM-DD
- filtrarDataPor (opcional): INCLUSAO (default)

## Responses

- 200 Sucesso
- 400 Requisição com formato inválido.
- 401 Requisição não autorizada.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
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

    $filters = [
        'codigoSolicitacao' => null,
        'codigoReceita' => null,
        'dataInicio' => null,
        'dataFim' => null,
        'filtrarDataPor' => 'INCLUSAO',
    ];
    try {
        echo "<pre>";
        $response = $bankingInter->informacaoPagamentoDARF($filters);
        // print_r($response);
        foreach ($response['response'] as $darf) {
            // print_r($darf);
            echo $darf->codigoSolicitacao.
            ' - '.$darf->codigoReceita.
            ' - '.$darf->statusPagamento.
            ' - '.$darf->cnpjCpf.
            ' - '.$darf->valorTotal.
            ' - '.$darf->dataPagamento.
            ' - '.$darf->dataVencimento.'<br>';
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```