<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Cadastros</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #000; font-size: 18px; }
        .info-box { background: #f8f9fa; padding: 10px; border: 1px solid #ddd; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #eee; border: 1px solid #999; padding: 8px; text-align: left; font-size: 9px; }
        td { border: 1px solid #ccc; padding: 6px; }
        .footer { position: fixed; bottom: -10px; width: 100%; text-align: right; font-size: 9px; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SISTEMA DE GESTÃO - RELATÓRIO DE CADASTROS</h2>
        <p style="margin: 5px 0;">Relatório Consolidado de Clientes</p>
    </div>

    <div class="info-box">
    <table style="border: none; margin-bottom: 0; width: 100%;">
        <tr style="border: none;">
            <td style="border: none; width: 70%; vertical-align: top;">
                <strong>Emitido em:</strong> <?= date('d/m/Y H:i'); ?><br>
                <strong>Operador(a):</strong> <?= $this->session->userdata('nome_usuario'); ?><br>
                
                <div style="margin-top: 5px; padding-top: 5px; border-top: 1px dashed #ccc;">
                    <strong>Filtros Aplicados:</strong><br>
                    <span style="color: #555;">
                        Cidades: <?= $this->input->get('cidade') ? mb_strtoupper($this->input->get('cidade')) : 'TODAS AS CIDADES'; ?> | 
                        Usuários: 
                        <?php 
                            // Lógica para pegar o nome do usuário filtrado
                            $user_id = $this->input->get('filtro_usuario');
                            $nome_user = 'TODOS OS USUÁRIOS';
                            if(!empty($user_id) && !empty($usuarios)){
                                foreach($usuarios as $u){
                                    if($u['id'] == $user_id) $nome_user = $u['nome'];
                                }
                            }
                            echo mb_strtoupper($nome_user);
                        ?> 
                        <br>
                        Período: 
                        <?= $this->input->get('data_inicio') ? date('d/m/Y', strtotime($this->input->get('data_inicio'))) : 'INÍCIO'; ?> até 
                        <?= $this->input->get('data_fim') ? date('d/m/Y', strtotime($this->input->get('data_fim'))) : date('d/m/Y'); ?>
                    </span>
                </div>
            </td>
            <td style="border: none; width: 30%; text-align: right; vertical-align: middle;">
                <div style="background: #eee; padding: 10px; border: 1px solid #999;">
                    <small style="display: block; text-transform: uppercase;">Total de Registros</small>
                    <span style="font-size: 22px; font-weight: bold;"><?= count($cadastros); ?></span>
                </div>
            </td>
        </tr>
    </table>
</div>

    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="35%">NOME</th>
                <th width="15%">CPF</th>
                <th width="20%">CIDADE</th>
                <th width="25%">BAIRRO</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($cadastros)): foreach($cadastros as $c): ?>
                <tr>
                    <td>#<?= $c['id']; ?></td>
                    <td><strong><?= mb_strtoupper($c['nome']); ?></strong></td>
                    <td><?= $c['cpf']; ?></td>
                    <td><?= mb_strtoupper($c['cidade']); ?></td>
                    <td><?= $c['bairro']; ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align:center; padding: 20px;">Nenhum registro encontrado para os filtros selecionados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Gerado automaticamente pelo Sistema de Gestão - Página 1
    </div>
</body>
</html>