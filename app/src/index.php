<?php
// O nginx da DMZ envia o cabecalho X-Rota-Acesso (publica ou corporativa)
$rota = isset($_SERVER['HTTP_X_ROTA_ACESSO']) ? $_SERVER['HTTP_X_ROTA_ACESSO'] : '';

if ($rota == 'publica') {
    $mensagem = 'Acesso pela rota publica';
} elseif ($rota == 'corporativa') {
    $mensagem = 'Acesso pela rede corporativa';
} else {
    $mensagem = 'Rota desconhecida';
}

$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Rota de acesso</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f5; padding: 40px; }
    </style>
</head>
<body>
    <h1><?php echo $mensagem; ?></h1>
    <a href="/adminer.php">Adminer</a>
</body>
</html>
