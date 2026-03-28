<?php
include_once "configs/database.php";
include_once "produto.php";

class produtoControler {
    private $bd;
    private $produto;
    private $imagens;

    public function __construct() {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->produto = new Produto($this->bd);
    }

    public function index() {
        return $this->produto->lerTodos();
    }

    public function pesquisarProduto($id) {
        return $this->produto->pesquisaProduto($id);
    }

    private function upload($arquivo) {
        $nomeOriginal  = $arquivo['name']['fileToUpload'];
        $tmpName       = $arquivo['tmp_name']['fileToUpload'];
        $extensao      = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
        $nomeUnico     = uniqid() . '.' . $extensao;
        $destino       = __DIR__ . '/../uploads/' . $nomeUnico;

        if (move_uploaded_file($tmpName, $destino)) {
            $this->imagens = $nomeUnico;
            return true;
        }
        return false;
    }

    public function cadastrarProduto($dados, $arquivo) {
        $temArquivo = isset($arquivo['name']['fileToUpload']) &&
            $arquivo['name']['fileToUpload'] !== '' &&
            $arquivo['error']['fileToUpload'] === UPLOAD_ERR_OK;

        if ($temArquivo) {
            if (!$this->upload($arquivo)) {
                return false;
            }
        } else {
            $this->imagens = null;
        }

        $this->produto->nome       = $dados['nome'];
        $this->produto->descricao  = $dados['descricao'];
        $this->produto->quantidade = $dados['quantidade'];
        $this->produto->preco      = $dados['preco'];
        $this->produto->imagens    = $this->imagens;

        if ($this->produto->cadastrar()) {
            header("location: index.php");
        } else {
            return false;
        }
    }

    public function excluirProduto($id) {
        $this->produto->id = $id;
        if ($this->produto->excluir()) {
            header("location: index.php");
        }
    }

    public function atualizarProduto($dados) {
        $this->produto->id         = $dados['id'];
        $this->produto->nome       = $dados['nome'];
        $this->produto->descricao  = $dados['descricao'];
        $this->produto->quantidade = $dados['quantidade'];
        $this->produto->preco      = $dados['preco'];
        $this->produto->imagens    = $dados['imagens'];

        if ($this->produto->atualizar()) {
            header("location: index.php");
        }
    }

    public function localizarProduto($id) {
        return $this->produto->buscarProduto($id);
    }
}