<?php
include("objetos/produtoControler.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller = new produtoControler();

    if (isset($_POST["cadastrar"])){
        $controller->cadastrarProduto($_POST["produto"], $_FILES["produto"]);
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de produto</title>
</head>
<body>

<h1>cadastro de produto</h1>
<a href="index.php">Voltar</a>

<form action="cadastro.php" method="post" enctype="multipart/form-data">
    <label>Nome</label>
    <input type="text" name="produto[nome]"><br><br>

    <label>descricao</label>
    <input type="text" name="produto[descricao]"><br><br>

    <label>quantidade</label>
    <input type="number" name="produto[quantidade]"><br><br>

    <label>Preço</label>
    <input type="text" name="produto[preco]"><br><br>

    <button type="submit" name="cadastrar" value="1">cadastrar</button>
    <label for="fileToUpload">selecionar foto</label>
    <input type="file" name="produto[fileToUpload]" id="fileToUpload"><br><br>
</form>

</body>
</html>