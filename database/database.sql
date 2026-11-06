CREATE DATABASE site_aulas;
USE site_aulas;

CREATE TABLE usuarios(

    id_usuario INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL

);

CREATE TABLE conteudos(

    id_conteudo INT AUTO_INCREMENT PRIMARY KEY,

    titulo VARCHAR(150) NOT NULL,
    referencia VARCHAR(255) NOT NULL,

    data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_upd  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

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

CREATE TABLE perguntas(

    id_pergunta INT AUTO_INCREMENT PRIMARY KEY,
    id_conteudo INT NOT NULL,

    pergunta TEXT NOT NULL,

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