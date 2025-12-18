# CRIAR COBRANÇA IMEDIATA-INTER

## Criar cobrança imediata
Endpoint para criar uma cobrança imediata.

## Escopo

Escopo requerido: cob.write<br>

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- Você gera o txid.
- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (path)

- txid (obrigatório): string [a-zA-Z0-9]{26,35}

## Responses

- 201 Cobrança imediata criada
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

    $txid = '';//gerado por você, min 26 max 35 caracteres
    $filters = [
        "calendario" => [
            "expiracao" => 86400,//Tempo de vida da cobrança, especificado em segundos a partir da data de criação (Calendario.criacao)
        ],
        "devedor" => [
            // Os campos aninhados sob o objeto devedor são opcionais.
            // Se o campo devedor.nome está preenchido, então deve existir um devedor.cpf OU um devedor.cnpj.
            // Não é permitido que cpf e cnpj estejam preenchidos ao mesmo tempo.
            "cpf" => '',
            "nome" => "",
            //ou
            // "cnpj" => '',
            // "nome" => "",
        ],
        "valor" => [
            "original" => '10.00',//string required- valores monetários referentes à cobrança.
            "modalidadeAlteracao" => 0,//int Trata-se de um campo que determina se o valor final do documento pode ser alterado pelo pagador. Na ausência desse campo, assume-se que não se pode alterar o valor do documento de cobrança, ou seja, assume-se o valor 0. Se o campo estiver presente e com valor 1, então está determinado que o valor final da cobrança pode ter seu valor alterado pelo pagador.
        ],
        "chave" => "",//string chave Pix do recebedor. Chave podem ser: telefone, e-mail, cpf/cnpj ou EVP.
        "solicitacaoPagador" => "Meus serviços",//O campo solicitacaoPagador determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto está limitado a 140 caracteres.
        // "infoAdicionais" => [
        //     [
        //         "nome" => '"Campo 1',//required
        //         "valor" => "Informação Adicional1 do PSP-Recebedor",//required
        //     ],
        // ],
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->criarCobrancaImediata($txid, $filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```