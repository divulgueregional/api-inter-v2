# CONSULTAR EXTRATO ENREIQUECIDO-INTER

## Consultar Extrato Enriquecido
Consultar extrato enriquecido com informações detalhadas de cada transação dado um período específico.<br>
Rate limit: 10 chamadas por minuto

```php
    require '../../../vendor/autoload.php';
    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
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
        'tamanhoPagina' =>  50,// max: 10000. Tamanho da página na lista de movimentações por dia
        'itensPorPagina' => '',// Valor máximo: 1000, Default value : 100
        'paginaAtual' =>  '',//Página a ser retornada pela consulta. Se não for informada, assumirá que será 0.
    ];
    
    try {
        echo "<pre>";
        $extratos = $bankingInter->consultarExtratoEnriquecido($filters);
        print_r($extratos);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```