# LISTAR COBRANÇA VENCIMENTO-INTER

## Listar cobrança com vencimento
Endpoint para consultar cobranças com vencimento através de parâmetros como início, fim, cpf, cnpj e status.<br>
Escopo requerido: cobv.read<br>
Rate limit: 120 chamadas por minuto<br>

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        "inicio" => '2023-07-01T12:09:00Z',
        "fim" => '2023-07-30T12:09:00Z',
        // "txId" => null,
        // "txIdPresente" => null,
        // "devolucaoPresente" => null,
        // "cpf" => null,
        // "cnpj" => null,
        // "status" => null,
        // "paginacao.paginaAtual" => 0,
        // "paginacao.itensPorPagina" => 100,
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $consultarListaCobrancaVencimento = $bankingInter->consultarListaCobrancaVencimento($filters);
        print_r($consultarListaCobrancaVencimento);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```