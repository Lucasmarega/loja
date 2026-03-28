<?php

Class Database {
    private $host = "localhost:3316";
    private $usuario = "root";
    private $senha = "123456";
    private $banco = "lojaproduto";
    private $con;

    public function conectar(){
        $this->con = null;

        try {
            $this ->con = new PDO("mysql:host=$this->host;dbname=$this->banco", $this->usuario, $this->senha);
        } catch (PDOException $e) {
            echo "Erro ao tentar conectar com o banco de dados: " . $e->getMessage();
        }
        return $this ->con;
    }
}
