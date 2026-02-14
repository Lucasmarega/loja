<?php
Class Produto{
    public $nome;
    public $descricao;
    public $quantidade;
    public $preco;
    public $img;
    public $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }

    public function lerTodos(){
        $sql = "SELECT * FROM produtos";
        $resultado = $this->bd->query($sql);
        $resultado->execute();

        return $resultado ->fetchAll(PDO::FETCH_OBJ);
    }
}