
<?php

require_once __DIR__ . '/../config/Database.php';

class Evento
{
    private PDO $conexao;
    private string $tabela = "eventos";

    public function __construct()
    {
        $database = new Database();
        $this->conexao = $database->conectar();
    }

    // Listar todos os eventos
    public function listar(): array
    {
        $sql = "SELECT * FROM {$this->tabela} ORDER BY id DESC";
        $consulta = $this->conexao->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    // Buscar evento pelo ID
    public function buscarPorId(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->tabela} WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch();
    }

    // Cadastrar evento
    public function cadastrar(array $dados): bool
    {
        $sql = "INSERT INTO {$this->tabela}
        (nome, descricao, data_evento, horario, local, vagas)
        VALUES
        (:nome, :descricao, :data_evento, :horario, :local, :vagas)";

        $consulta = $this->conexao->prepare($sql);

        return $consulta->execute([
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'],
            ':data_evento' => $dados['data_evento'],
            ':horario' => $dados['horario'],
            ':local' => $dados['local'],
            ':vagas' => $dados['vagas']
        ]);
    }

    // Editar evento
    public function editar(int $id, array $dados): bool
    {
        $sql = "UPDATE {$this->tabela} SET
        nome = :nome,
        descricao = :descricao,
        data_evento = :data_evento,
        horario = :horario,
        local = :local,
        vagas = :vagas
        WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);

        return $consulta->execute([
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'],
            ':data_evento' => $dados['data_evento'],
            ':horario' => $dados['horario'],
            ':local' => $dados['local'],
            ':vagas' => $dados['vagas'],
            ':id' => $id
        ]);
    }

    // Excluir evento
    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM {$this->tabela} WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }
}
