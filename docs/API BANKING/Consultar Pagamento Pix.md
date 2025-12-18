# CONSULTAR PAGAMENTO PIX-INTER

## Consultar Pagamento Pix
Método para consulta de um pagamento/transferência Pix utilizando o codigo solicitação.

## Escopo

Escopo requerido: pagamento-pix.read<br>

## Rate limit

20 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O período máximo para consulta é de 90 dias.
- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Header

- x-conta-corrente (opcional): string ^[1-9][0-9]*$

## Parâmetros (path)

- codigoSolicitacao (obrigatório): uuid

## Responses

- 200 Consulta realizada com sucesso
- 400 Requisição com formato inválido.
- 401 Requisição não autorizada.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
- 422 Erro de validação
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
    $codigoSolicitacao = '';//uuid

    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->consultarPagamentoPix($codigoSolicitacao);
        print_r($response);
        // $response['response']->transacaoPix
        // $response['response']->historico
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
