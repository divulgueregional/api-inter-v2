# OBTER PUBLICAÇÃO PELO ID-INTER

## Obter publicação pelo ID
Utilizado para recuperar uma publicação pelo ID.

## Escopo

Escopo requerido: publication.read<br>

## Rate limit

20 chamadas por minuto
120 chamadas por minuto

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.

## Endpoint

- Produção: GET https://cdpj.partners.bancointer.com.br/forum/v1/publication
- Sandbox: GET https://cdpj-sandbox.partners.uatinter.co/forum/v1/publication

## Parâmetros (header)

- id (obrigatório): string

## Responses

- 200 Ok
- 204 Publicação não encontrada
- 403 Não autorizado

## Response example (200)

```json
{
  "type": "POST",
  "id": "string",
  "text": "string",
  "mediasInformation": {
    "images": [],
    "videos": [],
    "documents": []
  },
  "card": {
    "type": "ASSET",
    "header": {},
    "body": {},
    "footer": {},
    "action": {},
    "metadata": {}
  },
  "poll": {
    "expiresAt": "2019-08-24T14:15:22Z",
    "multipleChoice": true,
    "optionsPoll": []
  },
  "likes": 0,
  "comments": 0,
  "reposts": 0,
  "quotes": 0,
  "answers": 0,
  "createdAt": "2019-08-24T14:15:22Z",
  "updatedAt": "2019-08-24T14:15:22Z"
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
        $response = $forum->obterPublicacao($id);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
