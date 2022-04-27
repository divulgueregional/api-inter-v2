# COLEÇÃO DE BOLETOS-INTER

## Coleção de Boletos
Coleção de boletos traz as informações dos boletos de acordo com os de filtros informados

```php
    session_start();
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $dd = new stdClass;
    $dd->certificate = '../cert/Inter_API_Certificado.crt';//local do certifiado crt
    $dd->certificateKey = '../cert/Inter_API_Chave.key';//local do certifiado key
    $dd->client_id = '';//seu client_id
    $dd->client_secret = '';//client_secret
    $dd->token_auto = 1;//1=não; 2=sim (caso tiver 1 é obrigado a informar o token, caso contrário a API irá gerar o token automaticamente)
    $dd->token = '';//informe o token
    //FILTROS
    $dd->dataInicial = '2022-04-01';//obrigatório
    $dd->dataFinal = '2022-04-30';//obrigatório
    $dd->filtrarDataPor = 'VENCIMENTO';//VENCIMENTO - EMISSAO - SITUACAO
    $dd->situacao = 'PAGO,EMABERTO,VENCIDO';//EXPIRADO - VENCIDO - EMABERTO - PAGO - CANCELADO
    $dd->nome = '';
    $dd->email = '';
    $dd->cpfCnpj = '';
    $dd->nossoNumero = '';
    $dd->itensPorPagina = 1000;//maximo 1000
    $dd->paginaAtual = 0;
    $dd->ordenarPor = 'DATAVENCIMENTO';//PAGADOR - NOSSONUMERO - SEUNUMERO - DATASITUACAO - DATAVENCIMENTO - VALOR - STATUS
    $dd->tipoOrdenacao = 'ASC';//ASC - Crescente (Default). DESC - Decrescente

    try {
        $bankingInter = new InterBanking($dd);

        $colecaoBoletos = $bankingInter->colecaoBoletos();
        print_r($colecaoBoletos);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```