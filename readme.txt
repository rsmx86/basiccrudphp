# Sistema de Gerenciamento de Usuários - CRUD com Framework Codeignter3

Este é um sistema de controle de usuários e perfis desenvolvido em PHP utilizando o framework CodeIgniter 3. Para otimização do fluxo de trabalho, utilizei componentes de interface (Bootstrap) de projetos anteriores e apliquei ferramentas de IA para acelerar a estruturação de lógica de logs e prototipagem de componentes, garantindo foco total nas regras de negócio e segurança do sistema.

## Funcionalidades

* Autenticação de Usuários: Sistema de login com proteção de sessão.
* Controle de Acesso (ACL): Diferenciação de permissões entre Administradores e Usuários Comuns.
* Gerenciamento de Usuários: CRUD completo (Create, Read, Update, Delete) com validação de dados.
* Segurança de Senha: Uso de algoritmos de hash (password_hash) para armazenamento seguro.
* Auditoria e Logs: Registro de último acesso e controle de fuso horário (Timezone America/Sao_Paulo). **Em desenvolvimento**
* Integridade de Dados: Sistema de exclusão com transferência de custódia de registros para administradores.
* Perfil do Usuário: Interface para atualização de dados pessoais e alteração de senha via requisições assíncronas (AJAX).

## Tecnologias Utilizadas

* Framework: CodeIgniter 3.1.13
* Linguagem: PHP 7.4+
* Banco de Dados: MySQL
* Interface: Bootstrap 4 e FontAwesome 5
* Comunicação: jQuery e JSON para retornos de API interna

## Instalação
 - Clonar o repositório com git glone