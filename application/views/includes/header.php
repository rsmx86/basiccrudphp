<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (isset($titulo)) ? $titulo : 'Sistema'; ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .navbar-top { background: #1a1d21; padding: 12px 0; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .nav-container, .main-content { max-width: 1200px !important; margin-left: auto !important; margin-right: auto !important; }
        .nav-container { display: flex; justify-content: space-between; align-items: center; padding: 0 15px; }
        .nav-brand { font-size: 1.4rem !important; font-weight: 800; color: #fff !important; text-decoration: none !important; margin-left: 10px; }
        .hamburger-menu { background: none; border: none; color: white; font-size: 1.6rem; cursor: pointer; padding: 5px 10px; }
        .main-content { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); min-height: 80vh; margin-bottom: 40px; }
    </style>
</head>
<body>

<?php
// Busca dados atualizados do usuário logado para o Modal
if (!isset($usuario)) {
    $CI =& get_instance();
    $CI->load->model('usuario_model');
    $usuario = $CI->usuario_model->get_user_by_id($this->session->userdata('id_usuario'));
}
?>

<div class="navbar-top">
    <div class="nav-container">
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="hamburger-menu" id="menuLateral" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="dropdown-menu shadow-lg">
                    <h6 class="dropdown-header">MENU DE RELATÓRIOS</h6>
                    <a class="dropdown-item py-3" href="<?php echo base_url('index.php/relatorios/clientes'); ?>">
                        <i class="fas fa-file-pdf mr-3 text-danger"></i> Relatório de Clientes
                    </a>
                </div>
            </div>
            <a href="<?= site_url('cadastro'); ?>" class="nav-brand">SISTEMA GESTÃO</a>
        </div>
        
        <div class="nav-links d-flex align-items-center">
            <a href="<?= site_url('cadastro'); ?>" class="btn btn-link text-white text-decoration-none mr-3">
                <i class="fas fa-home"></i> Início
            </a>

            <?php if ($this->session->userdata('nivel_acesso') === 'admin'): ?>
                <a href="<?= site_url('usuario'); ?>" class="btn btn-sm btn-info mr-3">
                    <i class="fas fa-users-cog"></i> Usuários
                </a>
            <?php endif; ?>

            <div class="dropdown">
                <button class="btn btn-link text-white text-decoration-none dropdown-toggle p-0" type="button" data-toggle="dropdown">
                    Olá, <strong><?= explode(' ', $this->session->userdata('nome_usuario'))[0]; ?></strong>
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow-lg mt-2">
                    <div class="dropdown-header">Minha Conta</div>
                    <a class="dropdown-item py-2" href="#" data-toggle="modal" data-target="#modalPerfil">
                        <i class="fas fa-user-edit mr-2 text-primary"></i> Meu Perfil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item py-2 text-danger" href="<?= site_url('auth/logout'); ?>">
                        <i class="fas fa-sign-out-alt mr-2"></i> Sair do Sistema
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-dark">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Meu Perfil</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                
                <div class="bg-light p-3 rounded mb-4 border text-center">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted"><i class="fas fa-clock mr-1"></i> Último acesso:</span>
                        <span class="small font-weight-bold">
                            <?= (!empty($usuario['ultimo_acesso'])) ? date('d/m/Y H:i', strtotime($usuario['ultimo_acesso'])) : 'Primeiro acesso'; ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted"><i class="fas fa-key mr-1"></i> Senha alterada:</span>
                        <span class="small font-weight-bold text-primary">
                            <?= (!empty($usuario['ultima_alteracao_senha'])) ? date('d/m/Y H:i', strtotime($usuario['ultima_alteracao_senha'])) : 'Nunca alterada'; ?>
                        </span>
                    </div>
                </div>

                <form action="<?= site_url('usuario/atualizar_perfil'); ?>" method="POST">
                    <div class="form-group text-left">
                        <label>Seu Nome:</label>
                        <input type="text" name="nome" class="form-control" value="<?= $usuario['nome']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Salvar Nome</button>
                </form>
                
                <hr>
                
                <button class="btn btn-outline-danger btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseSenha">
                    Alterar Minha Senha
                </button>

                <div class="collapse mt-3" id="collapseSenha">
                    <form id="formTrocaSenha">
                        <input type="password" name="senha_atual" class="form-control mb-2" placeholder="Senha Atual" required>
                        <input type="password" name="nova_senha" class="form-control mb-2" placeholder="Nova Senha" required>
                        <input type="password" name="confirma_senha" class="form-control mb-2" placeholder="Confirme a Nova" required>
                        <button type="submit" class="btn btn-danger btn-block btn-sm">Confirmar Troca</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSenha = document.getElementById('formTrocaSenha');
    if (formSenha) {
        formSenha.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button');
            const originalText = btn.innerText;

            btn.disabled = true;
            btn.innerText = 'Processando...';

            fetch("<?= site_url('usuario/processar_troca_senha'); ?>", {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.replace(data.redirect);
                } else {
                    alert(data.message);
                    btn.disabled = false;
                    btn.innerText = originalText;
                }
            })
            .catch(error => {
                alert('Erro na comunicação com o servidor.');
                btn.disabled = false;
                btn.innerText = originalText;
            });
        });
    }
});
</script>

<div class="main-content">