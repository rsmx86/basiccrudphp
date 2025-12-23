<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        <?php if($this->session->flashdata('sucesso')): ?>
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle mr-2"></i> <?php echo $this->session->flashdata('sucesso'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('erro')): ?>
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $this->session->flashdata('erro'); ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="font-weight-bold text-dark">
                    <i class="fas fa-user-edit text-primary mr-2"></i> Meus Dados
                </h4>
                <p class="text-muted small">Mantenha suas informações de contato atualizadas.</p>
            </div>
            
            <div class="card-body">
                <form action="<?php echo site_url('usuario/atualizar_perfil'); ?>" method="POST">
                    
                    <div class="form-group">
                        <label class="small font-weight-bold text-secondary">NOME COMPLETO</label>
                        <input type="text" name="nome" class="form-control form-control-lg" 
                               value="<?php echo $usuario['nome']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="small font-weight-bold text-secondary">E-MAIL / LOGIN</label>
                        <input type="email" class="form-control form-control-lg bg-light" 
                               value="<?php echo $usuario['email']; ?>" readonly>
                        <small class="form-text text-muted">O e-mail é usado para login e não pode ser alterado.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-secondary">NÍVEL DE ACESSO</label>
                                <div class="form-control bg-light border-0">
                                    <span class="badge badge-info"><?php echo strtoupper($usuario['nivel_acesso']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-secondary">ID DO USUÁRIO</label>
                                <input type="text" class="form-control bg-light border-0" 
                                       value="#<?php echo $usuario['id']; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?php echo site_url('cadastro'); ?>" class="btn btn-light btn-custom text-muted">
                            <i class="fas fa-chevron-left mr-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary btn-custom px-5 shadow">
                            Atualizar Dados
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="<?php echo site_url('usuario/alterar_senha'); ?>" class="text-primary small font-weight-bold">
                <i class="fas fa-key mr-1"></i> Deseja alterar sua senha de acesso?
            </a>
        </div>
    </div>
</div>