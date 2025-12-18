# CONSULTAR LISTA DE COBRANÇAS IMEDIATAS-INTER

## Consultar lista de cobranças imediatas
Endpoint para consultar cobranças imediatas através de parâmetros como início, fim, cpf, cnpj e status.

## Escopo

Escopo requerido: cob.read<br>

## Rate limit

120 chamadas por minuto (produção)
10 chamadas por minuto (sandbox)

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.
- Header x-conta-corrente é necessário somente quando a aplicação estiver associada a mais de uma conta corrente

## Parâmetros (query)

- inicio (obrigatório): string <date-time>
  - Formato: yyyy-MM-dd'T'HH:mm:ss[.SSS]XXX
  - Exemplo: 2024-03-01T00:00:00Z
- fim (obrigatório): string <date-time>
  - Formato: yyyy-MM-dd'T'HH:mm:ss[.SSS]XXX
  - Exemplo: 2024-03-20T00:00:00Z
- cpf (opcional): string
  - Filtro pelo CPF do devedor. Não pode ser utilizado ao mesmo tempo que o CNPJ.
- cnpj (opcional): string
  - Filtro pelo CNPJ do devedor. Não pode ser utilizado ao mesmo tempo que o CPF.
- locationPresente (opcional): boolean
- status (opcional): string
  - Enum: ATIVA, CONCLUIDA, REMOVIDA_PELO_USUARIO_RECEBEDOR, REMOVIDA_PELO_PSP
- paginacao.paginaAtual (opcional): integer (default: 0)
- paginacao.itensPorPagina (opcional): integer (default: 100, máximo: 1000)

## Parâmetros (header)

- x-conta-corrente (opcional): string[1-9][0-9]*

## Responses

- 200 Lista de cobranças imediatas.
- 403 Requisição de participante autenticado que viola alguma regra de autorização.
- 503 Serviço não está disponível no momento.

## Exemplo curl

```bash
#!/bin/zsh

# ------------------------------------
# Listando cobrancas imediatas...
# ------------------------------------

URL_COBS="https://cdpj.partners.bancointer.com.br/pix/v2/cob"

LISTA_COBRANCAS=$(curl \
  -X GET \
  -H "Authorization: Bearer <seu_token>" \
  -H "Content-Type: application/json" \
  -H "x-conta-corrente: <conta corrente selecionada>" \
  --cert <nome arquivo certificado>.crt \
  --key <nome arquivo chave privada>.key \
  --get \
  --data-urlencode "inicio=2024-03-01T00:00:00Z" \
  --data-urlencode "fim=2024-03-20T00:00:00Z" \
  --data-urlencode "paginacao.itensPorPagina=10" \
  --data-urlencode "paginacao.paginaAtual=2" \
 $URL_COBS)

echo $LISTA_COBRANCAS

exit 0
```

## Exemplo SDK (PHP)

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
        'inicio' => '2024-03-01T00:00:00Z',
        'fim' => '2024-03-20T00:00:00Z',
        'paginacao.itensPorPagina' => 10,
        'paginacao.paginaAtual' => 2,
        // 'cpf' => '00000000000',
        // 'cnpj' => '00000000000000',
        // 'locationPresente' => true,
        // 'status' => 'ATIVA',
    ];

    $token = '';//seu token
    try {
        $bankingInter = new InterBanking($config);
        $bankingInter->setToken($token);

        echo "<pre>";
        $response = $bankingInter->consultarListaCobrancaImediata($filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
