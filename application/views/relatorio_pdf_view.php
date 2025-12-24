<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Cadastros</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1a1d21; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; text-align: left; text-transform: uppercase; font-size: 10px; }
        td { border: 1px solid #dee2e6; padding: 8px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #777; }
        .filtros-aplicados { font-size: 10px; margin-bottom: 10px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
    <h2>SISTEMA DE GESTÃO - CADASTROS</h2>
    <p>Relatório Consolidado</p>
</div>

    <div class="filtros-aplicados">
        Emitido em: <?= date('d/m/Y H:i'); ?><br>
        Operador: <?= $this->session->userdata('nome_usuario'); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Cidade</th>
                <th>Bairro</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($cadastros)): foreach($cadastros as $c): ?>
                <tr>
                    <td>#<?= $c['id']; ?></td>
                    <td><?= mb_strtoupper($c['nome']); ?></td>
                    <td><?= $c['cpf']; ?></td>
                    <td><?= mb_strtoupper($c['cidade']); ?></td>
                    <td><?= $c['bairro']; ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align:center;">Nenhum registro encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Página 1 de 1
    </div>
</body>
</html>