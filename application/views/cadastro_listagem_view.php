<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1">Gestão de Cadastros</h4>
        <p class="text-muted small mb-0">Visualize e gerencie os registros.</p>
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

<div id="caixaFiltro" class="collapse mb-4 <?= ($this->input->get('filtro_usuario') || $this->input->get('cidade') || $this->input->get('filtro_data')) ? 'show' : ''; ?>">
    <div class="card card-body border-0 shadow-sm bg-light">
        <form method="GET" action="<?= site_url('cadastro/index'); ?>" class="form-row">
            <?php if ($this->session->userdata('nivel_acesso') == 'admin'): ?>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted">USUÁRIO:</label>
                    <select name="filtro_usuario" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <?php foreach($lista_usuarios as $u): ?>
                            <option value="<?= $u['id']; ?>" <?= ($this->input->get('filtro_usuario') == $u['id']) ? 'selected' : ''; ?>><?= $u['nome']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted">CIDADE:</label>
                <select name="cidade" class="form-control form-control-sm">
                    <option value="">Todas</option>
                    <?php foreach($lista_cidades as $c): ?>
                        <option value="<?= $c['cidade']; ?>" <?= ($this->input->get('cidade') == $c['cidade']) ? 'selected' : ''; ?>><?= mb_strtoupper($c['cidade']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted">DATA:</label>
                <input type="date" name="filtro_data" class="form-control form-control-sm" value="<?= $this->input->get('filtro_data'); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end mb-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mr-1">APLICAR</button>
                <a href="<?= site_url('cadastro'); ?>" class="btn btn-light btn-sm border">LIMPAR</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <form method="GET" action="<?= site_url('cadastro/index'); ?>" id="formLimit" class="form-inline">
            <input type="hidden" name="filtro_usuario" value="<?= $this->input->get('filtro_usuario'); ?>">
            <input type="hidden" name="cidade" value="<?= $this->input->get('cidade'); ?>">
            <input type="hidden" name="filtro_data" value="<?= $this->input->get('filtro_data'); ?>">
            
            <label class="small text-muted mr-2">Exibir</label>
            <select name="limit" class="form-control form-control-sm mr-2" onchange="document.getElementById('formLimit').submit();">
                <?php foreach([10, 25, 50, 100] as $l): ?>
                    <option value="<?= $l; ?>" <?= ($itens_por_pagina == $l) ? 'selected' : ''; ?>><?= $l; ?></option>
                <?php endforeach; ?>
            </select>
            <span class="small text-muted">registros por página</span>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light text-muted text-uppercase small">
                <tr>
                    <th>ID</th>
                    <th>Nome / CPF</th>
                    <th>Bairro</th>
                    <th>Cidade</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cadastros)): foreach ($cadastros as $cadastro): ?>
                <tr>
                    <td class="small">#<?= $cadastro['id']; ?></td>
                    <td>
                        <span class="d-block font-weight-bold"><?= mb_strtoupper($cadastro['nome']); ?></span>
                        <small class="text-muted"><?= $cadastro['cpf']; ?></small>
                    </td>
                    <td><?= $cadastro['bairro']; ?></td>
                    <td class="small font-weight-bold"><?= mb_strtoupper($cadastro['cidade']); ?></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="<?= site_url('cadastro/editar/'.$cadastro['id']); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <?php if ($this->session->userdata('nivel_acesso') === 'admin'): ?>
                                <a href="<?= site_url('cadastro/deletar/'.$cadastro['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir?')"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center py-4">Nenhum registro encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white border-top py-3">
        <div class="row align-items-center">
            <div class="col-sm-6 text-muted small">
                Mostrando <?= count($cadastros); ?> de <?= $total_registros; ?> registros.
            </div>
            <div class="col-sm-6 text-right">
                <nav class="d-inline-block">
                    <?= $paginacao; ?>
                </nav>
            </div>
        </div>
    </div>
</div>