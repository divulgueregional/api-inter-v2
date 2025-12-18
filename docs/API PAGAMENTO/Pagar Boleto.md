# PAGAR BOLETO-INTER

## Pagar boleto
Método para inclusão de um pagamento imediato ou agendamento do pagamento de boleto, convênio ou tributo com código de barras.<br>

## Escopo

Escopo requerido: pagamento-boleto.write<br>

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- Em sandbox, podem ser utilizados os seguintes códigos de barras:

03395988500000666539201493990000372830030102
82670000000653301602023123106000000002830894

- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Request (JSON)

- codBarraLinhaDigitavel (obrigatório): string
- valorPagar (obrigatório): string
- dataPagamento (opcional): YYYY-MM-DD (se não informada, o pagamento será feito no mesmo dia)
- dataVencimento (obrigatório): YYYY-MM-DD
- cpfCnpjBeneficiario (opcional): CPF (11) ou CNPJ (14)

## Responses

- 200 Sucesso
- 400 Requisição com formato inválido.
- 401 Requisição não autorizada.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 404 Recurso solicitado não foi encontrado.
- 406 Requisição não aceita.
- 409 Conflito com o recurso do servidor.

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
        // 'sandbox' => true, //opcional
        // 'contaCorrente' => '12345678', //opcional (x-conta-corrente)
    ];

    $filters = [
        'codBarraLinhaDigitavel' =>  $Post->codBarraLinhaDigitavel,// required
        'valorPagar' =>  $Post->valorPagar,// required
        'dataPagamento' =>  $Post->dataPagamento,// Data para efetivar o pagamento. Se não informada, o pagamento será feito no mesmo dia. Formato aceito: YYYY-MM-DD
        'dataVencimento' =>  $Post->dataVencimento,// required Data de vencimento do título. Formato aceito: YYYY-MM-DD
        'cpfCnpjBeneficiario' =>  $Post->cpfCnpjBeneficiario,// optional CPF/CNPJ do beneficiário (11 ou 14 dígitos)
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $pagarBoleto = $bankingInter->pagarBoleto($filters);
        print_r($pagarBoleto['response']);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```