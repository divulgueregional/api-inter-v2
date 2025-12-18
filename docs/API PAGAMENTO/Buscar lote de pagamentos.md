# BUSCAR LOTE DE PAGAMENTOS-INTER

## Buscar lote de pagamentos
Método para obter informações de um lote de pagamentos.

## Escopo

Escopo requerido: pagamento-lote.read<br>

## Rate limit

20 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Header

- x-conta-corrente (opcional): string ^[1-9][0-9]*$

## Parâmetros (path)

- idLote (obrigatório): string (24)

## Responses

- 200 Sucesso
- 404 Recurso solicitado não foi encontrado.

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
    $idLote = '';//id do lote

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->buscarPagamentoLote($idLote);
        print_r($response);
        // $response['response']->contaCorrente
        // $response['response']->dataCriacao
        // $response['response']->pagamentos
        // $response['response']->idLote
        // $response['response']->status
        // $response['response']->meuIdentificador
        // $response['response']->qtdePagamentos
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
