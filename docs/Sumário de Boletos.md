# SUMÁRIO DE BOLETOS-INTER

## Sumário de Boletos
Sumário de boletos traz o resumo dos boletos através de filtros

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

    try {    
        $bankingInter = new InterBanking($dd);

        $sumarioBoletos = $bankingInter->sumarioBoletos();
        print_r($sumarioBoletos);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```