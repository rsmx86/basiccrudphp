<!DOCTYPE html>
<html>
<head>
    <title><?php echo $titulo; ?></title>
</head>
<body>
    <h1><?php echo $titulo; ?></h1>
    <p><a href="<?php echo site_url('usuario'); ?>">Voltar para a Listagem de Usuários</a></p>
    <hr>

    <?php if ($this->session->flashdata('erro')): ?>
        <p style="color: red;"><?php echo $this->session->flashdata('erro'); ?></p>
    <?php endif; ?>

    <?php if (validation_errors()): ?>
        <div style="color: red; border: 1px solid red; background-color: #ffe0e0; padding: 10px; margin-bottom: 15px;">
            <h4>Erros de Validação:</h4>
            <?php echo validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?php echo form_open('usuario/atualizar/' . $usuario['id']); ?>

        <label for="email">E-mail (Login):</label><br>
        <input type="email" name="email" value="<?php echo $usuario['email']; ?>" size="50" disabled><br>
        <small>O e-mail não pode ser alterado por aqui, pois é o login principal.</small><br><br>

        <label for="nome">Nome Completo:</label><br>
        <input type="text" name="nome" value="<?php echo set_value('nome', $usuario['nome']); ?>" size="50"><br><br>
        
        <label for="nivel_acesso">Nível de Acesso:</label><br>
        <select name="nivel_acesso">
            <option value="comum" <?php echo set_select('nivel_acesso', 'comum', ($usuario['nivel_acesso'] == 'comum')); ?>>COMUM</option>
            <option value="admin" <?php echo set_select('nivel_acesso', 'admin', ($usuario['nivel_acesso'] == 'admin')); ?>>ADMINISTRADOR</option>
        </select><br><br>
        
        <label for="nova_senha">Nova Senha (Deixe em branco para não alterar):</label><br>
        <input type="password" name="nova_senha" size="50"><br>
        <small>Mínimo 6 caracteres.</small><br><br>

        <button type="submit">Salvar Alterações</button>
        
    <?php echo form_close(); ?>

</body>
</html>