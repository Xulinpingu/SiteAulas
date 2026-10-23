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