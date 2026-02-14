<?php
include_once "objetos/produtoControler.php";

$controller = new produtoControler();
$produto = $controller->index();
global $produto;
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senac Hell Claro</title>
</head>
<body>

<h1>Loja do arrthu</h1>
<h2>produtos Cadastrado</h2>
<table>
    <tr>
        <td>id</td>
        <td>nome</td>
        <td>descrição</td>
        <td>quantidade</td>
        <td>preço</td>
    </tr>
    <?php if($produto) : ?>
        <?php foreach($produto as $produto) : ?>
            <tr>
                <td><?php echo $produto->id?></td>
                <td><?php echo $produto->nome?></td>
                <td><?php echo $produto->descricao?></td>
                <td><?php echo $produto->quantidade?></td>
                <td><?php echo $produto->preco?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>
