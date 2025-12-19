# DELETAR PUBLICAÇÃO-INTER

## Deletar publicação
Utilizado para deletar uma publicação pelo ID.

## Escopo

Escopo requerido: publication.delete<br>

## Rate limit

20 chamadas por minuto
120 chamadas por minuto

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.

## Endpoint

- Produção: DELETE https://cdpj.partners.bancointer.com.br/forum/v1/publication
- Sandbox: DELETE https://cdpj-sandbox.partners.uatinter.co/forum/v1/publication

## Parâmetros (header)

- id (obrigatório): string

## Responses

- 200 Publicação deletada com sucesso
- 403 Não autorizado

## Response example (403)

```json
{
  "code": "string",
  "title": "Not found",
  "detail": "string",
  "timestamp": "2019-08-24T14:15:22Z",
  "violacoes": [
    {}
  ]
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

    $id = '';//id da publicação
    $token = '';//seu token

    try {
        $forum = new ApiForumInter($config);
        $forum->setToken($token);

        echo "<pre>";
        $response = $forum->deletarPublicacao($id);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
