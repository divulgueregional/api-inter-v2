# OBTER LISTA DE PUBLICAÇÕES-INTER

## Obter lista de publicações
Utilizado para recuperar uma lista de publicações baseado nos parametros informados.

## Escopo

Escopo requerido: publication.read<br>

## Rate limit

20 chamadas por minuto
120 chamadas por minuto

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.

## Endpoint

- Produção: GET https://cdpj.partners.bancointer.com.br/forum/v1/publications
- Sandbox: GET https://cdpj-sandbox.partners.uatinter.co/forum/v1/publications

## Parâmetros (query)

- page (opcional): integer (default: 0)
- size (opcional): integer (default: 100)
- searchKey (opcional): string
- startDate (opcional): string <date> (exemplo: 2025-01-01)
- endDate (opcional): string <date> (exemplo: 2025-01-02)
- types (opcional): array (POST, POLL)

## Responses

- 200 Ok
- 403 Não autorizado
- 404 Não encontrado
- 500 Erro interno do servidor

## Response example (200)

```json
{
  "content": [
    {}
  ],
  "totalPages": 0,
  "totalElements": 0
}
```

## Exemplo SDK (PHP)

```php
    require '../../../vendor/autoload.php';

    use Divulgueregional\ApiInterV2\ApiForumInter;

    $config = [
        'certificate' => '../cert/Inter_API_Certificado.crt',
        'certificateKey' => '../cert/Inter_API_Chave.key',
        // 'sandbox' => true, //opcional
    ];

    $filters = [
        'page' => 0,
        'size' => 100,
        // 'searchKey' => 'inter',
        // 'startDate' => '2025-01-01',
        // 'endDate' => '2025-01-02',
        // 'types' => ['POST', 'POLL'],
    ];

    $token = '';//seu token

    try {
        $forum = new ApiForumInter($config);
        $forum->setToken($token);

        echo "<pre>";
        $response = $forum->obterListaPublicacoes($filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
