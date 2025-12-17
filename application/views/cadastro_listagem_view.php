<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    <style>
        body {
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        h1 { margin: 0; font-size: 24px; color: #2c3e50; }
        
        .btn {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }
        .btn-add { background-color: #28a745; color: white; }
        .btn-add:hover { background-color: #218838; }
        .btn-admin { background-color: #17a2b8; color: white; margin-right: 10px; }
        .btn-logout { background-color: #dc3545; color: white; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f8f9fa;
            color: #666;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #dee2e6;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        tr:hover { background-color: #f9f9f9; }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .badge-admin { background: #d1ecf1; color: #0c5460; }
        .badge-comum { background: #e2e3e5; color: #383d41; }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-area">
        <div>
            <h1><?php echo $titulo; ?></h1>
            <small>Olá, <strong><?php echo $this->session->userdata('nome_usuario'); ?></strong> 
            <span class="badge <?php echo ($this->session->userdata('nivel_acesso') == 'admin') ? 'badge-admin' : 'badge-comum'; ?>">
                <?php echo $this->session->userdata('nivel_acesso'); ?>
            </span></small>
        </div>
        <div>
            <?php if ($this->session->userdata('nivel_acesso') === 'admin'): ?>
                <a href="<?php echo site_url('usuario'); ?>" class="btn btn-admin">⚙️ Gerenciar Usuários</a>
            <?php endif; ?>
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-logout">Sair</a>
        </div>
    </div>

    <?php if ($this->session->flashdata('sucesso')): ?>
        <div class="alert-success">
            <?php echo $this->session->flashdata('sucesso'); ?>
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <a href="<?php echo site_url('cadastro/criar'); ?>" class="btn btn-add">+ Novo Cadastro</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Bairro</th>
                <th>Cadastrado Por</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cadastros)): ?>
                <?php foreach ($cadastros as $cadastro): ?>
                <tr>
                    <td><?php echo $cadastro['id']; ?></td>
                    <td><strong><?php echo $cadastro['nome']; ?></strong></td>
                    <td><?php echo $cadastro['endereco']; ?></td>
                    <td><?php echo $cadastro['bairro']; ?></td>
                    <td><small><?php echo $cadastro['nome_cadastrador']; ?></small></td>
                    <td>
                        <a href="<?php echo site_url('cadastro/editar/'.$cadastro['id']); ?>" style="color: #007bff; text-decoration: none;">Editar</a>
                        <?php if ($this->session->userdata('nivel_acesso') === 'admin'): ?>
                            | <a href="<?php echo site_url('cadastro/deletar/'.$cadastro['id']); ?>" 
                               style="color: #dc3545; text-decoration: none;" 
                               onclick="return confirm('Tem certeza que deseja excluir o cadastro de <?php echo addslashes($cadastro['nome']); ?>?')">Excluir</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 30px;">Nenhum registro encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>