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
        $this->produto->preco = $dados['preco'];

        if($this->produto->cadastrar()){
            header("location: index.php");
            exit();
        }
    }
    public function excluirProduto ($id){
        $this->produto->id = $id;

        if($this->produto->excluir()){
            header("location: index.php");
        }
    }

    public function atualizarProduto ($dados){
        $this->produto->id = $dados['id'];
        $this->produto->nome = $dados['nome'];
        $this->produto->descricao = $dados['descricao'];
        $this->produto->quantidade = $dados['quantidade'];
        $this->produto->preco = $dados['preco'];


        if($this->produto->atualizar()){
            header("location: index.php");
        }
    }

    public function localizarProduto($id){
        return $this->produto->buscarProduto($id);

    }


}