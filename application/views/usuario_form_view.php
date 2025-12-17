<!DOCTYPE html>
<html>
<head>
    <title><?php echo $titulo; ?></title>
    </head>
<body>
    <h1><?php echo $titulo; ?></h1>
    <p><a href="<?php echo site_url('cadastro'); ?>">Voltar para a Listagem Principal</a></p>
    <hr>
    
    <?php if (validation_errors()): ?>
        <div style="color: red; border: 1px solid red; background-color: #ffe0e0; padding: 10px; margin-bottom: 15px;">
            <h4>⚠️ Atenção! Erros de Validação:</h4>
            <?php echo validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?php echo form_open('usuario/salvar'); ?>

        <label for="nome">Nome Completo do Novo Usuário:</label><br>
        <input type="text" name="nome" value="<?php echo set_value('nome'); ?>" size="50"><br>
        <small>Nome será usado na listagem de cadastros.</small><br><br>

        <label for="email">E-mail (Login e Chave Única):</label><br>
        <input type="email" name="email" value="<?php echo set_value('email'); ?>" size="50"><br>
        <small>Deve ser um e-mail único. Usado para o login.</small><br><br>
        
        <label for="senha">Senha:</label><br>
        <input type="password" name="senha" size="50"><br><br>
        
        <label for="confirma_senha">Confirmar Senha:</label><br>
        <input type="password" name="confirma_senha" size="50"><br><br>

        <p>O nível de acesso será automaticamente definido como **"comum"**.</p>
        
        <button type="submit">Criar Usuário Comum</button>
        
    <?php echo form_close(); ?>

</body>
</html>