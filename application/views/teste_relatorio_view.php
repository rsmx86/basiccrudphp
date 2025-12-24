<!DOCTYPE html>
<html>
<head>
    <title>Teste de Dados do Relatório</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="p-4">
    <h4>Dados Filtrados: <?php echo count($resultados); ?> registros encontrados.</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Cadastrado Por</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($resultados as $row): ?>
            <tr>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['cidade']; ?></td>
                <td><?php echo $row['cadastrado_por']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button onclick="window.close()" class="btn btn-secondary">Fechar e Voltar</button>
</body>
</html>