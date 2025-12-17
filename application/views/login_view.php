<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Cadastros</title>
    <style>
        body {
            background-color: #f4f7f6;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 350px;
        }
        .login-card h2 {
            text-align: center;
            color: #333;
            margin-top: 0;
            margin-bottom: 25px;
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        input[type="submit"]:hover { background-color: #0056b3; }
        .error-msg { color: red; font-size: 13px; margin-top: 5px; display: block; }
        .alert-error {
            background-color: #fee;
            color: #c00;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #fcc;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Login de Acesso</h2>

    <?php if ($this->session->flashdata('erro_login')): ?>
        <div class="alert-error">
            <?php echo $this->session->flashdata('erro_login'); ?>
        </div>
    <?php endif; ?>

    <?php echo form_open('auth/login'); ?>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" name="email" value="<?php echo set_value('email'); ?>" placeholder="Seu e-mail">
            <span class="error-msg"><?php echo form_error('email'); ?></span>
        </div>

        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" placeholder="Sua senha">
            <span class="error-msg"><?php echo form_error('senha'); ?></span>
        </div>

        <input type="submit" value="Entrar">

    <?php echo form_close(); ?>
</div>

</body>
</html>