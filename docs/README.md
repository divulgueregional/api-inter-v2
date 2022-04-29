# API-INTER

## Introdução

Essa documentação visa auxiliar a comunicação com a API do Banco Inter usando os padrões estabelecidos pelo banco, disponibilizando um conjunto de funcionalidade que permitem acesso seguro a dados e serviços bancários. Essa API pode ser facilmente integradas ao seu software e/ou ERP.

## PRIMEIRO PASSSO
Acesse sua conta no site do Banco do Inter e gere uma aplicação para obter o certificado crt e key.<br>
Após gerar o certificado baixe ele e pegue o client_id e client_secret.<br>
Gere o token para poder utilizar as funcionalidades da API cobranças e API Banking.

## API Banking
Estas funcionalidades incluem até o momento:

- Consultar extrato.
- Consultar saldo.
## API Cobranças
Suas funcionalidades são:

- Incluir boleto de cobrança.
- Recuperar coleção de boletos
- Recuperar sumário de boletos
- Recuperar boleto detalhado
- Recuperar boleto em PDF
- Cancelar boleto


## Webhooks
A integração via webhook oferece a possibilidade de o Inter enviar ao sistema do parceiro atualizações referentes aos serviços utilizados, em tempo real, tornando esta troca de informações mais segura, ágil e eficaz.(Em análise)