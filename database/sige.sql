CREATE DATABASE IF NOT EXISTS sige
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sige;

-- Tabela de eventos
CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    data_evento DATE NOT NULL,
    horario TIME NOT NULL,
    local VARCHAR(150) NOT NULL,
    vagas INT NOT NULL
) ENGINE=InnoDB;

-- Tabela de participantes
CREATE TABLE IF NOT EXISTS participantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20)
) ENGINE=InnoDB;

-- Tabela de inscrições
CREATE TABLE IF NOT EXISTS inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    participante_id INT NOT NULL,
    evento_id INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Ativa',
    data_inscricao DATETIME NOT NULL,

    CONSTRAINT fk_inscricao_participante
        FOREIGN KEY (participante_id)
        REFERENCES participantes(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_inscricao_evento
        FOREIGN KEY (evento_id)
        REFERENCES eventos(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;