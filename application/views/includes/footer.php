</div> <footer class="text-center py-4 text-muted small">
    &copy; <?= date('Y'); ?> Sistema de Gestão - Todos os direitos reservados.
</footer>

<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div id="viewDadosPerfil">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-user-circle mr-2"></i> Meus Dados</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <form action="<?= site_url('usuario/atualizar_perfil'); ?>" method="POST">
                        <div class="form-group">
                            <label class="small font-weight-bold">NOME COMPLETO</label>
                            <input type="text" name="nome" class="form-control" value="<?= $this->session->userdata('nome_usuario'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">E-MAIL</label>
                            <input type="text" class="form-control bg-light" value="<?= $this->session->userdata('email'); ?>" readonly>
                        </div>
                        <button type="button" onclick="alternarTelaSenha(true)" class="btn btn-sm btn-outline-warning mb-3">
                            <i class="fas fa-key"></i> Alterar Senha
                        </button>
                        <button type="submit" class="btn btn-primary btn-block shadow font-weight-bold">SALVAR ALTERAÇÕES</button>
                    </form>
                </div>
            </div>

            <div id="viewTrocarSenha" style="display: none;">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-lock mr-2"></i> Alterar Senha</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <form action="<?= site_url('usuario/processar_troca_senha'); ?>" method="POST">
                        <div class="form-group">
                            <label class="small font-weight-bold">SENHA ATUAL</label>
                            <input type="password" name="senha_atual" class="form-control border-warning" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">NOVA SENHA</label>
                            <input type="password" name="nova_senha" class="form-control border-warning" minlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block font-weight-bold">CONFIRMAR E REFAZER LOGIN</button>
                        <button type="button" onclick="alternarTelaSenha(false)" class="btn btn-link btn-block text-muted small">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function(){
    // Máscaras para CPF e CEP
    $('.cpf-mask').mask('000.000.000-00');
    $('.cep-mask').mask('00000-000');
    
    // Efeito de "Salvando" no botão do perfil
    $('#viewDadosPerfil form').on('submit', function() {
        $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Salvando...').prop('disabled', true);
    });
});

// Função para alternar entre dados e troca de senha no modal
function alternarTelaSenha(showSenha) {
    if(showSenha) {
        $('#viewDadosPerfil').fadeOut(200, function(){ $('#viewTrocarSenha').fadeIn(200); });
    } else {
        $('#viewTrocarSenha').fadeOut(200, function(){ $('#viewDadosPerfil').fadeIn(200); });
    }
}
</script>
</body>
</html>