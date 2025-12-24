<div class="container mt-4">
    <h3><i class="fas fa-filter"></i> Filtrar Relatório de Clientes</h3>
    <hr>
    <form action="<?php echo site_url('relatorios/gerar_pdf'); ?>" method="GET" target="_blank">
        <div class="row">
            <div class="col-md-5">
                <label>Cidade:</label>
                <select name="cidade" class="form-control">
                    <option value="">Todas as Cidades</option>
                    <?php foreach($cidades as $c): ?>
                        <option value="<?php echo $c['cidade']; ?>"><?php echo $c['cidade']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label>Cadastrado Por:</label>
                <select name="usuario_id" class="form-control">
                    <option value="">Todos os Usuários</option>
                    <?php foreach($usuarios as $u): ?>
                        <option value="<?php echo $u['id']; ?>"><?php echo $u['nome']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fas fa-file-pdf"></i> Gerar PDF
                </button>
            </div>
        </div>
    </form>
</div>