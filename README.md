# 🤖 Learn AI

Aprenda no seu ritmo. Descubra o mundo da Inteligência Artificial.

---

## 📖 Sobre o Projeto

O Learn AI é uma plataforma web educacional desenvolvida para apresentar e ensinar os principais conceitos relacionados à Inteligência Artificial.

A proposta do sistema é disponibilizar conteúdos organizados em aulas, permitindo que os usuários explorem diferentes assuntos, aprofundem seus conhecimentos e testem o que aprenderam por meio de quizzes e avaliações.

Durante o desenvolvimento do projeto foram utilizadas ferramentas de Inteligência Artificial como recursos de apoio à criação da identidade visual, à programação, à organização do código e à estilização da interface. Os materiais produzidos foram analisados, revisados e adaptados pelos integrantes conforme as necessidades do projeto. As ferramentas utilizadas, suas finalidades, etapas de utilização e formas de validação encontram-se detalhadas na DECLARAÇÃO DE USO DE INTELIGÊNCIA ARTIFICIAL, apresentada ao final deste documento.

---

## 🎯 Objetivo

Desenvolver uma plataforma educacional moderna e intuitiva que facilite o aprendizado sobre Inteligência Artificial, oferecendo conteúdos organizados, recursos interativos e avaliações para acompanhar o conhecimento adquirido pelos usuários.

---

## 👥 Público-Alvo

- Estudantes interessados em Inteligência Artificial;
- Pessoas que desejam conhecer os fundamentos da IA;
- Usuários interessados em tecnologia e inovação;
- Iniciantes que buscam aprender sobre Inteligência Artificial.

---

## 💡 Diferenciais

- Conteúdos organizados em uma trilha de aprendizado;
- Interface moderna e intuitiva;
- Aulas com conteúdos explicativos e materiais de referência;
- Quizzes individuais para cada aula;
- Avaliação geral para testar os conhecimentos adquiridos;
- Área administrativa para gerenciamento de conteúdos e perguntas.

---

## ✨ Funcionalidades

### 👤 Usuários

- Cadastro de contas;
- Login e autenticação;
- Acesso às avaliações mediante autenticação.

### 📚 Aulas

- Listagem de aulas disponíveis;
- Conteúdos divididos em seções;
- Organização por tags;
- Materiais e links de referência;
- Navegação entre aulas anteriores e próximas;
- Suporte à formatação Markdown e fórmulas matemáticas.

### 📝 Avaliações

- Quizzes relacionados a cada aula;
- Prova final com perguntas de diferentes aulas;
- Embaralhamento de perguntas e alternativas;
- Cronômetro durante as avaliações;
- Correção automática das respostas;
- Exibição de resultados e explicações;
- Histórico de tentativas e desempenho;
- Monitoramento de saída da aba durante a prova.

### ⚙️ Área Administrativa

- Cadastro e gerenciamento de aulas;
- Organização de conteúdos por seções e tags;
- Cadastro e exclusão de perguntas;
- Criação de alternativas e definição das respostas corretas.

---

## 🚀 Tecnologias Utilizadas

### Front-End

- HTML5
- CSS3
- JavaScript

### Back-End

- PHP
- PDO

### Banco de Dados

- MySQL

### Bibliotecas e Recursos

- Marked.js: renderização de conteúdo Markdown.
- DOMPurify: sanitização do conteúdo HTML.
- KaTeX: renderização de fórmulas matemáticas.
- Phosphor Icons: biblioteca de ícones.
- Google Fonts (Inter): tipografia da plataforma.

### Ferramentas

- Git
- GitHub
- Visual Studio Code

---

## 📂 Estrutura do Projeto

```text
SiteAulas/
│
├── actions/                 # Processamento de ações e formulários
│   ├── cadastro_action.php
│   ├── login_action.php
│   ├── logout_action.php
│   └── prova_saida_action.php
│
├── assets/
│   ├── css/                 # Estilização da plataforma
│   ├── imgs/                # Logo e elementos visuais
│   └── js/                  # Scripts e interações
│
├── config/                  # Configurações e conexão com o banco
│   └── connDB.php
│
├── database/                # Estrutura do banco de dados
│   └── database.sql
│
├── includes/                # Componentes e funções reutilizáveis
│   ├── views/               # Telas das avaliações
│   ├── aulas.php
│   ├── bootstrap.php
│   ├── provas.php
│   ├── header.php
│   ├── footer.php
│   └── ...
│
├── pages/                   # Páginas principais da aplicação
│   ├── index.php
│   ├── aula.php
│   ├── login.php
│   ├── cadastro.php
│   ├── prova.php
│   ├── add_aula.php
│   └── add_pergunta.php
│
└── README.md                # Documentação do projeto
```

---

## ⚙️ Instalação e Execução

Para executar o projeto localmente, é necessário possuir um ambiente com PHP e MySQL.

1. Clone ou baixe o repositório do projeto.
2. Configure o servidor PHP e o banco de dados MySQL.
3. Importe o arquivo `database/database.sql` no MySQL para criar a estrutura do banco de dados.
4. Configure as credenciais de conexão no arquivo `config/connDB.php`.
5. Inicie o servidor local na pasta principal do projeto.
6. Acesse a plataforma pelo navegador através de `http://localhost:8000/pages/index.php`, utilizando a porta configurada no servidor.

---

## 👨‍💻 Equipe

- Arthur Torres Mendes
- Bruno Lourenço de Lima
- Isaac Faleiros Quevedo
- Henrique Silvestre Martin

Projeto desenvolvido para fins acadêmicos no curso de Desenvolvimento de Sistemas.

---

## 📈 Status

🚧 Em desenvolvimento.

---

## 🤖 DECLARAÇÃO DE USO DE INTELIGÊNCIA ARTIFICIAL

Durante o desenvolvimento do Learn AI, foram utilizadas ferramentas de Inteligência Artificial generativa como recursos de apoio à criação da identidade visual, à programação, à organização do código e à estilização da interface.

Os materiais e as sugestões produzidos com auxílio dessas ferramentas foram analisados e revisados pelos integrantes, que realizaram adaptações e correções conforme as necessidades do projeto.

A tabela a seguir apresenta as ferramentas utilizadas, suas respectivas aplicações e os procedimentos de validação adotados pelo grupo.

| Ferramenta | Etapa | Finalidade | Validação |
|---|---|---|---|
| ChatGPT | Identidade visual | Auxílio na criação e adaptação da logo do projeto. | A imagem foi adaptada conforme a identidade visual desejada e analisada pelos integrantes até atingir o resultado esperado. |
| ChatGPT | Desenvolvimento | Auxílio na elaboração, alteração e correção de códigos. | As alterações foram revisadas pelos integrantes, que realizaram adaptações para atender aos objetivos do projeto. |
| Claude | Organização do código | Reorganização do código em blocos e inclusão de comentários explicativos. | O resultado foi analisado pelos integrantes, que realizaram as correções necessárias para melhorar a organização e a compreensão do código. |
| Claude | Front-End | Sugestões de estilização CSS e melhorias na apresentação visual da plataforma. | As alterações foram verificadas no ambiente local (`localhost`), com análise visual e ajustes quando necessários. |

---
