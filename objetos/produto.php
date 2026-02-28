<?php
Class Produto{
    public $id;
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

    public function pesquisaProduto($id){
        $sql = "SELECT * FROM produtos WHERE id = :id";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(":id", $id);
        $resultado->execute();

        return $resultado ->fetch(PDO::FETCH_OBJ);
    }

    public function cadastrar(){
        $sql = "INSERT INTO alunos (nome, descricao, quantidade, preco) VALUES (:nome, :descricao, :quantidade, :preco)";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":nome", $this->nome, PDO::PARAM_STR);
        $stmt->bindParam(":descricao", $this->descricao, PDO::PARAM_STR);
        $stmt->bindParam(":descricao", $this->quantidade, PDO::PARAM_STR);
        $stmt->bindParam(":preco", $this->preco, PDO::PARAM_STR);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }
}