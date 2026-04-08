<?php

include("objetos/produtoControler.php");

$controller = new ProdutoControler();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['alterar'])) {
    $a = $controller->localizarProduto($_GET['alterar']);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto'])) {
    $arquivo = isset($_FILES['produto']) ? $_FILES['produto'] : null;
    $controller->atualizarProduto($_POST['produto'], $arquivo);
} else {
    header("location: index.php");
}

?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Atualização de produto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="main-header">
        <div class="header-content">
            <a href="index.php" style="text-decoration:none;"><h1>Loja Senac</h1></a>
        </div>
    </header>

    <main class="main-container">
        <div class="form-container">
            <div class="form-header">
                <h1>Atualização de Produto</h1>
                <a href="index.php">← Voltar para Loja</a>
            </div>

            <form action="atualizar.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="produto[id]" value="<?= $a->id ?>">
                <input type="hidden" name="produto[imagem_atual]" value="<?= $a->imagens ?>">

                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" name="produto[nome]" value="<?= $a->nome ?>" required>
                </div>

                <div class="form-group">
                    <label>Descrição detalhada</label>
                    <input type="text" name="produto[descricao]" value="<?= $a->descricao ?>" required>
                </div>

                <div class="form-group">
                    <label>Quantidade em estoque</label>
                    <input type="number" name="produto[quantidade]" value="<?= $a->quantidade ?>" required>
                </div>

                <div class="form-group">
                    <label>Preço</label>
                    <input type="text" name="produto[preco]" value="<?= $a->preco ?>" required>
                </div>

                <div class="form-group">
                    <label for="fileToUpload">Foto do Produto (mantenha vazio para não alterar)</label>
                    <input type="file" name="produto[fileToUpload]" id="fileToUpload">
                </div>

                <button name="atualizar" class="btn-submit">Salvar Alterações</button>
            </form>
        </div>
    </main>
</body>

</html>