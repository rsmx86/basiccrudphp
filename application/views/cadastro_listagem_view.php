<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1">Gestão de Cadastros</h4>
        <p class="text-muted small mb-0">Visualize e gerencie todos os registros do sistema.</p>
    </div>
    <div class="col-auto">
        <div class="btn-group shadow-sm">
            <a href="<?= site_url('cadastro/criar'); ?>" class="btn btn-success px-4">
                <i class="fas fa-plus mr-2"></i> Novo Cadastro
            </a>
            <button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#caixaFiltro">
                <i class="fas fa-filter mr-1"></i> Filtros
            </button>
        </div>
    </div>
</div>

<?php if ($this->session->userdata('nivel_acesso') == 'admin'): ?>
<div id="caixaFiltro" class="collapse mb-4 <?= ($this->input->get('filtro_usuario') || $this->input->get('cidade') || $this->input->get('filtro_data')) ? 'show' : ''; ?>">
    <div class="card card-body border-0 shadow-sm bg-light">
        <form method="GET" action="<?= site_url('cadastro/index'); ?>" class="form-row">
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted text-uppercase">Usuário:</label>
                <select name="filtro_usuario" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <?php foreach($lista_usuarios as $u): ?>
                        <option value="<?= $u['id']; ?>" <?= ($this->input->get('filtro_usuario') == $u['id']) ? 'selected' : ''; ?>>
                            <?= $u['nome']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted text-uppercase">Cidade:</label>
                <input type="text" name="cidade" class="form-control form-control-sm" placeholder="Ex: João Pessoa" value="<?= $this->input->get('cidade'); ?>">
            </div>

            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted text-uppercase">Data:</label>
                <input type="date" name="filtro_data" class="form-control form-control-sm" value="<?= $this->input->get('filtro_data'); ?>">
            </div>

            <div class="col-md-3 d-flex align-items-end mb-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mr-2 font-weight-bold">APLICAR</button>
                <a href="<?= site_url('cadastro'); ?>" class="btn btn-white btn-sm flex-grow-1 border font-weight-bold text-muted">LIMPAR</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light text-muted">
                <tr>
                    <th class="border-0" width="70">ID</th>
                    <th class="border-0">Nome / CPF</th>
                    <th class="border-0">Bairro</th>
                    <th class="border-0">Cidade</th> 
                    <th class="border-0 text-center" width="180">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cadastros)): foreach ($cadastros as $cadastro): ?>
                <tr>
                    <td class="align-middle text-muted small">#<?= $cadastro['id']; ?></td>
                    
                    <td class="align-middle">
                        <span class="d-block font-weight-bold text-dark"><?= mb_strtoupper($cadastro['nome']); ?></span>
                        <small class="text-muted">CPF: <?= $cadastro['cpf']; ?></small>
                    </td>
                    
                    <td class="align-middle text-secondary">
                        <?= $cadastro['bairro']; ?>
                    </td>
                    
                    <td class="align-middle">
                        <span class="text-dark font-weight-bold small">
                            <i class="fas fa-map-marker-alt text-danger mr-1"></i> 
                            <?= $cadastro['cidade'] ? mb_strtoupper($cadastro['cidade']) : '---'; ?>
                        </span>
                    </td>

                    <td class="align-middle text-center">
                        <div class="btn-group shadow-sm">
                            <a href="<?= site_url('cadastro/editar/'.$cadastro['id']); ?>" class="btn btn-sm btn-white text-primary border" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <?php if ($this->session->userdata('nivel_acesso') === 'admin'): ?>
                                <a href="<?= site_url('cadastro/deletar/'.$cadastro['id']); ?>" 
                                   class="btn btn-sm btn-white text-danger border" 
                                   onclick="return confirm('Tem certeza que deseja apagar este registro?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-2x mb-3 d-block opacity-25"></i>
                            Nenhum registro encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>