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
}