<?php 
    // Configurações de Ordenação Dinâmica
    $current_sort_by = $this->input->get('sort_by') ?? 'id';
    $current_sort_order = $this->input->get('sort_order') ?? 'desc';
    $icon = ($current_sort_order == 'asc') ? 'fa-sort-up' : 'fa-sort-down';

    if (!function_exists('sort_url')) {
        function sort_url($column, $current_sort_by, $current_sort_order) {
            $ci =& get_instance();
            $params = $ci->input->get();
            $params['sort_by'] = $column;
            $params['sort_order'] = ($current_sort_by == $column && $current_sort_order == 'asc') ? 'desc' : 'asc';
            return site_url('cadastro/index') . '?' . http_build_query($params);
        }
    }
?>

<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="font-weight-bold text-dark mb-1">Gestão de Cadastros</h4>
        <p class="text-muted small mb-0">Visualize e filtre registros de toda a equipe.</p>
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
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted text-uppercase">Criado por:</label>
                <select name="filtro_usuario" class="form-control form-control-sm">
                    <option value="">Todos os usuários</option>
                    <?php if(!empty($lista_usuarios)): foreach($lista_usuarios as $u): ?>
                        <option value="<?= $u['id']; ?>" <?= ($this->input->get('filtro_usuario') == $u['id']) ? 'selected' : ''; ?>>
                            <?= ($u['id'] == $this->session->userdata('id_usuario')) ? '➤ MEUS REGISTROS' : $u['nome']; ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted text-uppercase">Cidade:</label>
                <select name="cidade" class="form-control form-control-sm">
                    <option value="">Todas as cidades</option>
                    <?php if(!empty($lista_cidades)): foreach($lista_cidades as $c): ?>
                        <option value="<?= $c['cidade']; ?>" <?= ($this->input->get('cidade') == $c['cidade']) ? 'selected' : ''; ?>><?= mb_strtoupper($c['cidade']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-muted text-uppercase">Data:</label>
                <input type="date" name="filtro_data" class="form-control form-control-sm" value="<?= $this->input->get('filtro_data'); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end mb-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mr-1">APLICAR</button>
                <a href="<?= site_url('cadastro'); ?>" class="btn btn-light btn-sm border text-muted">LIMPAR</a>
            </div>
            <input type="hidden" name="sort_by" value="<?= $current_sort_by; ?>">
            <input type="hidden" name="sort_order" value="<?= $current_sort_order; ?>">
        </form>
    </div>
</div>

<div class="row mb-3 align-items-center">
    <div class="col-md-7 d-flex align-items-center">
        <div class="bg-white border rounded px-3 py-2 shadow-sm d-flex align-items-center">
            <span class="text-muted small mr-3">Mostrar</span>
            <form method="GET" action="<?= site_url('cadastro/index'); ?>" class="d-inline-flex">
                <select name="limit" onchange="this.form.submit()" class="form-control form-control-sm bg-light border-0" style="width: 150px; font-weight: bold; cursor: pointer;">
                    <?php foreach([10, 25, 50, 75, 100] as $l): ?>
                        <option value="<?= $l; ?>" <?= ($itens_por_pagina == $l) ? 'selected' : ''; ?>><?= $l; ?> registros</option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="filtro_usuario" value="<?= $this->input->get('filtro_usuario'); ?>">
                <input type="hidden" name="cidade" value="<?= $this->input->get('cidade'); ?>">
                <input type="hidden" name="filtro_data" value="<?= $this->input->get('filtro_data'); ?>">
                <input type="hidden" name="sort_by" value="<?= $current_sort_by; ?>">
                <input type="hidden" name="sort_order" value="<?= $current_sort_order; ?>">
            </form>
            <div class="border-left ml-3 pl-3">
                <span class="text-muted small">Total: <strong class="text-dark"><?= $total_registros; ?></strong></span>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="float-right pagination-sm">
            <?= $paginacao; ?>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light text-muted text-uppercase small">
                <tr>
                    <th style="width: 100px;">
                        <a href="<?= sort_url('id', $current_sort_by, $current_sort_order); ?>" class="text-muted text-decoration-none d-flex justify-content-between align-items-center">
                            ID <i class="fas <?= $current_sort_by == 'id' ? $icon : 'fa-sort'; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?= sort_url('nome', $current_sort_by, $current_sort_order); ?>" class="text-muted text-decoration-none d-flex justify-content-between align-items-center">
                            CLIENTE <i class="fas <?= $current_sort_by == 'nome' ? $icon : 'fa-sort'; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?= sort_url('cidade', $current_sort_by, $current_sort_order); ?>" class="text-muted text-decoration-none d-flex justify-content-between align-items-center">
                            CIDADE <i class="fas <?= $current_sort_by == 'cidade' ? $icon : 'fa-sort'; ?>"></i>
                        </a>
                    </th>
                    <th>CADASTRADOR</th>
                    <th class="text-center">AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $id_logado = $this->session->userdata('id_usuario');
                $nivel = $this->session->userdata('nivel_acesso');
                
                if (!empty($cadastros)): 
                    foreach ($cadastros as $cadastro): 
                        $e_meu = ($cadastro['usuario_id'] == $id_logado);
                        $pode_editar = ($e_meu || $nivel === 'admin');
                ?>
                <tr>
                    <td class="small text-muted font-weight-bold">#<?= $cadastro['id']; ?></td>
                    <td>
                        <span class="d-block font-weight-bold text-dark"><?= mb_strtoupper($cadastro['nome']); ?></span>
                        <small class="text-muted"><?= $cadastro['cpf']; ?></small>
                        <?php if($e_meu): ?>
                            <span class="badge badge-light border text-primary ml-1" style="font-size: 9px;">MEU</span>
                        <?php endif; ?>
                    </td>
                    <td class="small font-weight-bold"><?= mb_strtoupper($cadastro['cidade']); ?></td>
                    <td>
                        <span class="small <?= $e_meu ? 'font-weight-bold text-primary' : 'text-muted'; ?>">
                            <i class="fas fa-user-circle mr-1"></i> <?= $cadastro['nome_cadastrador'] ?? 'Sistema'; ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <?php if ($pode_editar): ?>
                                <a href="<?= site_url('cadastro/editar/'.$cadastro['id']); ?>" class="btn btn-sm btn-white border text-primary shadow-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light text-muted border" disabled><i class="fas fa-lock"></i></button>
                            <?php endif; ?>

                            <?php if ($nivel === 'admin'): ?>
                                <a href="<?= site_url('cadastro/deletar/'.$cadastro['id']); ?>" class="btn btn-sm btn-white border text-danger shadow-sm" onclick="return confirm('Excluir permanentemente?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center py-5 text-muted small">Nenhum registro encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    <p class="text-muted small">Mostrando <strong><?= count($cadastros); ?></strong> resultados nesta página.</p>
</div>