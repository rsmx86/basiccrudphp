<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-key mr-2 text-warning"></i> Alterar Minha Senha</h5>
            </div>
            <div class="card-body p-4">
                
                <?php if($this->session->flashdata('erro')): ?>
                    <div class="alert alert-danger shadow-sm border-0"><?php echo $this->session->flashdata('erro'); ?></div>
                <?php endif; ?>

                <?php if(validation_errors()): ?>
                    <div class="alert alert-danger shadow-sm border-0"><?php echo validation_errors(); ?></div>
                <?php endif; ?>

                <form action="<?php echo site_url('usuario/alterar_minha_senha'); ?>" method="POST">
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">SENHA ATUAL</label>
                        <input type="password" name="senha_atual" class="form-control form-control-lg" required>
                    </div>
                    
                    <hr>

                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">NOVA SENHA</label>
                        <input type="password" name="nova_senha" class="form-control form-control-lg" placeholder="Mínimo 6 caracteres" required>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">CONFIRMAR NOVA SENHA</label>
                        <input type="password" name="confirma_senha" class="form-control form-control-lg" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning btn-block btn-lg font-weight-bold shadow">
                            ATUALIZAR SENHA AGORA
                        </button>
                        <a href="<?php echo site_url('cadastro'); ?>" class="btn btn-link btn-block text-muted">Voltar ao Início</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>