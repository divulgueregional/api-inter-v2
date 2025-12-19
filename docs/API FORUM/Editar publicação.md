# EDITAR PUBLICAÇÃO-INTER

## Editar publicação
Utilizado para editar uma publicação pelo ID.

## Escopo

Escopo requerido: publication.write<br>

## Rate limit

20 chamadas por minuto
120 chamadas por minuto

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.

## Endpoint

- Produção: PUT https://cdpj.partners.bancointer.com.br/forum/v1/publication
- Sandbox: PUT https://cdpj-sandbox.partners.uatinter.co/forum/v1/publication

## Parâmetros (header)

- id (obrigatório): string

## Request Body

Um JSON contendo informações da publicação.

- text (opcional): string
- mediasInformation (opcional): object
- mediaFiles (opcional): array

## Responses

- 200 Publicação atualizada com sucesso
- 400 Requisição inválida
- 403 Não autorizado

## Response example (200)

```json
"string"
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
    $filters = [
        'text' => 'Texto atualizado da publicação',
        // 'mediasInformation' => [
        //     'images' => [],
        //     'videos' => [],
        //     'documents' => [],
        // ],
        // 'mediaFiles' => [],
    ];

    $token = '';//seu token

    try {
        $forum = new ApiForumInter($config);
        $forum->setToken($token);

        echo "<pre>";
        $response = $forum->editarPublicacao($id, $filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
