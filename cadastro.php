<?php
include("objetos/produtoControler.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new produtoControler();

    if (isset($_POST["cadastrar"])) {
        $controller->cadastrarProduto($_POST["produto"], $_FILES["produto"]);
    }
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de produto</title>
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
                <h1>Cadastro de Produto</h1>
                <a href="index.php">← Voltar para Loja</a>
            </div>

            <form action="cadastro.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" name="produto[nome]" required>
                </div>

                <div class="form-group">
                    <label>Descrição detalhada</label>
                    <input type="text" name="produto[descricao]" required>
                </div>

                <div class="form-group">
                    <label>Quantidade em estoque</label>
                    <input type="number" name="produto[quantidade]" required>
                </div>

                <div class="form-group">
                    <label>Preço</label>
                    <input type="text" name="produto[preco]" placeholder="Ex: 99.90" required>
                </div>

                <div class="form-group">
                    <label for="fileToUpload">Foto do Produto (Opcional)</label>
                    <input type="file" name="produto[fileToUpload]" id="fileToUpload">
                </div>

                <button type="submit" name="cadastrar" value="1" class="btn-submit">Cadastrar Produto</button>
            </form>
        </div>
    </main>

</body>

</html>