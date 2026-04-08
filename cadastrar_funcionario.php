<?php
session_start();
// Apenas gerentes podem usar esse recurso
if (!isset($_SESSION['logado']) || $_SESSION['cargo'] !== 'Gerente') {
    header("Location: index.php");
    exit;
}

require_once "configs/database.php";

$dbWorker = new Database();
$conWorker = $dbWorker->conectar();

$erro = "";
$sucesso = "";

// Fluxo de submissão do formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $cargo = $_POST['cargo'];

    $stmt = $conWorker->prepare("INSERT INTO funcionario (nome, email, telefone, login, senha, cargo) VALUES (?, ?, ?, ?, ?, ?)");
    if($stmt->execute([$nome, $email, $telefone, $login, $senha, $cargo])){
        $sucesso = "Novo membro integrado à equipe com sucesso!";
    } else {
        $erro = "Erro ao injetar inserção no banco de dados.";
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Novo Funcionário - Loja Senac</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Roboto', sans-serif; background-color: #ebebeb; display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        .main-header { background-color: #fff159; padding: 20px 0; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .header-content { max-width: 800px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .header-content h1 { color: #333; font-size: 24px; margin: 0; }
        .btn-voltar { background: #fff; padding: 10px 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.1); color: #333; text-decoration: none; font-weight: bold; }
        
        .main-container { flex: 1; display: flex; align-items: flex-start; justify-content: center; padding: 40px 20px; }
        .form-box { background: #fff; padding: 40px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; max-width: 600px; }
        .form-box h2 { font-size: 22px; margin-bottom: 25px; color: #333; margin-top: 0; }
        
        .alert-success { background-color: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; border: 1px solid #c8e6c9; }
        .alert-error { background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; border: 1px solid #ffcdd2; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; font-weight: 500;}
        .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 15px; transition: border-color 0.2s; }
        .form-control:focus { border-color: #3483fa; outline: none; }
        
        div.flex-row { display: flex; gap: 15px; margin-bottom: 15px; }
        div.flex-row .form-group { flex: 1; margin-bottom: 0; }
        
        fieldset { border: 1px solid #ddd; padding: 20px; border-radius: 4px; margin-bottom: 25px; background-color: #fafafa; }
        legend { color: #666; font-size: 14px; padding: 0 5px; font-weight: bold; }
        
        .btn-submit { width: 100%; background: #3483fa; color: #fff; padding: 15px; border: none; border-radius: 4px; font-weight: bold; font-size: 16px; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #2968c8; }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <h1>Contratar Novo Funcionário</h1>
            <a href="painel_gerente.php" class="btn-voltar">Retornar ao Painel</a>
        </div>
    </header>

    <main class="main-container">
        <div class="form-box">
            <h2>Dados Pessoais do Reforço</h2>
            
            <?php if ($sucesso): ?><div class="alert-success"><?= $sucesso ?></div><?php endif; ?>
            <?php if ($erro): ?><div class="alert-error"><?= $erro ?></div><?php endif; ?>

            <form action="cadastrar_funcionario.php" method="post">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" placeholder="Ex: Roberto Justos" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>E-mail Corporativo</label>
                    <input type="email" name="email" placeholder="Ex: roberto.justos@empresa.com" required class="form-control">
                </div>
                
                <div class="flex-row">
                    <div class="form-group">
                        <label>Celular / Telefone</label>
                        <input type="text" name="telefone" placeholder="Ex: (11) 98888-8888" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nível de Ocupação (Cargo)</label>
                        <select name="cargo" required class="form-control">
                            <option value="Funcionario" selected>Funcionário Comum</option>
                            <option value="Gerente">Administrador (Gerente)</option>
                        </select>
                    </div>
                </div>

                <fieldset>
                    <legend>Credenciais Iniciais de Entrada</legend>
                    <div class="flex-row" style="margin-bottom:0;">
                        <div class="form-group">
                            <label>Login de Rede</label>
                            <input type="text" name="login" placeholder="Ex: robertojustos" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Senha Provisória</label>
                            <input type="text" name="senha" placeholder="Definir uma senha temporária" required class="form-control">
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="btn-submit">Finalizar Contratação</button>
            </form>
        </div>
    </main>
</body>
</html>
