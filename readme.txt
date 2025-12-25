Sistema de Gestão de Cadastros (CRUD)

Projeto desenvolvido em **PHP (CodeIgniter 3)** para gerenciamento de clientes, controle de acesso e geração de relatórios.

# Funcionalidades
- **Autenticação:** Login seguro com níveis de acesso (Admin e Usuário).
- **CRUD Completo:** Cadastro, edição, listagem e exclusão de registros.
- **Filtros Avançados:** Busca por cidade, data e usuário responsável.
- **Relatórios:** Geração de relatórios profissionais em **PDF** usando a biblioteca Dompdf.

# Tecnologias Utilizadas
- PHP 7.4+ / CodeIgniter 3
- MySQL (Banco de dados)
- Bootstrap 4 (Interface)
- Dompdf (Gerador de PDF)

## 📋 Como instalar
1. Clone o repositório.
2. Importe o arquivo SQL da pasta `/docs` no seu MySQL.
3. Ajuste as configurações de banco em `application/config/database.php`.
4. Configure sua base_url em `application/config/config.php`.