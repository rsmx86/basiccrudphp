<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1"><?= $titulo; ?></h4>
        <p class="text-muted small mb-0">Preencha os dados do cliente. Campos com <span class="text-danger">*</span> são obrigatórios.</p>
    </div>
    <div class="col-auto">
        <a href="<?= site_url('cadastro'); ?>" class="btn btn-white btn-sm border px-3 shadow-sm text-muted">
            <i class="fas fa-chevron-left mr-1"></i> Voltar para a listagem
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php echo form_open($form_action); ?>
            
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 25px; height: 25px; font-size: 12px;">1</div>
                <h5 class="text-dark font-weight-bold mb-0">Identificação Pessoal</h5>
            </div>
            <hr class="mt-0 mb-4">
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label class="small font-weight-bold text-muted text-uppercase">Nome Completo <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-control <?= form_error('nome') ? 'is-invalid' : ''; ?>" 
                           value="<?= set_value('nome', $cadastro['nome'] ?? ''); ?>" placeholder="Digite o nome do cliente">
                    <div class="invalid-feedback"><?= strip_tags(form_error('nome')); ?></div>
                </div>

                <div class="form-group col-md-4">
                    <label class="small font-weight-bold text-muted text-uppercase">CPF <span class="text-danger">*</span></label>
                    <input type="text" name="cpf" id="cpf" class="form-control <?= form_error('cpf') ? 'is-invalid' : ''; ?>" 
                           value="<?= set_value('cpf', $cadastro['cpf'] ?? ''); ?>" placeholder="000.000.000-00">
                    <div class="invalid-feedback"><?= strip_tags(form_error('cpf')); ?></div>
                </div>
            </div>

            <div class="d-flex align-items-center mt-4 mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 25px; height: 25px; font-size: 12px;">2</div>
                <h5 class="text-dark font-weight-bold mb-0">Endereço e Localização</h5>
            </div>
            <hr class="mt-0 mb-4">

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label class="small font-weight-bold text-primary text-uppercase">
                        <i class="fas fa-search-location mr-1"></i> CEP (Auto-busca)
                    </label>
                    <input type="text" name="cep" id="cep" class="form-control border-primary <?= form_error('cep') ? 'is-invalid' : ''; ?>" 
                           value="<?= set_value('cep', $cadastro['cep'] ?? ''); ?>" placeholder="00000-000">
                    <div class="invalid-feedback"><?= strip_tags(form_error('cep')); ?></div>
                </div>

                <div class="form-group col-md-9">
                    <label class="small font-weight-bold text-muted text-uppercase">Logradouro / Endereço</label>
                    <input type="text" name="endereco" id="endereco" class="form-control <?= form_error('endereco') ? 'is-invalid' : ''; ?>" 
                           value="<?= set_value('endereco', $cadastro['endereco'] ?? ''); ?>" placeholder="Rua, Av, Praça...">
                    <div class="invalid-feedback"><?= strip_tags(form_error('endereco')); ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="small font-weight-bold text-muted text-uppercase">Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="form-control <?= form_error('bairro') ? 'is-invalid' : ''; ?>" 
                           value="<?= set_value('bairro', $cadastro['bairro'] ?? ''); ?>">
                    <div class="invalid-feedback"><?= strip_tags(form_error('bairro')); ?></div>
                </div>

                <div class="form-group col-md-6">
                    <label class="small font-weight-bold text-muted text-uppercase">Cidade</label>
                    <input type="text" name="cidade" id="cidade" class="form-control <?= form_error('cidade') ? 'is-invalid' : ''; ?>" 
                           value="<?= set_value('cidade', $cadastro['cidade'] ?? ''); ?>">
                    <div class="invalid-feedback"><?= strip_tags(form_error('cidade')); ?></div>
                </div>
            </div>
            
            <div class="mt-5 border-top pt-4 d-flex justify-content-between align-items-center">
                <p class="text-muted small mb-0">Operador: <strong><?= $this->session->userdata('nome_usuario'); ?></strong></p>
                <div>
                    <a href="<?= site_url('cadastro'); ?>" class="btn btn-link text-muted mr-3">Cancelar</a>
                    <button type="submit" class="btn btn-success px-5 font-weight-bold shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= isset($cadastro['id']) ? 'ATUALIZAR DADOS' : 'CONFIRMAR CADASTRO'; ?>
                    </button>
                </div>
            </div>

        <?php echo form_close(); ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function() {
    $('#cpf').mask('000.000.000-00');
    $('#cep').mask('00000-000');

    $('#cep').blur(function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)) {
                $("#endereco, #bairro, #cidade").val("...");
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        $("#endereco").val(dados.logradouro.toUpperCase());
                        $("#bairro").val(dados.bairro.toUpperCase());
                        $("#cidade").val(dados.localidade.toUpperCase());
                    } else {
                        alert("CEP não encontrado.");
                        $("#endereco, #bairro, #cidade").val("");
                    }
                });
            }
        }
    });
});
</script>