<?php
include_once "configs/database.php";

$banco = new Database();
$bd = $banco -> conectar();

if ($bd) {
    $sql = "SELECT * FROM produtos";
    $resultado = $bd->query($sql);
    $resultado -> execute();
    $resultado = $resultado -> fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultado as $produto) {
        echo $produto['nome'] . "<br>";
        echo $produto['descricao'] . "<br>";
        echo $produto['quantidade'] . "<br>";
        echo $produto['preco'] . "<br>";
    }
}else{
    echo "falha ao conectar banco";
}
