# SIGE – Sistema de Gerenciamento de Eventos Acadêmicos

## Sobre o projeto

O SIGE é um sistema desenvolvido para facilitar o gerenciamento de eventos acadêmicos, permitindo organizar eventos, cadastrar participantes, controlar inscrições e consultar relatórios.

## Funcionalidades

- Cadastro, edição, consulta e exclusão de eventos.
- Cadastro, edição, consulta e exclusão de participantes.
- Gerenciamento de inscrições.
- Controle de vagas disponíveis.
- Prevenção de inscrições duplicadas.
- Cancelamento de inscrições.
- Relatórios de eventos e participantes.

## Tecnologias utilizadas

- PHP
- MySQL
- HTML
- CSS
- XAMPP
- MySQL Workbench

## Banco de dados

O sistema utiliza o banco de dados MySQL, com tabelas para eventos, participantes e inscrições.

O arquivo `database/sige.sql` contém a estrutura e os dados necessários para criar o banco de dados.

## Como executar o projeto

1. Instale o XAMPP e o MySQL Server.
2. Coloque a pasta `projetoSIGE` no diretório `htdocs` do XAMPP.
3. Crie o banco de dados com o nome `sige`.
4. Importe o arquivo `database/sige.sql` pelo MySQL Workbench.
5. Configure a conexão com o banco de dados.

## Configuração da conexão

1. Entre na pasta `config`.
2. Faça uma cópia do arquivo `database.example.php`.
3. Renomeie a cópia para `Database.php`.
4. Informe os dados de conexão do seu MySQL, incluindo a senha correta.
5. Salve o arquivo.

**Importante:** o arquivo `Database.php` contém informações locais de conexão e não deve ser enviado ao GitHub.

## Acesso ao sistema

1. Inicie o Apache no XAMPP.
2. Inicie o MySQL Server.
3. Acesse pelo navegador:

`http://localhost/projetoSIGE/`

## Objetivo acadêmico

Projeto desenvolvido com o objetivo de aplicar conhecimentos de programação PHP, banco de dados MySQL, orientação a objetos e desenvolvimento de sistemas web.