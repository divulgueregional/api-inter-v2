# INCLUIR PIX-INTER

## Incluir Pix
Método para inclusão de um pagamento/transferência Pix utilizando dados bancários, chave ou código Copia e Cola.

## Escopo

Escopo requerido: pagamento-pix.write<br>

## Rate limit

60 chamadas por minuto (produção) (Limitado a 1 chamada por segundo)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Header

- x-conta-corrente (opcional): string ^[1-9][0-9]*$
- x-id-idempotente (opcional): uuid (RFC4122)

## Request (JSON)

- valor (obrigatório): number
- dataPagamento (opcional): YYYY-MM-DD (se não informada, será a data atual)
- descricao (opcional): string (<= 140)
- destinatario (obrigatório): object
  - tipo (obrigatório): CHAVE, PIX_COPIA_E_COLA, DADOS_BANCARIOS
  - chave (quando tipo=CHAVE): string
  - pixCopiaECola (quando tipo=PIX_COPIA_E_COLA): string
  - contaCorrente/tipoConta/cpfCnpj/agencia/nome (quando tipo=DADOS_BANCARIOS)

## Responses

- 200 Pagamento realizado com sucesso
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

    // Destinatário por CHAVE
    $filters = [
        'valor' => '10.00',
        'dataPagamento' => null,
        'descricao' =>  'Teste',
        'destinatario' => [
            'chave' =>  '55309496068',
            'tipo' =>  'CHAVE',
        ],
    ];

    // Destinatário por PIX_COPIA_E_COLA
    // $filters = [
    //     'valor' => '100.00',
    //     'descricao' => 'pagamento...',
    //     'destinatario' => [
    //         'tipo' => 'PIX_COPIA_E_COLA',
    //         'pixCopiaECola' => '<código copia e cola do pix a ser pago>',
    //     ],
    // ];

    // Destinatário por DADOS_BANCARIOS
    // $filters = [
    //     'valor' => '100.00',
    //     'dataPagamento' => '2023-04-06',
    //     'destinatario' => [
    //         'contaCorrente' => '<conta corrente destino>',
    //         'tipo' => 'DADOS_BANCARIOS',
    //         'tipoConta' => 'CONTA_CORRENTE',
    //         'cpfCnpj' => '<cnpj da conta corrente de destino>',
    //         'agencia' => '<código da agência>',
    //         'nome' => '<nome do recebedor>',
    //     ],
    //     'descricao' => 'DESCRICAO PIX PAGAMENTO 21/03 11:12',
    // ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        $xIdIdempotente = null; //opcional (uuid)

        echo "<pre>";
        $response = $bankingInter->IncluirPix($filters, $xIdIdempotente);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```