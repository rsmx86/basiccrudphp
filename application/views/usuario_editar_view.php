<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1">
            <i class="fas fa-user-shield text-primary mr-2"></i><?= $titulo; ?>
        </h4>
        <p class="text-muted small mb-0">Gerencie as permissões e dados de login do colaborador.</p>
    </div>
    <div class="col-auto">
        <a href="<?= site_url('usuario'); ?>" class="btn btn-white btn-sm border px-3 shadow-sm text-muted text-decoration-none">
            <i class="fas fa-chevron-left mr-1"></i> Voltar
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        
        <?php if ($this->session->flashdata('erro')): ?>
            <div class="alert alert-danger shadow-sm border-0">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= $this->session->flashdata('erro'); ?>
            </div>
        <?php endif; ?>

        <?php if (validation_errors()): ?>
            <div class="alert alert-warning shadow-sm border-0 small text-dark">
                <strong>Verifique os campos abaixo:</strong><br>
                <?= validation_errors(); ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <?php echo form_open('usuario/atualizar/' . $usuario['id']); ?>
                    
                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-muted text-uppercase">E-mail (Login)</label>
                        <input type="email" class="form-control bg-light border-0" value="<?= $usuario['email']; ?>" disabled>
                        <small class="text-info font-italic">O e-mail é o identificador e não pode ser alterado.</small>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" value="<?= set_value('nome', $usuario['nome']); ?>" required placeholder="Digite o nome completo">
                    </div>

                    <div class="form-group mt-4 p-3 border rounded bg-light">
                        <label class="small font-weight-bold text-primary text-uppercase d-block mb-2">
                            <i class="fas fa-layer-group mr-1"></i> Nível de Permissão
                        </label>
                        <select name="nivel_acesso" class="form-control custom-select border-primary font-weight-bold text-primary">
                            <option value="comum" <?= set_select('nivel_acesso', 'comum', ($usuario['nivel_acesso'] == 'comum')); ?>>USUÁRIO COMUM</option>
                            <option value="admin" <?= set_select('nivel_acesso', 'admin', ($usuario['nivel_acesso'] == 'admin')); ?>>ADMINISTRADOR</option>
                        </select>
                    </div>

                    <div class="form-group mt-4">
                        <label class="small font-weight-bold text-muted text-uppercase">Alterar Senha</label>
                        <input type="password" name="nova_senha" class="form-control" placeholder="Deixe em branco para manter a atual">
                        <small class="text-muted">Mínimo 6 caracteres se for alterar.</small>
                    </div>

                    <div class="d-flex justify-content-end mt-5 border-top pt-4">
                        <a href="<?= site_url('usuario'); ?>" class="btn btn-link text-muted mr-3 text-decoration-none">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2"></i> SALVAR ALTERAÇÕES
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>