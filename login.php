<?php
session_start();
if(isset($_SESSION['logado']) && $_SESSION['logado'] === true){
    header("Location: index.php");
    exit;
}

$erro = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "configs/database.php";
    
    $loginOuEmail = $_POST['email']; 
    $senha = $_POST['senha'];

    $db = new Database();
    $con = $db->conectar();
    
    $stmt = $con->prepare("SELECT * FROM funcionario WHERE (email = :login OR login = :login) AND senha = :senha");
    $stmt->bindParam(":login", $loginOuEmail);
    $stmt->bindParam(":senha", $senha);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        $_SESSION['logado'] = true;
        $_SESSION['usuario_nome'] = $user->nome;
        $_SESSION['cargo'] = $user->cargo;
        $_SESSION['usuario_id'] = $user->ra;
        
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail/Login ou senha incorretos!";
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Loja Senac</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Roboto', Arial, sans-serif; background-color: #ebebeb; display: flex; flex-direction: column; min-height: 100vh; }
        .login-header { background-color: #fff159; height: 80px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,.1); }
        .login-header h1 { font-size: 26px; color: #333; font-weight: bold; text-decoration: none; }
        .login-main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-box { background: #fff; padding: 50px 40px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; max-width: 420px; }
        .login-box h2 { font-size: 24px; color: #333; margin-bottom: 30px; text-align: center; font-weight: 500; }
        .erro { background-color: #ffebee; color: #e3242b; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #ffcdd2; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #666; }
        .form-group input { width: 100%; padding: 16px 14px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; outline: none; transition: border-color .2s, box-shadow .2s; }
        .form-group input:focus { border-color: #3483fa; box-shadow: 0 0 0 1px #3483fa; }
        .btn-submit { width: 100%; background-color: #3483fa; color: #fff; border: none; border-radius: 4px; padding: 16px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color .2s; margin-top: 10px; }
        .btn-submit:hover { background-color: #2968c8; }
        .login-footer { text-align: center; margin-top: 25px; font-size: 13px; color: #999; }
    </style>
</head>
<body>
    <header class="login-header">
        <h1>Loja Senac</h1>
    </header>
    <main class="login-main">
        <div class="login-box">
            <h2>Olá! Digite para entrar</h2>
            <?php if($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label>E-mail ou Login</label>
                    <input type="text" name="email" required placeholder="Digite seu e-mail ou login cadastrado">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required placeholder="Digite sua senha">
                </div>
                <button type="submit" class="btn-submit">Continuar</button>
            </form>
            <div class="login-footer">
                <p>Acesso Restrito - Funcionários</p>
            </div>
        </div>
    </main>
</body>
</html>
