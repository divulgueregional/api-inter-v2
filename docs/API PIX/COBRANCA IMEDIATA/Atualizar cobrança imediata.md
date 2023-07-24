# ATUALIZAR COBRANÇA IMEDIATA-INTER

## Atualizar cobrança imediata
Endpoint para atualizar cobrança imediata.<br>
Escopo requerido: cob.write<br>
Rate limit: 120 chamadas por minuto<br>

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\InterBanking;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',//local do certificado crt
        'certificateKey' => '../cert/Inter_API_Chave.key',//local do certificado key
    ];

    $txid = ''; //gerado ao criar o pix  
    $filters = [
        "calendario" => [
            "expiracao" => 86400,//Tempo de vida da cobrança, especificado em segundos a partir da data de criação (Calendario.criacao)
        ],
        "devedor" => [
            "cpf" => '',//required
            "nome" => '',//required
            //ou
            // "cnpj" => '',//required
            // "nome" => '',//required
        ],
        "valor" => [
            "original" => '5.00',//string required- valores monetários referentes à cobrança.
            "modalidadeAlteracao" => 0,//int Trata-se de um campo que determina se o valor final do documento pode ser alterado pelo pagador. Na ausência desse campo, assume-se que não se pode alterar o valor do documento de cobrança, ou seja, assume-se o valor 0. Se o campo estiver presente e com valor 1, então está determinado que o valor final da cobrança pode ter seu valor alterado pelo pagador.
        ],
        // "chave" => "",//string chave Pix do recebedor. Chave podem ser: telefone, e-mail, cpf/cnpj ou EVP.
        "solicitacaoPagador" => "Pix Texte",//O campo solicitacaoPagador determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto está limitado a 140 caracteres.
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
        $atualizarCobrancaImediata = $bankingInter->atualizarCobrancaImediata($txid, $filters);
        print_r($atualizarCobrancaImediata);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```