# API-INTER

## Introdução

Essa documentação visa auxiliar a implementação com a API do Banco Inter usando os padrões estabelecidos pelo banco, disponibilizando um conjunto de funcionalidade que permitem acesso seguro a dados e serviços bancários. Essa biblioteca pode ser facilmente integrada ao seu software e/ou ERP.

## PRIMEIRO PASSSO
Acesse sua conta no site do Banco do Inter e gere uma aplicação para obter o certificado crt e key.<br>
Após gerar o certificado baixe ele e pegue o client_id e client_secret.<br>
Gere o token para poder utilizar as funcionalidades da API Banking e API cobranças


# ENDPOINTS DA API DO BANCO INTER

## AUTENTICAÇÃO OAUTH

<b>Token</b>
- Obter token oAuth

## API Cobrança

<b>Boletos</b>
- Incluir boleto de cobrança.
- Recuperar coleção de boletos
- Recuperar sumário de boletos
- Recuperar boleto detalhado
- Recuperar boleto em PDF
- Cancelar boleto

<b>Webhook</b>
- Criar webhook.
- Obter webhook cadastrado
- Excluir webhook
- webhook.php

## API Banking

<b>Extrato</b>
- Consultar extrato
- Consultar extrato em pdf

<b>Saldo</b>
- Consultar saldo

<b>Pagamento</b>
- Obter dados CIP.
- Incluir pagamento com código de barras
- Busca informações de pagamentos de boleto
- Incluir pagamento de DARF
- Busca informações de pagamentos DARF