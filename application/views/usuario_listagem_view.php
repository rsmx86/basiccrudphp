<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1">Usuários do Sistema</h4>
        <p class="text-muted small mb-0">Gerencie acessos e níveis de permissão dos colaboradores.</p>
    </div>
    <div class="col-auto">
    <a href="<?= site_url('usuario/criar'); ?>" class="btn btn-success shadow-sm px-4">
    <i class="fas fa-user-plus mr-2"></i> Novo Usuário
</a>
</div>
</div>

<?php if ($this->session->flashdata('sucesso')): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4 animate__animated animate__fadeIn">
        <i class="fas fa-check-circle mr-2"></i> <?= $this->session->flashdata('sucesso'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('erro')): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4 animate__animated animate__fadeIn">
        <i class="fas fa-exclamation-circle mr-2"></i> <?= $this->session->flashdata('erro'); ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-0" width="60">ID</th>
                    <th class="border-0">Nome</th>
                    <th class="border-0">E-mail (Login)</th>
                    <th class="border-0 text-center">Nível</th>
                    <th class="border-0">Criado Por</th>
                    <th class="border-0 text-center" width="200">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): foreach ($usuarios as $user): ?>
                <tr>
                    <td class="align-middle text-muted">#<?= $user['id']; ?></td>
                    <td class="align-middle font-weight-bold text-dark"><?= $user['nome']; ?></td>
                    <td class="align-middle"><?= $user['email']; ?></td>
                    <td class="align-middle text-center">
                        <?php 
                            // Lógica robusta para o badge
                            $nivelRaw = isset($user['nivel_acesso']) ? trim(strtolower($user['nivel_acesso'])) : '';
                            $isAdmin = ($nivelRaw === 'admin');
                            
                            $classeBadge = $isAdmin ? 'badge-admin' : 'badge-comum';
                            $labelBadge  = $isAdmin ? 'ADMIN' : 'COMUM';
                        ?>
                        <span class="badge <?= $classeBadge; ?> py-2 px-3">
                            <?= $labelBadge; ?>
                        </span>
                    </td>
                    <td class="align-middle">
                        <small class="text-muted">
                            <i class="fas fa-user-tag mr-1"></i>
                            <?= $user['nome_criador'] ?? 'Sistema'; ?>
                        </small>
                    </td>
                    <td class="align-middle text-center">
                        <div class="btn-group shadow-sm">
                            <a href="<?= site_url('usuario/editar/'.$user['id']); ?>" class="btn btn-sm btn-white text-primary border" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-white text-danger border" onclick="confirmarExclusao(<?= $user['id']; ?>)" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-users-slash fa-2x mb-3 d-block"></i>
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalExcluirUsuario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title font-weight-bold">Confirmar Exclusão</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formExcluir" method="POST">
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <p class="text-dark">Atenção: Esta ação é permanente.</p>
                    <div class="alert alert-light border small text-left">
                        Os registros vinculados a este usuário serão movidos para o seu perfil administrativo.
                    </div>
                    <div class="form-group text-left">
                        <label class="font-weight-bold small">CONFIRME COM SUA SENHA MASTER:</label>
                        <input type="password" name="senha_master" class="form-control form-control-lg border-danger" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4 shadow-sm">Confirmar e Deletar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id) {
    $('#formExcluir').attr('action', "<?= site_url('usuario/deletar/'); ?>" + id);
    $('#modalExcluirUsuario').modal('show');
}
</script>