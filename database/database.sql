CREATE DATABASE site_aulas;
USE site_aulas;

CREATE TABLE usuarios(

    id_usuario INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL

);

CREATE TABLE card_aulas (
    id_card INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    num_aula INT NOT NULL,
    descricao VARCHAR(100) NOT NULL
);

CREATE TABLE tag (
    id_tag INT AUTO_INCREMENT PRIMARY KEY,
    tag VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE aula_tag (
    id_card INT NOT NULL,
    id_tag INT NOT NULL,

    PRIMARY KEY (id_card, id_tag),

    FOREIGN KEY (id_card)
        REFERENCES card_aulas(id_card)
        ON DELETE CASCADE,

    FOREIGN KEY (id_tag)
        REFERENCES tag(id_tag)
        ON DELETE CASCADE
);

CREATE TABLE conteudos(

    id_conteudo INT AUTO_INCREMENT PRIMARY KEY,

    -- Aula (card) a que este conteúdo pertence. Uma aula tem no máximo um conteúdo.
    id_card INT NULL UNIQUE,

    titulo VARCHAR(150) NOT NULL,
    referencia VARCHAR(255) NOT NULL,

    data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_upd  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (id_card)
        REFERENCES card_aulas(id_card)
        ON DELETE CASCADE

);

CREATE TABLE subtitulos(

    id_subtitulo INT AUTO_INCREMENT PRIMARY KEY,
    id_conteudo INT NOT NULL,
    
    titulo VARCHAR(150) NOT NULL,
    conteudo TEXT NOT NULL,
    ordem INT NOT NULL,

    FOREIGN KEY (id_conteudo)
        REFERENCES conteudos(id_conteudo)
        ON DELETE CASCADE

);

CREATE TABLE perguntas(

    id_pergunta INT AUTO_INCREMENT PRIMARY KEY,
    id_conteudo INT NOT NULL,

    pergunta TEXT NOT NULL,
    explicacao TEXT NULL,

    FOREIGN KEY (id_conteudo)
        REFERENCES conteudos(id_conteudo)
        ON DELETE CASCADE

);

CREATE TABLE alternativas(

    id_alternativa INT AUTO_INCREMENT PRIMARY KEY,
    id_pergunta INT NOT NULL,

    conteudo TEXT NOT NULL,
    correta BOOLEAN NOT NULL DEFAULT false,

    FOREIGN KEY (id_pergunta)
        REFERENCES perguntas(id_pergunta)
        ON DELETE CASCADE

);

-- Provas dos alunos: uma linha por tentativa e uma por pergunta respondida.
-- id_card NULL = prova final; preenchido = quiz de uma aula.
CREATE TABLE tentativas(

    id_tentativa INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_card INT NULL,

    acertos INT NOT NULL,
    total INT NOT NULL,
    data_tentativa TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,

    FOREIGN KEY (id_card)
        REFERENCES card_aulas(id_card)
        ON DELETE CASCADE

);

CREATE TABLE tentativa_respostas(

    id_tentativa INT NOT NULL,
    id_pergunta INT NOT NULL,
    id_alternativa INT NULL,
    correta BOOLEAN NOT NULL DEFAULT false,

    PRIMARY KEY (id_tentativa, id_pergunta),

    FOREIGN KEY (id_tentativa)
        REFERENCES tentativas(id_tentativa)
        ON DELETE CASCADE,

    FOREIGN KEY (id_pergunta)
        REFERENCES perguntas(id_pergunta)
        ON DELETE CASCADE,

    FOREIGN KEY (id_alternativa)
        REFERENCES alternativas(id_alternativa)
        ON DELETE SET NULL

);
