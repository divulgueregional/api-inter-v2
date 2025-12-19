# CRIAR PUBLICAÇÃO-INTER

## Criar publicação
Utilizado para criar uma publicação.

## Escopo

Escopo requerido: publication.write<br>

## Rate limit

20 chamadas por minuto
120 chamadas por minuto

## Observações

- O token tem validade de 60 minutos e deverá ser reutilizado nas requisições.

## Endpoint

- Produção: POST https://cdpj.partners.bancointer.com.br/forum/v1/publication
- Sandbox: POST https://cdpj-sandbox.partners.uatinter.co/forum/v1/publication

## Request Body

Um JSON contendo informações da publicação.

- type (opcional): string (Default: POST) (Enum: POST, POLL)
- text (opcional): string
- mediaFiles (opcional): array
- poll (opcional): object
- createdAt (opcional): string <date-time>

## Responses

- 200 Publicação criada com sucesso
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

    $filters = [
        'type' => 'POST',
        'text' => 'Minha primeira publicação no Forum!',
        // 'mediaFiles' => [],
        // 'poll' => [
        //     'durationInDays' => 'ONE_DAY',
        //     'multipleChoice' => true,
        //     'options' => [],
        // ],
        // 'createdAt' => '2019-08-24T14:15:22Z',
    ];

    $token = '';//seu token

    try {
        $forum = new ApiForumInter($config);
        $forum->setToken($token);

        echo "<pre>";
        $response = $forum->criarPublicacao($filters);
        print_r($response);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```
