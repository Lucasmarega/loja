<?php
include("objetos/produtoControler.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller = new ProdutoControler();

    if (isset($_POST["cadastrar"])){
        $a = $controller->cadastrarProduto($_POST["produto"]);
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de produto</title>
</head>
<body>

<h1>cadastro de produto</h1>
<a href="index.php">Voltar</a>

<form action="cadastro.php" method="post">
    <label>Nome</label>
    <input type="text" name=produto[nome]"><br><br>

    <label>descricao</label>
    <input type="text" name=produto[descricao]"><br><br>

    <label>quantidade</label>
    <input type="number" name=produto[quantidade]"><br><br>

    <label>telefone</label>
    <input type="text" name=produto[preco]"><br><br>

    <button name="cadastrar">cadastrar</button>
</form>

</body>
</html>