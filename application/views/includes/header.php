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
        .navbar-top { background: #1a1d21; padding: 12px 0; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .nav-container, .main-content { max-width: 1200px !important; margin-left: auto !important; margin-right: auto !important; }
        .nav-container { display: flex; justify-content: space-between; align-items: center; padding: 0 15px; }
        .nav-brand { font-size: 1.4rem !important; font-weight: 800; color: #fff !important; text-decoration: none !important; margin-left: 10px; }
        .hamburger-menu { background: none; border: none; color: white; font-size: 1.6rem; cursor: pointer; padding: 5px 10px; }
        .user-greeting { color: #adb5bd; font-size: 1rem; border-left: 1px solid #444; padding-left: 15px; margin-left: 10px; white-space: nowrap; }
        .main-content { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); min-height: 80vh; margin-bottom: 40px; }
        .dropdown-menu-left-custom { min-width: 250px; margin-top: 10px; }
    </style>
</head>
<body>

<div class="navbar-top">
    <div class="nav-container">
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="hamburger-menu" id="menuLateral" data-toggle="dropdown">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-left-custom shadow-lg">
                    <h6 class="dropdown-header">MENU DE RELATÓRIOS</h6>
                    <a class="dropdown-item py-3" href="<?php echo base_url('index.php/relatorios/clientes'); ?>">
                        <i class="fas fa-file-pdf mr-3 text-danger"></i> Relatório de Clientes
                    </a>
                </div>
            </div>
            <a href="<?= site_url('cadastro'); ?>" class="nav-brand">SISTEMA GESTÃO</a>
        </div>
        
        <div class="nav-links d-flex align-items-center">
            <a href="<?= site_url('cadastro'); ?>" class="btn btn-link text-white text-decoration-none mr-2">
                <i class="fas fa-home"></i> Início
            </a>

            <?php if ($this->session->userdata('nivel_acesso') === 'admin'): ?>
                <a href="<?= site_url('usuario'); ?>" class="btn btn-sm btn-info mr-2">
                    <i class="fas fa-users-cog"></i> Usuários
                </a>
            <?php endif; ?>

            <a href="<?= site_url('auth/logout'); ?>" class="btn btn-sm btn-danger mr-2">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>

            <div class="user-greeting d-none d-md-block">
                Olá, <strong><?= explode(' ', $this->session->userdata('nome_usuario'))[0]; ?></strong>
            </div>
        </div>
    </div>
</div>

<div class="main-content">