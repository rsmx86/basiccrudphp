<!DOCTYPE html>
<html>
<head>
    <title><?php echo $titulo; ?></title>
</head>
<body>
    <h1><?php echo $titulo; ?></h1>
    <p>Usuário Logado: **<?php echo $this->session->userdata('nome_usuario'); ?>** | Nível: **<?php echo $this->session->userdata('nivel_acesso'); ?>** - <a href="<?php echo site_url('auth/logout'); ?>">Sair</a></p>
    <hr>
    
    <?php 
    // Define a URL de destino do formulário (Action)
    if (isset($cadastro)):
        // Se a variável $cadastro existe, é EDIÇÃO = Envia para 'atualizar/ID'
        $form_action = site_url('cadastro/atualizar/' . $cadastro['id']);
    else:
        // Caso contrário, é CRIAÇÃO = Envia para 'store'
        $form_action = site_url('cadastro/store');
    endif;
    ?>

    <?php echo form_open($form_action); ?>
        
        <label for="nome">Nome:</label><br>
        <input type="text" name="nome" value="<?php echo set_value('nome', $cadastro['nome'] ?? ''); ?>"><br>
        <?php echo form_error('nome'); ?><br>

        <label for="endereco">Endereço:</label><br>
        <input type="text" name="endereco" value="<?php echo set_value('endereco', $cadastro['endereco'] ?? ''); ?>"><br>
        <?php echo form_error('endereco'); ?><br>

        <label for="bairro">Bairro:</label><br>
        <input type="text" name="bairro" value="<?php echo set_value('bairro', $cadastro['bairro'] ?? ''); ?>"><br>
        <?php echo form_error('bairro'); ?><br>
        
        <input type="submit" value="<?php echo isset($cadastro) ? 'Atualizar Cadastro' : 'Salvar Cadastro'; ?>">
    <?php echo form_close(); ?>
    
    <p><a href="<?php echo site_url('cadastro'); ?>">Voltar para a listagem</a></p>
</body>
</html>