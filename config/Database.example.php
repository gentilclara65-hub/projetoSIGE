<?php

class Database {
    private $host = '127.0.0.1';
    private $port = '3306';
    private $dbname = 'sige';
    private $username = 'root';
    private $password = 'SUA_SENHA_AQUI';

    public function conectar(): PDO
    {
        try {
            $conexao = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $conexao;

        } catch (PDOException $erro) {
            die("Erro ao conectar ao banco de dados: " . $erro->getMessage());
        }
    }
}