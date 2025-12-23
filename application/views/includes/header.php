<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo; ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .navbar-top { background: #1a1d21; padding: 12px 0; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .nav-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 15px; }
        .nav-brand { font-size: 1.2rem; font-weight: bold; color: #fff !important; text-decoration: none !important; }
        .main-content { max-width: 1200px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); min-height: 80vh; margin-bottom: 40px; }
        .nav-links a:hover { opacity: 0.8; text-decoration: none; }
    </style>
</head>
<body>

<div class="navbar-top">
    <div class="nav-container">
        <a href="<?= site_url('cadastro'); ?>" class="nav-brand">
             SISTEMA GESTÃO
        </a>
        
        <div class="nav-links d-flex align-items-center">
            <a href="<?= site_url('cadastro'); ?>" class="btn btn-sm btn-link text-white mr-3">
                <i class="fas fa-home"></i> Início
            </a>

            <a href="javascript:void(0)" class="btn btn-sm btn-outline-light mr-3" data-toggle="modal" data-target="#modalPerfil">
                <i class="fas fa-user-circle"></i> Meu Perfil
            </a>

            <?php if ($this->session->userdata('nivel_acesso') === 'admin'): ?>
                <a href="<?= site_url('usuario'); ?>" class="btn btn-sm btn-info mr-3">
                    <i class="fas fa-users-cog"></i> Usuários
                </a>
            <?php endif; ?>

            <a href="<?= site_url('auth/logout'); ?>" class="btn btn-sm btn-danger px-3">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </div>
</div>

<div class="main-content">