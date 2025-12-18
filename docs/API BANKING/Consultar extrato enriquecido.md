# CONSULTAR EXTRATO ENREIQUECIDO-INTER

## Consultar Extrato Enriquecido
Consultar extrato enriquecido com informações detalhadas de cada transação dado um período específico.<br>

## Escopo

Escopo requerido: extrato.read<br>
Rate limit: 10 chamadas por minuto

## Observações

- Consulta máxima de 90 dias entre dataInicio e dataFim
- Em sandbox, os dados retornados são fictícios (apenas para validação da requisição)
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros

- pagina (opcional): integer >= 0 (default 0)
- tamanhoPagina (opcional): integer <= 10000 (default 50)
- tipoOperacao (opcional): D (Débito) ou C (Crédito)
- tipoTransacao (opcional): PIX, CAMBIO, ESTORNO, INVESTIMENTO, TRANSFERENCIA, PAGAMENTO, BOLETO_COBRANCA, OUTROS
- dataInicio (obrigatório): YYYY-MM-DD
- dataFim (obrigatório): YYYY-MM-DD

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

    $token = '';//seu token
    $bankingInter = new InterBanking($config);
    $bankingInter->setToken($token);

    //CONSULTA MÁXIMA DE 90 DIAS
    $filters = [
        'dataInicio' => '2022-10-01',//obrigatorio
        'dataFim' =>  '2022-10-17',//obrigatorio
        'tipoOperacao' =>  '',//D - Débito(Saída); C - Crédito(Entrada)
        'tipoTransacao' =>  '',// PIX, CAMBIO, ESTORNO, INVESTIMENTO, TRANSFERENCIA, PAGAMENTO, BOLETO_COBRANCA, OUTROS
        'pagina' =>  0,//Posição da página na lista de movimentações
        'tamanhoPagina' =>  50,//Tamanho da página (max: 10000)
    ];

    try {
        echo "<pre>";
        $extratos = $bankingInter->consultarExtratoEnriquecido($filters);
        print_r($extratos['response']);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```