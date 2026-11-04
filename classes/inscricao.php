<?php

require_once __DIR__ . '/../config/Database.php';

class Inscricao
{
    private PDO $conexao;
    private string $tabela = "inscricoes";

    public function __construct()
    {
        $database = new Database();
        $this->conexao = $database->conectar();
    }

    // Listar inscrições com dados do participante e evento
    public function listar(): array
    {
        $sql = "SELECT
                    i.id,
                    i.status,
                    i.data_inscricao,
                    p.nome AS participante,
                    e.nome AS evento
                FROM {$this->tabela} i
                INNER JOIN participantes p ON i.participante_id = p.id
                INNER JOIN eventos e ON i.evento_id = e.id
                ORDER BY i.id DESC";

        $consulta = $this->conexao->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    // Buscar inscrição pelo ID
    public function buscarPorId(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->tabela} WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch();
    }

    // Cadastrar inscrição com controle de vagas e duplicidade
    public function cadastrar(int $participanteId, int $eventoId): bool
    {
        try {
            $this->conexao->beginTransaction();

            // Verificar se o evento existe e bloquear sua linha
            $sqlEvento = "SELECT id, vagas
                          FROM eventos
                          WHERE id = :evento_id
                          FOR UPDATE";

            $consultaEvento = $this->conexao->prepare($sqlEvento);
            $consultaEvento->execute([
                ':evento_id' => $eventoId
            ]);

            $dadosEvento = $consultaEvento->fetch();

            if (!$dadosEvento) {
                throw new RuntimeException('O evento selecionado não existe.');
            }

            // Verificar inscrição ativa duplicada
            $sqlDuplicidade = "SELECT COUNT(*)
                               FROM inscricoes
                               WHERE participante_id = :participante_id
                               AND evento_id = :evento_id
                               AND status = 'Ativa'";

            $consultaDuplicidade = $this->conexao->prepare($sqlDuplicidade);
            $consultaDuplicidade->execute([
                ':participante_id' => $participanteId,
                ':evento_id' => $eventoId
            ]);

            if ((int) $consultaDuplicidade->fetchColumn() > 0) {
                throw new RuntimeException(
                    'Este participante já possui inscrição ativa neste evento.'
                );
            }

            // Contar inscrições ativas
            $sqlVagas = "SELECT COUNT(*)
                         FROM inscricoes
                         WHERE evento_id = :evento_id
                         AND status = 'Ativa'";

            $consultaVagas = $this->conexao->prepare($sqlVagas);
            $consultaVagas->execute([
                ':evento_id' => $eventoId
            ]);

            $ocupadas = (int) $consultaVagas->fetchColumn();
            $disponiveis = (int) $dadosEvento['vagas'];

            if ($ocupadas >= $disponiveis) {
                throw new RuntimeException(
                    'Não há vagas disponíveis para este evento.'
                );
            }

            // Registrar inscrição
            $sql = "INSERT INTO {$this->tabela}
                    (participante_id, evento_id, status, data_inscricao)
                    VALUES
                    (:participante_id, :evento_id, 'Ativa', NOW())";

            $consulta = $this->conexao->prepare($sql);

            $resultado = $consulta->execute([
                ':participante_id' => $participanteId,
                ':evento_id' => $eventoId
            ]);

            $this->conexao->commit();

            return $resultado;

        } catch (Throwable $erro) {

            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }

            throw $erro;
        }
    }

    // Cancelar inscrição
    public function cancelar(int $id): bool
    {
        $sql = "UPDATE {$this->tabela}
                SET status = 'Cancelada'
                WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }

    // Excluir inscrição
    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM {$this->tabela} WHERE id = :id";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }
}