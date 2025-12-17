<?php
$senha_texto_simples = '12345678'; // <-- Sua senha
echo password_hash($senha_texto_simples, PASSWORD_DEFAULT);
?>