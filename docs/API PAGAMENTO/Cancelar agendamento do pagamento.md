# CANCELAR AGENDAMENTO PAGAMENTO-INTER

## Cancelar agendamento do pagamento
Método utilizado para cancelar o agendamento do pagamento de um boleto.<br>
 
## Escopo
 
Escopo requerido: pagamento-boleto.write<br>
 
## Rate limit
 
20 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)
 
## Observações
 
- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente
 
## Responses
 
- 204 Sucesso
- 404 Recurso solicitado não foi encontrado.
- 422 Não foi possível processar a requisição.
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
    $codigoTransacao = '';//Código da Transação
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);
        echo "<pre>";
        $response = $bankingInter->cancelarAgendamentoPagamento($codigoTransacao);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```