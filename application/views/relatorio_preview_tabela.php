<div class="table-responsive">
    <table class="table table-sm table-hover mb-0">
        <thead class="bg-light small text-uppercase">
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Cidade</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($cadastros)): foreach($cadastros as $c): ?>
            <tr>
                <td><?= mb_strtoupper($c['nome']); ?></td>
                <td><?= $c['cpf']; ?></td>
                <td><?= mb_strtoupper($c['cidade']); ?></td>
                <td class="small"><?= date('d/m/Y', strtotime($c['data_criacao'])); ?></td>
            </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="4" class="text-center p-3">Nenhum dado encontrado para os filtros.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>