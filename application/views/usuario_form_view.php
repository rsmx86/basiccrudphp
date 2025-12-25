<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1">Novo Usuário do Sistema</h4>
        <p class="text-muted small mb-0">A senha padrão de acesso será: <strong>123456</strong></p>
    </div>
    <div class="col-auto">
        <a href="<?= site_url('usuario'); ?>" class="btn btn-light border px-3">
            <i class="fas fa-chevron-left mr-2"></i> Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?= site_url('usuario/salvar'); ?>" method="POST">
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label class="small font-weight-bold text-muted text-uppercase">Nome Completo</label>
                    <input type="text" name="nome" class="form-control" required placeholder="Digite o nome completo" value="<?= set_value('nome'); ?>">
                    <?= form_error('nome', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group col-md-4">
                    <label class="small font-weight-bold text-muted text-uppercase">Nível de Acesso</label>
                    <select name="nivel_acesso" class="form-control" required>
                        <option value="user">Usuário Comum</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label class="small font-weight-bold text-muted text-uppercase">E-mail (Login)</label>
                    <input type="email" name="email" class="form-control" required placeholder="exemplo@email.com" value="<?= set_value('email'); ?>">
                    <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>

            <input type="hidden" name="senha" value="123456">

            <hr class="my-4">

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                    <i class="fas fa-check mr-2"></i> FINALIZAR CADASTRO
                </button>
            </div>
        </form>
    </div>
</div>