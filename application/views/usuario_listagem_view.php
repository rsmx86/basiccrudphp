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
        .btn-back { background-color: #6c757d; color: white; margin-right: 10px; }
        .btn-back:hover { background-color: #5a6268; }

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

        /* Estilo dos Níveis de Acesso */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .badge-admin { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .badge-comum { background: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-area">
        <div>
            <h1>⚙️ Gestão de Usuários</h1>
            <small>Gerencie quem tem acesso ao sistema e seus níveis de permissão.</small>
        </div>
        <div>
            <a href="<?php echo site_url('cadastro'); ?>" class="btn btn-back">← Voltar aos Cadastros</a>
            <a href="<?php echo site_url('usuario/criar'); ?>" class="btn btn-add">+ Criar Novo Usuário</a>
        </div>
    </div>

    <?php if ($this->session->flashdata('sucesso')): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            <?php echo $this->session->flashdata('sucesso'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('erro')): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <?php echo $this->session->flashdata('erro'); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail (Login)</th>
                <th>Nível</th>
                <th>Criado Por</th>
                <th style="text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><strong><?php echo $user['nome']; ?></strong></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <span class="badge <?php echo ($user['nivel_acesso'] == 'admin') ? 'badge-admin' : 'badge-comum'; ?>">
                            <?php echo $user['nivel_acesso']; ?>
                        </span>
                    </td>
                    <td>
                        <small style="color: #888;">
                            <?php echo !empty($user['nome_criador']) ? $user['nome_criador'] : '<span style="color:#ccc">Sistema</span>'; ?>
                        </small>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo site_url('usuario/editar/'.$user['id']); ?>" 
                           style="color: #007bff; text-decoration: none; font-weight: bold;">
                           Editar Nível
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 30px;">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
