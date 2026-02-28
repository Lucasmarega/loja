<?php

include_once "configs/database.php";
include_once "produto.php";

Class produtoControler{
    private $bd;

    private $produto;

    public function __construct(){
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->produto = new Produto($this->bd);
    }
    public function index(){
        return $this->produto->lerTodos();
    }
    public function pesquisarProduto($id){
        return $this->produto->pesquisaProduto($id);
    }

    public function cadastrarProduto($dados){

        $this->produto->nome = $dados['nome'];
        $this->produto->descricao = $dados['descricao'];
        $this->produto->quantidade = $dados['quantidade'];
        $this->aluno->preco = $dados['preco'];


        if($this->aluno->cadastrar()){
            header("location: index.php");
            exit();
        }
    }


}