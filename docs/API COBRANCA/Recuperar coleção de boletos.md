# COLEÇÃO DE BOLETOS-INTER

## Coleção de Boletos
Coleção de boletos traz as informações dos boletos de acordo com os de filtros informados

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $filters = [
        'dataInicial' => '2022-04-01',//obrigatório
        'dataFinal' => '2022-04-28',//obrigatório
        'filtrarDataPor' => 'VENCIMENTO', //VENCIMENTO - EMISSAO - SITUACAO
        'situacao' => 'PAGO,EMABERTO,VENCIDO', //EXPIRADO - VENCIDO - EMABERTO - PAGO - CANCELADO
        'nome' => '',
        'email' => '',
        'cpfCnpj' => '',
        'nossoNumero' => '',
        'itensPorPagina' => 1000, //maximo 1000
        'paginaAtual' => 0,
        'ordenarPor' => 'DATAVENCIMENTO', //PAGADOR - NOSSONUMERO - SEUNUMERO - DATASITUACAO - DATAVENCIMENTO - VALOR - STATUS
        'tipoOrdenacao' => 'ASC', //ASC - Crescente (Default). DESC - Decrescente
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $colecaoBoletos = $bankingInter->colecaoBoletos($filters);
        print_r($colecaoBoletos);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```