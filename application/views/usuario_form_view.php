<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1">
            <?= isset($user) ? 'Editar Registro' : 'Novo Cadastro'; ?>
        </h4>
        <p class="text-muted small mb-0">Preencha os campos abaixo com atenção.</p>
    </div>
    <div class="col-auto">
        <a href="<?= site_url('cadastro'); ?>" class="btn btn-light border px-3">
            <i class="fas fa-chevron-left mr-2"></i> Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?= isset($user) ? site_url('cadastro/atualizar/'.$user['id']) : site_url('cadastro/salvar'); ?>" method="POST">
            
            <h5 class="text-primary mb-4 border-bottom pb-2">Informações Pessoais</h5>
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label class="small font-weight-bold text-muted text-uppercase">Nome Completo</label>
                    <input type="text" name="nome" class="form-control" value="<?= $user['nome'] ?? ''; ?>" required placeholder="Digite o nome completo">
                </div>
                <div class="form-group col-md-4">
                    <label class="small font-weight-bold text-muted text-uppercase">CPF</label>
                    <input type="text" name="cpf" class="form-control cpf-mask" value="<?= $user['cpf'] ?? ''; ?>" required placeholder="000.000.000-00">
                </div>
            </div>

            <h5 class="text-primary mt-4 mb-4 border-bottom pb-2">Endereço e Localização</h5>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label class="small font-weight-bold text-muted text-uppercase">CEP</label>
                    <input type="text" name="cep" class="form-control cep-mask" value="<?= $user['cep'] ?? ''; ?>" placeholder="00000-000">
                </div>
                <div class="form-group col-md-6">
                    <label class="small font-weight-bold text-muted text-uppercase">Bairro</label>
                    <input type="text" name="bairro" class="form-control" value="<?= $user['bairro'] ?? ''; ?>" placeholder="Ex: Centro">
                </div>
                <div class="form-group col-md-3">
                    <label class="small font-weight-bold text-muted text-uppercase">Cidade</label>
                    <input type="text" name="cidade" class="form-control" value="<?= $user['cidade'] ?? ''; ?>" placeholder="Ex: João Pessoa">
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-light border mr-2 px-4">Limpar</button>
                <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                    <i class="fas fa-save mr-2"></i> SALVAR REGISTRO
                </button>
            </div>
        </form>
    </div>
</div>