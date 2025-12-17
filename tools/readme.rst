CRUD de Gestão de Cadastros - CodeIgniter 3
Sistema desenvolvido para gestão de registros com autenticação e níveis de auditoria, utilizando o framework CodeIgniter 3 e arquitetura MVC.

Principais Funcionalidades
Autenticação de utilizadores com sessão segura.

CRUD completo de registos (Create, Read, Update, Delete).

Lógica de auditoria para identificar o autor da criação/edição de cada registo.

Interface adaptada para navegação administrativa.

Stack Tecnológica
PHP 7.4.33+

CodeIgniter 3.1.x

MySQL

Bootstrap para o front-end ( todo conteudo de front-end foi utilizado ChatGPT)

Git para controle de versão

Configuração do Ambiente Local
Clonar o repositório para a pasta htdocs do XAMPP.

Criar a base de dados no MySQL com o nome: basic_cruc_di3. //atenção no nome

Importar o ficheiro SQL localizado na raiz do projeto (basic_cruc_di3.sql).

Configurar as credenciais de acesso em: application/config/database.php.

Aceder via browser em: http://localhost/ci3/index.php/auth.

Estrutura de Pastas Relevante
/application/controllers: Lógica de controle e rotas.

/application/models: Comunicação com a base de dados.

/application/views: Templates e interface do utilizador.

/database: Scripts SQL de estrutura e dados iniciais.

Próximas Implementações
Refatoração para CodeIgniter 4 ou Laravel para modernização da stack.

Implementação de validações (AJAX ou Alpine.js a verificar)

Sistema de logs de sistema para monitorização de erros.

Implementação de exportação de relatórios em formato CSV ou PDF.