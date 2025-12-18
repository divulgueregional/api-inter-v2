# INCLUIR PAGAMENTOS EM LOTE-INTER

## Incluir pagamentos em lote
Método para inclusão de um lote de pagamentos.

## Escopo

Escopo requerido: pagamento-lote.write<br>

## Rate limit

10 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente
- Em sandbox, podem ser utilizados os seguintes códigos de barras:

03395988500000666539201493990000372830030102
82670000000653301602023123106000000002830894

## Header

- x-conta-corrente (opcional): string ^[1-9][0-9]*$

## Request (JSON)

- meuIdentificador (opcional): string (<= 30)
- pagamentos (obrigatório): array (2..150)

## Responses

- 202 Sucesso
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

    $token = '';//seu token

    $filters = [
        'meuIdentificador' => 'Lote 01',
        'pagamentos' => [
            [
                'tipoPagamento' => 'BOLETO',
                'codBarraLinhaDigitavel' => '07791924100000002500001112068423600932691183',
                'valorPagar' => '2.5',
                'dataVencimento' => '2022-01-25',
                'dataPagamento' => '',
            ],
            [
                'tipoPagamento' => 'DARF',
                'cnpjCpf' => '08951851648',
                'codigoReceita' => '0191',
                'dataVencimento' => '2023-06-22',
                'descricao' => 'Pagamento DARF Janeiro',
                'nomeEmpresa' => 'Minha Empresa2',
                'periodoApuracao' => '2022-06-06',
                'referencia' => '03199991111336',
                'telefoneEmpresa' => '0319999111111231313',
                'valorJuros' => 10.11,
                'valorMulta' => 27.48,
                'valorPrincipal' => 50.15,
            ],
        ],
    ];

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->incluirPagamentoLote($filters);
        print_r($response);
        // $response['response']->idLote
        // $response['response']->status
        // $response['response']->meuIdentificador
        // $response['response']->qtdePagamentos
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
