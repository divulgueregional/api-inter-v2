# BOLETO DETALHADO-INTER

## Consultar boleto
Busca todos os dados do boleto<br>
Necessita informar o nossoNumero.

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
    $dd->nossoNumero = '';//caso não informar traz o saldo do dia

    try {
        $bankingInter = new InterBanking($dd);

        echo "<pre>";
        $boletoDetalhado = $bankingInter->boletoDetalhado();
        print_r($boletoDetalhado);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```