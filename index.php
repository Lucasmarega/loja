<?php
include_once "objetos/produtoControler.php";

$controller = new produtoControler();
$produtos = $controller->index();
global $produtos;
$a = null;

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["pesquisar"])){
        $a = $controller->pesquisarProduto($_POST["pesquisar"]);
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET"){
    if(isset($_GET["excluir"])){
        $a = $controller->excluirProduto($_GET["excluir"]);
    }
}

var_dump($a);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senac Hell Claro</title>
    <style>
    table,tr,td{
        border: 1px solid black;
        border-collapse: collapse;
    }
    </style>
</head>
<body>

<h1>Loja do arrthu</h1>
<a href="cadastro.php">Cadastrar Produto</a>
<h3>produtos Cadastrado</h3>

<form method="post" action="index.php">
    <label>ID</label>
    <input type="number" name="pesquisar">
    <select name="tipo">
        <option value="id">ID</option>
        <option value="descrição">descricao</option>
    </select>
    <button>Pesquisar</button>
</form>

<table>
    <tr>
        <td>id</td>
        <td>nome</td>
        <td>descrição</td>
        <td>quantidade</td>
        <td>preço</td>
    </tr>
    <?php if($produtos) : ?>
        <?php foreach($produtos as $produto) : ?>
            <tr>
                <td><?= $produto->id; ?></td>
                <td><?= $produto->nome; ?></td>
                <td><?= $produto->descricao; ?></td>
                <td><?= $produto->quantidade; ?></td>
                <td><?= $produto->preco; ?></td>
                <td><a href="index.php?excluir=<?= $produto->id ?>">EXCLUIR</a> </td>
                <td><a href="atualizar.php?alterar=<?= $produto->id ?>">ALTERAR</a> </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>
