<div class="container mt-4">
    <h3><i class="fas fa-filter"></i> Filtrar Relatório de Clientes</h3>
    <hr>
    
    <form action="<?php echo site_url('relatorios/clientes'); ?>" method="GET">
        <div class="row">
            <div class="col-md-3">
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
            
            <div class="col-md-3">
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
                <label class="font-weight-bold">De:</label>
                <input type="date" name="data_inicio" class="form-control" value="<?= $this->input->get('data_inicio'); ?>">
            </div>

            <div class="col-md-2">
                <label class="font-weight-bold">Até:</label>
                <input type="date" name="data_fim" class="form-control" value="<?= $this->input->get('data_fim'); ?>">
            </div>
            
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </div>

        <div class="mt-2 mb-4">
            <a href="<?= site_url('relatorios/clientes'); ?>" class="text-muted small">
                <i class="fas fa-times-circle"></i> Limpar Filtros
            </a>
        </div>
    </form>

    <div class="mt-5 pt-3 border-top">
        <?php 
            // Lógica para verificar se filtros foram aplicados
            $exibir_opcoes = ($this->input->get('cidade') || $this->input->get('filtro_usuario') || $this->input->get('data_inicio') || $this->input->get('data_fim')); 
        ?>

        <?php if ($exibir_opcoes): ?>
            <div class="animate__animated animate__fadeInUp">
                
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Filtros aplicados!</h5>
                        <p class="small text-muted">Visualize uma amostra dos dados antes de exportar o arquivo completo:</p>
                        <button type="button" id="btnPreview" class="btn btn-info px-4 shadow-sm">
                            <i class="fas fa-eye mr-2"></i> Visualizar Prévia dos Dados
                        </button>
                    </div>
                </div>

                <div id="areaPreview" style="display: none;" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h6 class="mb-0 font-weight-bold text-primary">
                                <i class="fas fa-table mr-2"></i>Registros encontrados (Prévia do Relatório)
                            </h6>
                            <button type="button" class="close" id="btnFecharPreview">&times;</button>
                        </div>
                        <div class="card-body p-0" id="conteudoPreview">
                            </div>
                        <div class="card-footer bg-light text-center small text-muted">
                            Esta é uma prévia limitada. Utilize os botões de exportação abaixo para obter o arquivo completo.
                        </div>
                    </div>
                </div>

                <div class="alert alert-info shadow-sm mb-3">
                    <i class="fas fa-check-circle"></i> Selecione o formato para exportação abaixo:
                </div>
                
                <div class="d-flex pb-5">
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
                    Selecione os filtros acima e clique em <strong>Filtrar</strong> para liberar a prévia e as opções de exportação.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnPreview = document.getElementById('btnPreview');
    const btnFechar = document.getElementById('btnFecharPreview');
    const area = document.getElementById('areaPreview');
    const conteudo = document.getElementById('conteudoPreview');

    if (btnPreview) {
        btnPreview.addEventListener('click', function() {
            area.style.display = 'block';
            conteudo.innerHTML = '<div class="p-5 text-center"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><br><span class="mt-2 d-block">Buscando dados...</span></div>';

            fetch('<?= site_url("relatorios/preview?" . $_SERVER["QUERY_STRING"]); ?>')
                .then(response => response.text())
                .then(html => {
                    conteudo.innerHTML = html;
                    area.scrollIntoView({ behavior: 'smooth' });
                })
                .catch(err => {
                    conteudo.innerHTML = '<div class="alert alert-danger m-3">Erro ao carregar prévia. Tente exportar direto.</div>';
                });
        });
    }

    if (btnFechar) {
        btnFechar.addEventListener('click', function() {
            area.style.display = 'none';
        });
    }
});
</script>