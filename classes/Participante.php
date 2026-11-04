
<?php

require_once __DIR__ . '/../config/Database.php';

class Participante
{
    private PDO $conexao;
    private string $tabela = "participantes";

    public function __construct()
    {
        $database = new Database();
        $this->conexao = $database->conectar();
    }

    // Listar todos os participantes
    public function listar(): array
    {
        $sql = "SELECT * FROM {$this->tabela} ORDER BY id DESC";

        $consulta = $this->conexao->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    // Buscar participante pelo ID
    public function buscarPorId(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->tabela} WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch();
    }

    // Cadastrar participante
    public function cadastrar(array $dados): bool
    {
        $sql = "INSERT INTO {$this->tabela}
        (nome, email, telefone)
        VALUES
        (:nome, :email, :telefone)";

        $consulta = $this->conexao->prepare($sql);

        return $consulta->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':telefone' => $dados['telefone']
        ]);
    }

    // Editar participante
    public function editar(int $id, array $dados): bool
    {
        $sql = "UPDATE {$this->tabela} SET
        nome = :nome,
        email = :email,
        telefone = :telefone
        WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);

        return $consulta->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':telefone' => $dados['telefone'],
            ':id' => $id
        ]);
    }

    // Excluir participante
    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM {$this->tabela} WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }
}
