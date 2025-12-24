<div class="container mt-4">
    <h3><i class="fas fa-filter"></i> Filtrar Relatório de Clientes</h3>
    <hr>
    
    <form action="<?php echo site_url('relatorios/clientes'); ?>" method="GET">
        <div class="row">
            <div class="col-md-5">
                <label class="font-weight-bold">Cidade:</label>
                <select name="cidade" class="form-control">
                    <option value="">Todas as Cidades</option>
                    <?php foreach($cidades as $c): ?>
                        <option value="<?php echo $c['cidade']; ?>" <?= ($this->input->get('cidade') == $c['cidade']) ? 'selected' : ''; ?>>
                            <?php echo mb_strtoupper($c['cidade']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-5">
                <label class="font-weight-bold">Cadastrado Por:</label>
                <select name="filtro_usuario" class="form-control">
                    <option value="">Todos os Usuários</option>
                    <?php foreach($usuarios as $u): ?>
                        <option value="<?php echo $u['id']; ?>" <?= ($this->input->get('filtro_usuario') == $u['id']) ? 'selected' : ''; ?>>
                            <?php echo $u['nome']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
			
			<div class="col-md-14 mt-4">
		<a href="<?= site_url('relatorios/clientes'); ?>" class="text-muted small shadow-none">
        <i class="fas fa-times-circle"></i> Limpar Filtros
		</a>
			</div>
			
        </div>
    </form>

    <div class="mt-5 pt-3 border-top">
        <hr>

<?php 
//botão de filtrar dos relatorios + exportacao
// Verifica se o usuário já clicou em filtrar (se existe algum parâmetro na URL)


$exibir_opcoes = ($this->input->get('cidade') !== null || $this->input->get('filtro_usuario') !== null); 
?>

<?php if ($exibir_opcoes): ?>
    <div class="mt-4 animate__animated animate__fadeInUp">
        <div class="alert alert-info shadow-sm">
            <i class="fas fa-check-circle"></i> <strong>Filtros aplicados!</strong> Selecione o formato para exportação abaixo:
        </div>
        
        <div class="d-flex pb-4">
            <a href="<?= site_url('relatorios/gerar_pdf?' . $_SERVER['QUERY_STRING']); ?>" target="_blank" class="btn btn-danger btn-lg mr-2 shadow">
                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
            </a>

            <a href="<?= site_url('relatorios/gerar_csv?' . $_SERVER['QUERY_STRING']); ?>" class="btn btn-success btn-lg shadow">
                <i class="fas fa-file-excel mr-1"></i> Exportar CSV (Excel)
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="mt-4 text-center p-5 border rounded bg-light">
        <p class="text-muted">
            <i class="fas fa-arrow-up d-block mb-2" style="font-size: 24px;"></i>
            Selecione os filtros acima e clique em <strong>Filtrar</strong> para gerar as opções de exportação.
        </p>
    </div>
<?php endif; ?>

    </div>
</div>