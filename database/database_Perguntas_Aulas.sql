-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/11/2026 às 23:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `site_aulas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alternativas`
--

CREATE TABLE `alternativas` (
  `id_alternativa` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `correta` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alternativas`
--

INSERT INTO `alternativas` (`id_alternativa`, `id_pergunta`, `conteudo`, `correta`) VALUES
(1, 1, 'Aprender a operação lógica AND a partir de exemplos.', 1),
(2, 1, 'Armazenar todos os resultados em uma tabela fixa.', 0),
(3, 1, 'Resolver qualquer operação lógica, inclusive XOR.', 0),
(4, 1, 'Substituir todas as instruções condicionais do Python.', 0),
(5, 2, 'Quando pelo menos uma entrada é 1.', 0),
(6, 2, 'Quando as duas entradas são 0.', 0),
(7, 2, 'Somente quando as duas entradas são 1.', 1),
(8, 2, 'Sempre que as entradas forem diferentes.', 0),
(9, 3, 'z = A + B + bias.', 0),
(10, 3, 'z = A · w1 + B · w2 + b.', 1),
(11, 3, 'z = w1 + w2 + A · B.', 0),
(12, 3, 'z = A · w1 − B · w2 − b.', 0),
(13, 4, 'A quantidade de épocas do treinamento.', 0),
(14, 4, 'O resultado esperado de cada exemplo.', 0),
(15, 4, 'A taxa usada para corrigir os erros.', 0),
(16, 4, 'A influência de cada entrada sobre a soma ponderada.', 1),
(17, 5, 'Deslocar o limiar da decisão, sendo somado diretamente à soma ponderada.', 1),
(18, 5, 'Multiplicar todas as entradas por uma constante.', 0),
(19, 5, 'Contar quantos exemplos foram classificados corretamente.', 0),
(20, 5, 'Definir quantas épocas serão executadas.', 0),
(21, 6, '0, porque somente valores positivos ativam o neurônio.', 0),
(22, 6, 'Depende do valor de w1.', 0),
(23, 6, '1, porque z maior ou igual a zero produz saída 1.', 1),
(24, 6, '0,5, porque está exatamente no limiar.', 0),
(25, 7, '0, pois z = −0,7.', 0),
(26, 7, '1, pois z = 0,3.', 1),
(27, 7, '1, pois z = 1,7.', 0),
(28, 7, '0, pois z = −0,2.', 0),
(29, 8, '1, pois z = 0,5.', 0),
(30, 8, '1, pois z = 0.', 0),
(31, 8, '0, pois z = −0,7.', 0),
(32, 8, '0, pois z = −0,2.', 1),
(33, 9, 'Porque ele é multiplicado sempre pela entrada A.', 0),
(34, 9, 'Porque só pode assumir valores positivos.', 0),
(35, 9, 'Porque é somado diretamente, sem multiplicar uma entrada específica.', 1),
(36, 9, 'Porque não pode ser atualizado durante o treinamento.', 0),
(37, 10, 'Erro = previsto − esperado.', 0),
(38, 10, 'Erro = esperado − previsto.', 1),
(39, 10, 'Erro = esperado × previsto.', 0),
(40, 10, 'Erro = esperado + previsto.', 0),
(41, 11, '+1.', 1),
(42, 11, '−1.', 0),
(43, 11, '0.', 0),
(44, 11, '2.', 0),
(45, 12, 'Que o modelo acertou.', 0),
(46, 12, 'Que o modelo respondeu baixo demais.', 0),
(47, 12, 'Que a entrada deve ser ignorada.', 0),
(48, 12, 'Que o modelo respondeu alto demais.', 1),
(49, 13, 'Todos os pesos aumentam.', 0),
(50, 13, 'O bias é sempre zerado.', 0),
(51, 13, 'Nenhum parâmetro muda, pois o erro é zero.', 1),
(52, 13, 'A taxa de aprendizado passa a ser zero.', 0),
(53, 14, 'Determinar o tamanho das correções aplicadas aos parâmetros.', 1),
(54, 14, 'Indicar quantas entradas existem.', 0),
(55, 14, 'Escolher automaticamente a operação lógica.', 0),
(56, 14, 'Garantir que o treinamento termine mais rápido.', 0),
(57, 15, 'Para contar quantas épocas já passaram.', 0),
(58, 15, 'Porque o peso w1 está associado à entrada A.', 1),
(59, 15, 'Para transformar o erro em uma previsão.', 0),
(60, 15, 'Porque o bias depende de A.', 0),
(61, 16, 'w1 é sempre zerado.', 0),
(62, 16, 'w1 aumenta pela taxa de aprendizado.', 0),
(63, 16, 'A atualização de w1 é zero.', 1),
(64, 16, 'O treinamento é interrompido.', 0),
(65, 17, 'Sim, porque sua atualização não é multiplicada por uma entrada.', 1),
(66, 17, 'Não, pois o bias depende de A e B.', 0),
(67, 17, 'Somente se os dois pesos forem negativos.', 0),
(68, 17, 'Apenas na última época.', 0),
(69, 18, 'Uma atualização de um único peso.', 0),
(70, 18, 'Uma previsão feita pelo modelo.', 0),
(71, 18, 'O tempo necessário para executar o programa.', 0),
(72, 18, 'Uma passagem completa por todos os exemplos de treinamento.', 1),
(73, 19, '4.', 0),
(74, 19, '20.', 1),
(75, 19, '2.', 0),
(76, 19, '100.', 0),
(77, 20, 'Como pares de pesos e bias.', 0),
(78, 20, 'Como textos contendo a operação AND.', 0),
(79, 20, 'Como pares formados por entradas e resultado esperado.', 1),
(80, 20, 'Como previsões já calculadas.', 0),
(81, 21, 'Calcular z e aplicar a função degrau.', 1),
(82, 21, 'Atualizar todos os pesos.', 0),
(83, 21, 'Criar os exemplos de treinamento.', 0),
(84, 21, 'Contar o número de épocas.', 0),
(85, 22, 'Porque a função degrau só funciona na última época.', 0),
(86, 22, 'Porque corrigir um exemplo pode temporariamente prejudicar outro.', 1),
(87, 22, 'Porque os pesos não podem mudar durante uma época.', 0),
(88, 22, 'Porque cada época cria uma nova rede.', 0),
(89, 23, 'Na primeira.', 0),
(90, 23, 'Na segunda.', 0),
(91, 23, 'Na terceira.', 1),
(92, 23, 'Somente na vigésima.', 0),
(93, 24, 'Porque o Python bloqueia as variáveis.', 0),
(94, 24, 'Porque a taxa de aprendizado vira negativa.', 0),
(95, 24, 'Porque o modelo deixa de executar o laço.', 0),
(96, 24, 'Porque as previsões ficam corretas e o erro passa a ser zero.', 1),
(97, 25, 'A fração de respostas corretas nos próprios exemplos usados para treinar.', 1),
(98, 25, 'A capacidade de acertar qualquer dado futuro.', 0),
(99, 25, 'O valor final do bias.', 0),
(100, 25, 'A quantidade total de épocas.', 0),
(101, 26, 'Repetir o treinamento várias vezes.', 0),
(102, 26, 'Obter pesos iguais a zero.', 0),
(103, 26, 'Ter bom desempenho em exemplos novos, que não foram usados no treinamento.', 1),
(104, 26, 'Classificar corretamente somente os exemplos já vistos.', 0),
(105, 27, 'Porque quatro exemplos não podem ser usados em Python.', 0),
(106, 27, 'Porque os mesmos dados foram usados para ajustar e avaliar o modelo.', 1),
(107, 27, 'Porque o AND não possui resultado esperado.', 0),
(108, 27, 'Porque a função degrau não retorna números inteiros.', 0),
(109, 28, 'Ele sempre alcança 4 acertos.', 0),
(110, 28, 'Ele aprende XOR se a taxa for suficientemente alta.', 0),
(111, 28, 'Ele transforma automaticamente a função degrau em Sigmoid.', 0),
(112, 28, 'Ele não consegue acertar simultaneamente as quatro combinações; a aula observa no máximo 2 de 4.', 1),
(113, 29, 'O Perceptron usa somente textos e a Sigmoid usa números.', 0),
(114, 29, 'O Perceptron não possui entradas.', 0),
(115, 29, 'A ativação degrau produz uma classe 0 ou 1, enquanto a Sigmoid é suave e diferenciável.', 1),
(116, 29, 'A Sigmoid não possui parâmetros ajustáveis.', 0),
(117, 30, 'Ele ajusta pesos e bias com base nos erros, mas sua capacidade depende do problema que tenta resolver.', 1),
(118, 30, 'Ele aprende qualquer função lógica se forem usadas épocas suficientes.', 0),
(119, 30, 'Ele não precisa de exemplos para ajustar os parâmetros.', 0),
(120, 30, 'Ele altera apenas o bias e mantém os pesos fixos.', 0),
(121, 31, 'AND.', 0),
(122, 31, 'XOR.', 1),
(123, 31, 'Soma de números.', 0),
(124, 31, 'OR.', 0),
(125, 32, '0.', 1),
(126, 32, '1.', 0),
(127, 32, '0,5.', 0),
(128, 32, 'Depende dos pesos.', 0),
(129, 33, 'Quando as duas entradas são iguais.', 0),
(130, 33, 'Somente quando ambas são 1.', 0),
(131, 33, 'Quando exatamente uma das duas entradas é 1.', 1),
(132, 33, 'Sempre que A é 0.', 0),
(133, 34, 'Porque o XOR possui mais de duas entradas.', 0),
(134, 34, 'Porque o Perceptron não pode usar pesos negativos.', 0),
(135, 34, 'Porque a saída do XOR precisa ser um texto.', 0),
(136, 34, 'Porque os exemplos do XOR não podem ser separados por uma única reta.', 1),
(137, 35, '1–1–1.', 0),
(138, 35, '2–2–1.', 1),
(139, 35, '2–1–2.', 0),
(140, 35, '4–2–2.', 0),
(141, 36, 'Dois.', 1),
(142, 36, 'Um.', 0),
(143, 36, 'Três.', 0),
(144, 36, 'Quatro.', 0),
(145, 37, 'Armazenar diretamente os resultados finais.', 0),
(146, 37, 'Substituir a necessidade de entradas.', 0),
(147, 37, 'Construir representações intermediárias a partir das entradas.', 1),
(148, 37, 'Executar o código Python sem pesos.', 0),
(149, 38, 'N1 = AND e N2 = XOR.', 0),
(150, 38, 'N1 = XOR e N2 = OR.', 0),
(151, 38, 'N1 = AND e N2 = NOT.', 0),
(152, 38, 'N1 = OR e N2 = AND.', 1),
(153, 39, 'Quando N1 = 0 e N2 = 1.', 0),
(154, 39, 'Quando N1 = 1 e N2 = 0.', 1),
(155, 39, 'Sempre que N1 e N2 forem iguais.', 0),
(156, 39, 'Quando N1 = 0 e N2 = 0.', 0),
(157, 40, 'Porque várias somas ponderadas podem ser combinadas em uma única transformação afim.', 1),
(158, 40, 'Porque camadas adicionais apagam todas as entradas.', 0),
(159, 40, 'Porque pesos não podem ser usados em camadas ocultas.', 0),
(160, 40, 'Porque o XOR exige uma função de ativação degrau em todas as camadas.', 0),
(161, 41, 'σ(z) = 1 + e^−z.', 0),
(162, 41, 'σ(z) = z².', 0),
(163, 41, 'σ(z) = 1 / (1 + e^−z).', 1),
(164, 41, 'σ(z) = e^z − 1.', 0),
(165, 42, 'Somente 0 e 1.', 0),
(166, 42, 'Qualquer número inteiro.', 0),
(167, 42, 'Somente números negativos.', 0),
(168, 42, 'Valores entre 0 e 1.', 1),
(169, 43, '0,5.', 1),
(170, 43, '0.', 0),
(171, 43, '1.', 0),
(172, 43, '2.', 0),
(173, 44, 'σ\'(z) = 1 − σ(z).', 0),
(174, 44, 'σ\'(z) = σ(z) · (1 − σ(z)).', 1),
(175, 44, 'σ\'(z) = z · σ(z).', 0),
(176, 44, 'σ\'(z) = σ(z)² + 1.', 0),
(177, 45, 'Porque elimina a necessidade de pesos.', 0),
(178, 45, 'Porque sempre retorna exatamente 0 ou 1.', 0),
(179, 45, 'Porque é suave e diferenciável.', 1),
(180, 45, 'Porque impede qualquer erro numérico.', 0),
(181, 46, 'Sim, em qualquer rede.', 0),
(182, 46, 'Sim, desde que z seja positivo.', 0),
(183, 46, 'Não, porque a Sigmoid retorna apenas valores negativos.', 0),
(184, 46, 'Não; é apenas um número entre 0 e 1 e não é automaticamente uma probabilidade calibrada.', 1),
(185, 47, 'Perda quadrática: L = 1/2 (t − y)².', 1),
(186, 47, 'Erro absoluto: L = t + y.', 0),
(187, 47, 'Perda logarítmica obrigatória.', 0),
(188, 47, 'L = t · y.', 0),
(189, 48, 't é a taxa de aprendizado e y é o peso.', 0),
(190, 48, 't é o resultado esperado e y é a previsão.', 1),
(191, 48, 't é o bias e y é a entrada.', 0),
(192, 48, 't é a época e y é o gradiente.', 0),
(193, 49, 't − y.', 0),
(194, 49, 'y + t.', 0),
(195, 49, 'y − t.', 1),
(196, 49, '2y + t.', 0),
(197, 50, 'O número de neurônios de uma rede.', 0),
(198, 50, 'A sequência de exemplos do treinamento.', 0),
(199, 50, 'A saída binária da função degrau.', 0),
(200, 50, 'A variação local da perda em relação a um parâmetro.', 1),
(201, 51, 'w ← w − η · gradiente.', 1),
(202, 51, 'w ← w + η, sempre.', 0),
(203, 51, 'w ← gradiente − w.', 0),
(204, 51, 'w ← w × η, sem considerar o gradiente.', 0),
(205, 52, 'Gera entradas aleatórias para a rede.', 0),
(206, 52, 'Aplica a regra da cadeia para propagar gradientes da saída às camadas anteriores.', 1),
(207, 52, 'Substitui a função de ativação por AND.', 0),
(208, 52, 'Calcula somente a acurácia final.', 0),
(209, 53, 'O peso da primeira entrada.', 0),
(210, 53, 'A taxa de aprendizado.', 0),
(211, 53, 'A saída do neurônio final da rede.', 1),
(212, 53, 'O número de épocas.', 0),
(213, 54, 'Para evitar que os gradientes misturem parâmetros de momentos diferentes do treinamento.', 1),
(214, 54, 'Para garantir que todos os pesos sejam positivos.', 0),
(215, 54, 'Porque o Python não permite atualizar variáveis dentro de laços.', 0),
(216, 54, 'Para que a rede não precise calcular a previsão.', 0),
(217, 55, 'Um peso que a rede aprende durante o treinamento.', 0),
(218, 55, 'Um valor que torna reproduzível a sequência pseudoaleatória.', 1),
(219, 55, 'A quantidade de neurônios ocultos.', 0),
(220, 55, 'Uma função de perda alternativa.', 0),
(221, 56, 'Sim, ela é atualizada pelo gradiente.', 0),
(222, 56, 'Sim, mas somente na camada de saída.', 0),
(223, 56, 'Não; ela controla a sequência pseudoaleatória usada na inicialização.', 1),
(224, 56, 'Não; ela substitui os pesos e os bias.', 0),
(225, 57, 'Para garantir que todos tenham a mesma representação.', 0),
(226, 57, 'Para zerar os gradientes.', 0),
(227, 57, 'Para eliminar a necessidade da Sigmoid.', 0),
(228, 57, 'Para quebrar a simetria e evitar que aprendam exatamente a mesma representação.', 1),
(229, 58, 'Sua derivada se aproxima de zero e os ajustes podem ficar muito pequenos.', 1),
(230, 58, 'A rede passa a ter mais entradas.', 0),
(231, 58, 'A perda obrigatoriamente vira zero.', 0),
(232, 58, 'Os pesos deixam de existir.', 0),
(233, 59, 'Apenas a seed e a perda final.', 0),
(234, 59, 'fan_in e fan_out.', 1),
(235, 59, 'Somente a quantidade de épocas.', 0),
(236, 59, 'O valor esperado do XOR.', 0),
(237, 60, 'Xavier venceu em todas as execuções.', 0),
(238, 60, 'Os dois métodos tiveram exatamente os mesmos resultados.', 0),
(239, 60, 'A inicialização uniforme teve melhor resultado nesse conjunto: 16/20 contra 15/20, sem provar superioridade geral.', 1),
(240, 60, 'A inicialização uniforme falhou em todas as execuções.', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `aula_tag`
--

CREATE TABLE `aula_tag` (
  `id_card` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aula_tag`
--

INSERT INTO `aula_tag` (`id_card`, `id_tag`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `card_aulas`
--

CREATE TABLE `card_aulas` (
  `id_card` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `num_aula` int(11) NOT NULL,
  `descricao` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `card_aulas`
--

INSERT INTO `card_aulas` (`id_card`, `title`, `num_aula`, `descricao`) VALUES
(1, 'Perceptron: como um neurônio aprende', 1, 'Aula introdutória a inteligência artificial'),
(2, 'Redes multicamadas, Sigmoid e Backpropagation', 2, 'Aprenda como múltiplos neurônios se conectam para chegar no resultado esperado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conteudos`
--

CREATE TABLE `conteudos` (
  `id_conteudo` int(11) NOT NULL,
  `id_card` int(11) DEFAULT NULL,
  `titulo` varchar(150) NOT NULL,
  `referencia` varchar(255) NOT NULL,
  `data_publicacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_upd` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conteudos`
--

INSERT INTO `conteudos` (`id_conteudo`, `id_card`, `titulo`, `referencia`, `data_publicacao`, `data_upd`) VALUES
(1, 1, 'Perceptron: como um neurônio aprende', '', '2026-11-26 21:38:43', '2026-11-26 21:38:43'),
(2, 2, 'Redes multicamadas, Sigmoid e Backpropagation', '', '2026-11-26 21:40:41', '2026-11-26 21:40:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas`
--

CREATE TABLE `perguntas` (
  `id_pergunta` int(11) NOT NULL,
  `id_conteudo` int(11) NOT NULL,
  `pergunta` text NOT NULL,
  `explicacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perguntas`
--

INSERT INTO `perguntas` (`id_pergunta`, `id_conteudo`, `pergunta`, `explicacao`) VALUES
(1, 1, 'Qual é o objetivo principal do Perceptron apresentado na Aula 01?', 'O Perceptron ajusta seus próprios parâmetros usando exemplos. Na aula, ele aprende a operação AND sem receber uma regra pronta com if.'),
(2, 1, 'Em qual situação a operação lógica AND retorna 1?', 'A saída do AND só é 1 quando A e B valem 1. Nas outras três combinações, a saída é 0.'),
(3, 1, 'Qual é a fórmula da soma ponderada do Perceptron?', 'O modelo multiplica cada entrada pelo seu peso correspondente e soma o bias: z = A·w1 + B·w2 + b.'),
(4, 1, 'O que representam os pesos w1 e w2?', 'Os pesos indicam quanto cada entrada influencia o valor de z. Um peso negativo pode inibir a ativação.'),
(5, 1, 'Qual é a função do bias (viés)?', 'O bias é somado diretamente a z. Ele também influencia a decisão quando todas as entradas são zero.'),
(6, 1, 'Na função degrau da aula, qual é a saída quando z = 0?', 'A regra apresentada é: retorna 1 se z ≥ 0 e retorna 0 se z < 0. Portanto, zero já conta como 1.'),
(7, 1, 'Um Perceptron usa w1 = 0,5, w2 = 0,5 e bias = −0,7. Qual é a previsão para A = 1 e B = 1?', 'Calculando: z = 1·0,5 + 1·0,5 − 0,7 = 0,3. Como z é maior que zero, a função degrau retorna 1.'),
(8, 1, 'Com os mesmos parâmetros do exemplo anterior, qual é a previsão para A = 0 e B = 1?', 'z = 0·0,5 + 1·0,5 − 0,7 = −0,2. Como o resultado é negativo, a previsão é 0.'),
(9, 1, 'Por que o bias não é considerado apenas mais um peso de entrada?', 'Os pesos multiplicam entradas específicas. O bias é adicionado diretamente à soma ponderada.'),
(10, 1, 'Como a aula define o erro do Perceptron?', 'A regra usada é erro = esperado − previsto. O sinal indica se o modelo respondeu abaixo ou acima do resultado esperado.'),
(11, 1, 'Se o esperado é 1 e o previsto é 0, qual é o erro?', 'O erro é 1 − 0 = +1. Isso indica que o modelo respondeu baixo demais.'),
(12, 1, 'Se o esperado é 0 e o previsto é 1, o que o erro negativo indica?', 'O erro é 0 − 1 = −1, indicando que a previsão ficou acima do resultado esperado.'),
(13, 1, 'O que acontece com os parâmetros quando o Perceptron acerta um exemplo?', 'Quando esperado e previsto são iguais, o erro é zero. Assim, as atualizações calculadas pela regra também são zero.'),
(14, 1, 'Qual é o papel da taxa de aprendizado η?', 'A taxa de aprendizado escala as atualizações. Uma taxa maior não garante, por si só, treinamento mais rápido ou melhor.'),
(15, 1, 'Na atualização do peso w1, por que o valor da entrada A aparece na fórmula?', 'A atualização é w1 ← w1 + η·erro·A. Se A for zero, a atualização desse peso nesse exemplo será zero.'),
(16, 1, 'Se A = 0 durante um exemplo, o que ocorre com a atualização de w1?', 'Como a fórmula multiplica a atualização por A, uma entrada A igual a zero faz com que w1 não mude naquele exemplo.'),
(17, 1, 'O bias pode ser atualizado quando A = 0 e B = 0?', 'A atualização do bias é b ← b + η·erro. Por isso, ele pode mudar mesmo quando ambas as entradas são zero.'),
(18, 1, 'O que é uma época no treinamento descrito?', 'Uma época percorre todos os exemplos uma vez. No código, o laço externo controla as épocas e o interno percorre os dados.'),
(19, 1, 'No código da aula, quantas épocas são executadas?', 'O código utiliza for epoca in range(20), portanto realiza 20 passagens completas pelos exemplos.'),
(20, 1, 'Em que formato os exemplos AND são armazenados na lista dados?', 'Cada item tem o formato ([A, B], resultado esperado), permitindo que o programa separe as entradas do alvo correto.'),
(21, 1, 'Qual é a responsabilidade da função prever(a, b) no código?', 'A função calcula a soma ponderada com os parâmetros atuais e retorna 1 ou 0 conforme a função degrau.'),
(22, 1, 'Por que o treinamento é repetido em várias épocas?', 'Uma atualização pode alterar a classificação de outros exemplos. Repetir os dados permite que o modelo ajuste os parâmetros progressivamente.'),
(23, 1, 'Segundo os resultados apresentados, em qual época o Perceptron chega a 4 acertos de 4?', 'A tabela mostra que ao final da terceira época o modelo classifica corretamente os quatro exemplos do AND.'),
(24, 1, 'Depois de atingir 4 acertos, por que os parâmetros deixam de mudar nos exemplos apresentados?', 'Com erro zero, as fórmulas de atualização não alteram os pesos nem o bias para esses exemplos.'),
(25, 1, 'O que significa acurácia de treinamento?', 'A acurácia de treinamento mede os acertos nos dados de treinamento, não necessariamente o desempenho em dados novos.'),
(26, 1, 'O que é generalização?', 'Generalização é a capacidade de funcionar bem em dados novos. A aula destaca que acertar os dados de treinamento não comprova isso.'),
(27, 1, 'Por que 4 acertos nos quatro exemplos AND não provam, sozinhos, que o modelo generaliza?', 'Avaliar apenas os exemplos usados no treinamento não mostra como o modelo se comportará diante de entradas novas.'),
(28, 1, 'O que acontece quando o Perceptron simples é treinado com os exemplos XOR?', 'O XOR não é linearmente separável. Uma única fronteira linear, como a de um Perceptron simples, não consegue separar corretamente suas classes.'),
(29, 1, 'Qual é a principal diferença entre o Perceptron com degrau e o neurônio com Sigmoid citado ao final da aula?', 'A aula contrasta a função degrau, que decide entre 0 e 1, com a Sigmoid, cuja saída é suave e permite o treinamento por gradientes estudado na Aula 02.'),
(30, 1, 'Qual conclusão é mais adequada sobre o aprendizado do Perceptron?', 'O Perceptron aprende corrigindo parâmetros a partir dos exemplos, mas sua fronteira linear limita os problemas que consegue representar.'),
(31, 2, 'Qual problema é usado na Aula 02 para mostrar uma limitação do Perceptron simples?', 'A aula usa o XOR porque suas classes não podem ser separadas por uma única fronteira linear.'),
(32, 2, 'Qual é a saída da operação XOR quando A = 0 e B = 0?', 'XOR retorna 1 quando as entradas são diferentes. Como 0 e 0 são iguais, a saída é 0.'),
(33, 2, 'Em quais combinações o XOR retorna 1?', 'O XOR é o OU exclusivo: retorna 1 para (0,1) e (1,0), pois as entradas são diferentes.'),
(34, 2, 'Por que um único Perceptron não consegue resolver corretamente o XOR?', 'Um Perceptron simples cria uma fronteira linear. As combinações positivas e negativas do XOR não podem ser separadas corretamente por uma única reta.'),
(35, 2, 'Qual arquitetura é apresentada para aprender XOR?', 'A rede possui duas entradas, dois neurônios na camada oculta e um neurônio na saída, formando a arquitetura 2–2–1.'),
(36, 2, 'Na arquitetura 2–2–1, quantos neurônios existem na camada oculta?', 'O número do meio na arquitetura 2–2–1 representa os dois neurônios da camada oculta.'),
(37, 2, 'Qual é a função dos neurônios da camada oculta?', 'Cada neurônio oculto combina as entradas e produz uma representação intermediária que será usada pelo neurônio de saída.'),
(38, 2, 'Na demonstração manual, como N1 e N2 são definidos?', 'A aula demonstra uma composição: N1 calcula OR(A,B), enquanto N2 calcula AND(A,B).'),
(39, 2, 'Na demonstração manual, quando N3 deve ser ativado?', 'O XOR pode ser obtido ativando a saída quando OR é 1 e AND é 0: existe pelo menos uma entrada 1, mas não as duas.'),
(40, 2, 'Por que várias camadas sem ativação não linear não resolvem, por si só, o XOR?', 'Sem não linearidade, a composição de transformações afins continua sendo uma transformação afim. A profundidade sozinha não cria a fronteira necessária.'),
(41, 2, 'Qual é a expressão matemática da função Sigmoid?', 'A Sigmoid é definida por 1 dividido por (1 + e elevado a −z), produzindo uma saída suave entre 0 e 1.'),
(42, 2, 'Quais valores podem ser retornados pela Sigmoid?', 'A Sigmoid transforma o valor z em um número entre 0 e 1, sem se limitar apenas aos extremos.'),
(43, 2, 'Qual é aproximadamente o valor da Sigmoid quando z = 0?', 'Substituindo z = 0, temos 1/(1 + e⁰) = 1/(1+1) = 0,5.'),
(44, 2, 'Qual é a derivada da Sigmoid apresentada na aula?', 'A derivada tem a forma conveniente σ(z) multiplicada por 1 − σ(z). Ela é usada nos cálculos dos gradientes.'),
(45, 2, 'Por que a Sigmoid é útil para o treinamento por gradientes?', 'A diferenciabilidade permite calcular como pequenas mudanças nos parâmetros afetam a saída e a perda.'),
(46, 2, 'A saída da Sigmoid deve ser interpretada automaticamente como uma probabilidade calibrada?', 'A aula alerta que estar entre 0 e 1 não garante que a saída represente uma probabilidade calibrada de acerto.'),
(47, 2, 'Qual função de perda é utilizada na aula?', 'A aula escolhe a perda quadrática, que mede o desvio entre o resultado esperado t e a previsão y.'),
(48, 2, 'Na fórmula da perda, o que representam t e y?', 'A notação usada define t como o alvo esperado e y como a saída prevista pela rede.'),
(49, 2, 'Qual é a derivada da perda quadrática em relação à previsão y?', 'Para L = 1/2(t−y)², a derivada em relação a y é y−t. O fator 1/2 simplifica a derivada.'),
(50, 2, 'O que representa um gradiente?', 'O gradiente indica como a perda varia localmente quando um parâmetro muda, ajudando a decidir como ajustá-lo.'),
(51, 2, 'Como o Gradient Descent atualiza um peso?', 'O parâmetro é movido contra a direção do gradiente, multiplicando-o pela taxa de aprendizado η.'),
(52, 2, 'O que o Backpropagation faz?', 'O Backpropagation calcula como o erro da saída depende dos parâmetros das camadas anteriores, usando a regra da cadeia.'),
(53, 2, 'Na expressão δ3 = (n3 − t) · n3 · (1 − n3), o que é n3?', 'n3 representa a saída do neurônio final, calculada pela Sigmoid. Ela participa do cálculo do delta da saída.'),
(54, 2, 'Por que os deltas de todas as camadas devem ser calculados antes de atualizar os pesos?', 'Os deltas devem corresponder à mesma previsão e ao mesmo conjunto de pesos. Atualizar antes de calcular os demais pode misturar estados diferentes.'),
(55, 2, 'O que é uma seed no contexto da inicialização aleatória?', 'random.seed(3), por exemplo, faz o script repetir a mesma sequência pseudoaleatória, facilitando reproduzir um experimento.'),
(56, 2, 'A seed é um parâmetro aprendido pela rede?', 'A seed determina a sequência de números pseudoaleatórios. Ela não é ajustada pelo treinamento como os pesos e os bias.'),
(57, 2, 'Por que é importante inicializar os neurônios ocultos com valores diferentes?', 'Se os neurônios começarem idênticos, podem receber gradientes idênticos e continuar iguais. Inicializações diferentes quebram essa simetria.'),
(58, 2, 'O que pode acontecer quando a Sigmoid fica muito próxima de 0 ou 1?', 'Em regiões saturadas, a derivada da Sigmoid fica próxima de zero. Isso pode produzir gradientes pequenos, fenômeno relacionado a vanishing gradients.'),
(59, 2, 'O que o método Xavier/Glorot considera para definir a escala dos pesos iniciais?', 'O limite do Xavier uniforme é calculado por √(6/(fan_in + fan_out)), usando o número de conexões de entrada e de saída.'),
(60, 2, 'Qual conclusão foi obtida na comparação de 20 seeds entre inicialização uniforme e Xavier?', 'Nos testes apresentados, o método uniforme teve 80% de sucesso e Xavier 75%. A diferença é pequena e não demonstra superioridade universal.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `subtitulos`
--

CREATE TABLE `subtitulos` (
  `id_subtitulo` int(11) NOT NULL,
  `id_conteudo` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `conteudo` text NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `subtitulos`
--

INSERT INTO `subtitulos` (`id_subtitulo`, `id_conteudo`, `titulo`, `conteudo`, `ordem`) VALUES
(1, 1, 'Introdução', '> **Projeto:** ZeroToAI · **Linguagem:** Python puro · **Pré-requisitos:** variáveis, funções e laços `for`.\n\nNesta aula você constrói, do zero e sem nenhuma biblioteca, o modelo de aprendizado de máquina mais simples que existe: o **Perceptron**. Ele aprende a operação lógica **AND** olhando apenas para exemplos, sem que ninguém escreva a regra por ele.', 1),
(2, 1, 'Objetivos', 'Ao terminar, você deverá compreender:\n\n- o cálculo de uma previsão com **pesos** e **bias (viés)**;\n- a função de ativação **degrau**;\n- a diferença entre **previsão**, **erro**, **época** e **taxa de aprendizado**;\n- a **regra de aprendizado** do Perceptron;\n- por que acertar os dados de treinamento **não prova generalização**.\n\n> **Como estudar esta aula:** leia as seções em ordem, refaça à mão os cálculos das seções 2 e 3 e só depois execute o código da seção 4.', 2),
(3, 1, '1. O problema: ensinar o AND', 'O primeiro modelo aprende a operação lógica **AND** (E): a saída só é `1` quando **ambas** as entradas são `1`.\n\n| A | B | Saída esperada |\n| :-: | :-: | :-: |\n| 0 | 0 | 0 |\n| 0 | 1 | 0 |\n| 1 | 0 | 0 |\n| 1 | 1 | 1 |\n\n> **Ideia central:** não vamos programar a resposta com `if a == 1 and b == 1`. Queremos que o modelo **ajuste seus próprios parâmetros** a partir dos exemplos.', 3),
(4, 1, '2. Anatomia do Perceptron', 'Um Perceptron faz duas coisas: **soma** as entradas ponderadas e **decide** se responde `0` ou `1`.\n\n### A soma ponderada\n\n$$ z = A \\cdot w_1 + B \\cdot w_2 + b $$\n\n| Termo | Nome | O que faz |\n| --- | --- | --- |\n| `A`, `B` | Entradas | Informações fornecidas ao modelo |\n| `w1`, `w2` | Pesos | Indicam como cada entrada influencia a soma; valores negativos podem inibir a ativação |\n| `b` | Bias (viés) | Desloca o limiar da decisão, mesmo quando as entradas são zero |\n| `z` | Soma ponderada | Valor calculado antes da ativação |\n\n### A função degrau\n\n$$ f(z) = \\begin{cases} 1, & \\text{se } z \\geq 0 \\\\ 0, & \\text{se } z < 0 \\end{cases} $$\n\nEla converte `z` em uma classe: `0` ou `1`. Repare que `z = 0` **já conta como `1`**.\n\n### Exemplo com números\n\nCom `w1 = 0.5`, `w2 = 0.5` e `bias = -0.7`:\n\n| A | B | Conta | z | Previsão |\n| :-: | :-: | --- | :-: | :-: |\n| 0 | 0 | `0·0.5 + 0·0.5 - 0.7` | `-0.7` | `0` |\n| 0 | 1 | `0·0.5 + 1·0.5 - 0.7` | `-0.2` | `0` |\n| 1 | 0 | `1·0.5 + 0·0.5 - 0.7` | `-0.2` | `0` |\n| 1 | 1 | `1·0.5 + 1·0.5 - 0.7` | `0.3` | `1` |\n\nEsses parâmetros reproduzem o AND: só a última linha dispara.\n\n> **Tente você:** troque o bias para `-0.4` e refaça a tabela. Ainda é um AND?\n\n### O bias não é outro peso da mesma entrada\n\nOs pesos multiplicam entradas específicas. O bias é somado **diretamente**. Quando `A = B = 0`, a soma vale `z = bias`: ele ajusta a fronteira de decisão independentemente das entradas.', 4),
(5, 1, '3. Como o Perceptron aprende', 'Aprender, aqui, significa **corrigir os parâmetros sempre que o modelo erra**.\n\n### Passo 1: calcular o erro\n\n$$ erro = esperado - previsto $$\n\n| esperado | previsto | erro | Significado |\n| :-: | :-: | :-: | --- |\n| `1` | `0` | `+1` | o modelo respondeu baixo demais |\n| `0` | `1` | `-1` | o modelo respondeu alto demais |\n| igual | igual | `0` | o modelo acertou |\n\n### Passo 2: atualizar os parâmetros\n\n$$ \\begin{aligned} w_1 &\\leftarrow w_1 + \\eta \\cdot erro \\cdot A \\\\ w_2 &\\leftarrow w_2 + \\eta \\cdot erro \\cdot B \\\\ b &\\leftarrow b + \\eta \\cdot erro \\end{aligned} $$\n\nA letra **η (eta)** representa a **taxa de aprendizado**. Ela controla o tamanho das correções, e **não** garante, por si só, um treinamento mais rápido ou melhor.\n\nO que essa regra implica:\n\n- Se a previsão está correta, `erro = 0`: nenhum parâmetro muda nesse exemplo.\n- Se `A = 0`, a atualização do peso associado a `A` é zero.\n- O bias pode mudar mesmo com `A = B = 0`.\n- Corrigir um exemplo pode, temporariamente, prejudicar outro; por isso repetimos o treinamento.\n\n### Cálculo manual\n\nPeso inicial `0.5`, taxa `0.2`, erro `-1` e entrada `1`:\n\n$$ w_{novo} = 0.5 + 0.2 \\cdot (-1) \\cdot 1 = 0.3 $$\n\nO erro negativo **diminuiu** o peso: o modelo havia respondido alto demais.', 5),
(6, 1, '4. O código: perceptron.py', '```python\n# Exemplos AND: ([A, B], resultado esperado)\ndados = [\n    ([0, 0], 0),\n    ([0, 1], 0),\n    ([1, 0], 0),\n    ([1, 1], 1),\n]\n\npeso1 = 0.0\npeso2 = 0.0\nbias = 0.0\ntaxa_aprendizado = 0.2\n\n\ndef prever(a, b):\n    z = a * peso1 + b * peso2 + bias\n    return 1 if z >= 0 else 0\n\n\n# Uma época percorre TODOS os exemplos uma vez.\nfor epoca in range(20):\n    for entradas, esperado in dados:\n        a, b = entradas\n        previsao = prever(a, b)\n        erro = esperado - previsao\n\n        peso1 += taxa_aprendizado * erro * a\n        peso2 += taxa_aprendizado * erro * b\n        bias += taxa_aprendizado * erro\n\n\nprint(\"Resultados após o treinamento:\")\nfor entradas, esperado in dados:\n    a, b = entradas\n    print(f\"{entradas} | esperado={esperado} | previsto={prever(a, b)}\")\n\nprint(f\"Pesos: {peso1:.2f}, {peso2:.2f} | Bias: {bias:.2f}\")\n```\n\nPara executar, abra o terminal na pasta da aula:\n\n```\npython perceptron.py\n```\n\nNo Windows, se `python` não funcionar, tente `py perceptron.py`.\n\n### Lendo o código por blocos\n\n| Bloco | O que faz |\n| --- | --- |\n| `dados` | Os quatro exemplos do AND, no formato `([A, B], esperado)` |\n| `peso1`, `peso2`, `bias` | Parâmetros iniciais, todos em `0.0` |\n| `taxa_aprendizado` | O η da regra de aprendizado, aqui `0.2` |\n| `prever(a, b)` | Calcula `z` e aplica o degrau |\n| `for epoca in range(20)` | Repete o treinamento por 20 épocas |\n| `erro = esperado - previsao` | Mede o quanto o modelo errou neste exemplo |\n| linhas com `+=` | Aplicam a regra de atualização da seção 3 |\n| `print(...)` | Mostra esperado × previsto e os parâmetros finais |\n\nRepare que a linha do bias **não multiplica por nenhuma entrada**: o bias é somado diretamente.', 6),
(7, 1, '5. Acompanhando o treinamento passo a passo', 'Com os parâmetros todos em `0.0`, o primeiro `z` vale `0` e o degrau responde `1` para **qualquer** entrada. Veja a primeira época:\n\n| Exemplo | Esperado | Previsto | Erro | `peso1` | `peso2` | `bias` |\n| :-: | :-: | :-: | :-: | :-: | :-: | :-: |\n| `[0, 0]` | `0` | `1` | `-1` | `0.0` | `0.0` | `-0.2` |\n| `[0, 1]` | `0` | `0` | `0` | `0.0` | `0.0` | `-0.2` |\n| `[1, 0]` | `0` | `0` | `0` | `0.0` | `0.0` | `-0.2` |\n| `[1, 1]` | `1` | `0` | `+1` | `0.2` | `0.2` | `0.0` |\n\nAo longo das épocas, os parâmetros evoluem assim:\n\n| Fim da época | `peso1` | `peso2` | `bias` | Acertos |\n| :-: | :-: | :-: | :-: | :-: |\n| 1 | `0.2` | `0.2` | `0.0` | 1 de 4 |\n| 2 | `0.4` | `0.2` | `-0.2` | 2 de 4 |\n| 3 | `0.4` | `0.2` | `-0.4` | 4 de 4 |\n| 4 em diante | `0.4` | `0.2` | `-0.4` | 4 de 4 (nada mais muda) |\n\nA saída impressa pelo programa é:\n\n```\nResultados após o treinamento:\n[0, 0] | esperado=0 | previsto=0\n[0, 1] | esperado=0 | previsto=0\n[1, 0] | esperado=0 | previsto=0\n[1, 1] | esperado=1 | previsto=1\nPesos: 0.40, 0.20 | Bias: -0.40\n```', 7),
(8, 1, '6. Interpretando os resultados', 'O modelo aprende a classificar corretamente as quatro combinações do AND. Isso significa que ele encontrou uma configuração útil de pesos e bias para **esses exemplos**.\n\nÉ importante distinguir três ideias:\n\n- **Época:** passagem completa por todos os exemplos.\n- **Acurácia de treinamento:** fração das respostas corretas nesses exemplos.\n- **Generalização:** desempenho em exemplos **novos**; não pode ser demonstrada apenas testando os mesmos dados usados no treinamento.\n\n> **Um detalhe de ponto flutuante:** ao final, `peso1 = 0.4`, `peso2 = 0.2` e `bias` é aproximadamente `-0.4`. Para `A = 1` e `B = 0`, `z = 0.4 - 0.4`, que na matemática exata vale `0` e faria o degrau responder `1` (errado!). No Python, o bias acumulado fica em `-0.4000000000000001` por causa do arredondamento, `z` vira `-5.55e-17` e a previsão sai `0`. A solução encontrada está **exatamente na fronteira** de decisão: o Perceptron para de corrigir assim que acerta e não procura nenhuma folga de segurança.\n\nO Perceptron com ativação degrau é diferente de um neurônio com **Sigmoid** treinado por gradientes, que você vai estudar na Aula 02.', 8),
(9, 1, '7. Experimentos opcionais', '1. **Menos épocas.** Troque `range(20)` por `range(1)` e compare. *Observado:* com uma única época, só 1 dos 4 exemplos é classificado corretamente.\n2. **Outras taxas.** Experimente `taxa_aprendizado = 0.01` e `1.0` e registre se o resultado muda. *Observado:* nos dois casos o modelo chega a 4 de 4; mudam apenas os valores finais (com `1.0`, por exemplo, `2.0`, `1.0` e `-3.0`).\n3. **Lógica OR.** Modifique os dados para `([0,0],0), ([0,1],1), ([1,0],1), ([1,1],1)` e veja se o modelo consegue aprender. *Observado:* ele aprende (4 de 4).\n4. **Lógica XOR.** Use os dados `([0,0],0), ([0,1],1), ([1,0],1), ([1,1],0)`. *Observado:* o modelo nunca passa de 2 de 4 acertos, mesmo com centenas de épocas. Um único Perceptron não consegue acertar os quatro simultaneamente: a operação **não é linearmente separável**.', 9),
(10, 1, '8. Resumo', '| Conceito | Papel |\n| --- | --- |\n| Peso | Influência de uma entrada sobre `z` |\n| Bias | Deslocamento do limiar de ativação |\n| Função degrau | Converte `z` em classe `0` ou `1` |\n| Erro | Diferença `esperado - previsto` |\n| Taxa de aprendizado | Escala a atualização dos parâmetros |\n| Época | Uma passagem sobre todos os dados |\n\n> **Próxima aula:** múltiplos neurônios, XOR, Sigmoid, derivadas, Backpropagation e comparação de inicializações.', 10),
(11, 2, 'Introdução', '> **Projeto:** ZeroToAI · **Linguagem:** Python puro · **Pré-requisito:** Aula 01 (Perceptron).\n\nNa Aula 01, um único neurônio aprendeu o AND. Nesta aula vamos além: construímos uma pequena **rede neural** que aprende a operação **XOR** sem que ninguém defina os pesos à mão. Para isso você vai conhecer a **Sigmoid**, a **perda**, os **gradientes** e o algoritmo que faz a rede aprender: o **Backpropagation**.', 1),
(12, 2, 'Objetivos', 'Ao terminar, você deverá compreender:\n\n- por que um Perceptron não resolve o XOR;\n- camadas ocultas e não linearidade;\n- a Sigmoid e a sua derivada;\n- função de perda, gradientes, regra da cadeia e **Backpropagation**;\n- o **Gradient Descent**;\n- inicialização aleatória, simetria, **Xavier/Glorot** e reprodutibilidade;\n- como comparar treinamentos de forma experimental.\n\n> **Como estudar esta aula:** as seções 1 a 4 são a teoria; as seções 5 a 7 colocam a teoria em código e em experimentos; a seção 8 lista erros reais que você provavelmente vai encontrar.', 2),
(13, 2, '1. XOR e a limitação de um Perceptron', 'A operação **XOR** (OU exclusivo) retorna `1` quando as duas entradas são **diferentes**.\n\n| A | B | XOR |\n| :-: | :-: | :-: |\n| 0 | 0 | 0 |\n| 0 | 1 | 1 |\n| 1 | 0 | 1 |\n| 1 | 1 | 0 |\n\nUm Perceptron decide com `z = A*w1 + B*w2 + bias` e um limiar. Essa decisão estabelece uma **fronteira linear**: os exemplos do XOR não podem ser separados corretamente por uma única reta.\n\n### Por que é impossível? A prova algébrica\n\nPara acertar as quatro linhas da tabela, os parâmetros precisariam satisfazer **ao mesmo tempo**:\n\n$$ \\begin{aligned} b &< 0 \\\\ w_2 + b &\\geq 0 \\\\ w_1 + b &\\geq 0 \\\\ w_1 + w_2 + b &< 0 \\end{aligned} $$\n\nAs duas desigualdades do meio implicam `w1 + w2 + 2b >= 0`. Como `b < 0`, isso implica `w1 + w2 + b > 0`, o que **contradiz** a última condição. Logo, nenhum conjunto de pesos e bias resolve o XOR com um único neurônio.', 3),
(14, 2, '2. A arquitetura 2–2–1', 'Usamos **duas entradas**, **dois neurônios ocultos** e **um neurônio de saída**:\n\n```\nEntradas     Camada oculta     Saída\n\n  A ──┬──► N1 ──┐\n      │         ├──► N3 ──► previsão\n  B ──┴──► N2 ──┘\n\n(cada entrada alimenta tanto N1 quanto N2)\n```\n\nCada neurônio oculto combina as entradas; o terceiro combina as representações intermediárias.\n\n### Demonstração manual\n\nPodemos montar o XOR a partir de OR e AND:\n\n- `N1 = OR(A, B)`\n- `N2 = AND(A, B)`\n- `N3` ativa quando `N1 = 1` **e** `N2 = 0`.\n\nIsso mostra que vários neurônios conseguem **combinar saídas** para resolver o que um só não resolve. Mas aqui definimos os pesos à mão. Para que a rede **aprenda** os pesos sozinha, precisamos de um procedimento de treinamento.\n\n### Por que a ativação não linear importa?\n\nVárias camadas que fazem somente somas ponderadas e bias podem ser compostas em **uma única transformação afim**. Sem não linearidade nas camadas ocultas, a profundidade sozinha não resolve o XOR.\n\n> **Resumo da seção:** camadas ocultas **mais** uma ativação não linear permitem representar o que um Perceptron simples não representa.', 4),
(15, 2, '3. A função Sigmoid', 'Para treinar com gradientes, precisamos de uma ativação suave. A **Sigmoid** é:\n\n$$ \\sigma(z) = \\frac{1}{1 + e^{-z}} $$\n\nEla retorna valores **entre `0` e `1`** e é **diferenciável**. Sua derivada tem uma forma muito conveniente:\n\n$$ \\sigma\'(z) = \\sigma(z)\\,\\bigl(1 - \\sigma(z)\\bigr) $$\n\n| z | Sigmoid (aprox.) |\n| :-: | :-: |\n| `-2` | `0.1192` |\n| `-1` | `0.2689` |\n| `0` | `0.5000` |\n| `1` | `0.7311` |\n| `2` | `0.8808` |\n\n> **Atenção:** o resultado da Sigmoid **não é automaticamente uma probabilidade calibrada de estar certo**. É apenas um número entre 0 e 1.\n\n### Implementação numericamente estável\n\nO cálculo direto de `math.exp(-z)` transborda quando `z` é muito negativo. A versão abaixo evita o overflow:\n\n```python\nimport math\n\ndef sigmoid(z):\n    if z >= 0:\n        return 1 / (1 + math.exp(-z))\n    exp_z = math.exp(z)\n    return exp_z / (1 + exp_z)\n```', 5),
(16, 2, '4. Perda, gradiente e regra da cadeia', '### A perda\n\nPara uma saída `y` e um resultado esperado `t`, usamos a **perda quadrática**:\n\n$$ L = \\frac{1}{2}(t - y)^2 $$\n\nSua derivada em relação à previsão é:\n\n$$ \\frac{\\partial L}{\\partial y} = y - t $$\n\n### O gradiente de um neurônio\n\nPara um único neurônio com saída `y = sigmoid(z)` e `z = entrada*w + bias`:\n\n$$ \\frac{\\partial L}{\\partial w} = (y - t)\\,y\\,(1 - y)\\,\\text{entrada} $$\n\n### Gradient Descent\n\nPara reduzir a perda, movemos cada parâmetro **contra** o gradiente:\n\n$$ w \\leftarrow w - \\eta\\,\\frac{\\partial L}{\\partial w} $$\n\n### Backpropagation\n\nO **Backpropagation** aplica a **regra da cadeia**, propagando os gradientes da saída para as camadas anteriores. Na nossa rede:\n\n$$ \\begin{aligned} \\delta_3 &= (n_3 - t)\\,n_3\\,(1 - n_3) \\\\ \\delta_1 &= \\delta_3\\,w_5\\,n_1\\,(1 - n_1) \\\\ \\delta_2 &= \\delta_3\\,w_6\\,n_2\\,(1 - n_2) \\end{aligned} $$\n\n### Exemplo numérico\n\nCom `n3 = 0.6`, `esperado = 1` e `n1 = 0.8`:\n\n- `delta3 = (0.6 - 1) * 0.6 * 0.4 = -0.096`\n- `gradiente_w5 = delta3 * n1 = -0.0768`\n- Com `w5 = 0.5` e `taxa = 0.5`: `w5_novo = 0.5 - 0.5 * (-0.0768) = 0.5384`\n\n> **Regra crucial:** calcule os deltas de **todas** as camadas **antes** de atualizar os pesos. Os gradientes devem corresponder à mesma previsão, feita com o mesmo conjunto de pesos.', 6),
(17, 2, '5. O código: rede_neural.py', 'O arquivo [`rede_neural.py`](https://github.com/IsaacFaleirosQuevedo/ZeroToAI/blob/main/Aula_02/rede_neural.py) contém a implementação completa da rede XOR 2–2–1, com **inicialização Xavier**, `random.seed(3)`, Sigmoid numericamente estável e relatório de perda a cada 1.000 épocas.\n\n```\npython rede_neural.py\n```\n\nA rede tem **9 parâmetros**: seis pesos (`w1` a `w6`) e três bias (`b1`, `b2`, `b3`). As etapas centrais do treinamento, para cada exemplo, são:\n\n```python\nn1, n2, n3 = prever(a, b)  # Forward propagation\n\ndelta3 = (n3 - esperado) * derivada_sigmoid(n3)\ndelta1 = delta3 * w5 * derivada_sigmoid(n1)\ndelta2 = delta3 * w6 * derivada_sigmoid(n2)\n\n# Todos os deltas são calculados antes da atualização.\nw5 -= taxa_aprendizado * delta3 * n1\nw6 -= taxa_aprendizado * delta3 * n2\nb3 -= taxa_aprendizado * delta3\nw1 -= taxa_aprendizado * delta1 * a\nw2 -= taxa_aprendizado * delta1 * b\nb1 -= taxa_aprendizado * delta1\nw3 -= taxa_aprendizado * delta2 * a\nw4 -= taxa_aprendizado * delta2 * b\nb2 -= taxa_aprendizado * delta2\n```\n\n> **Nota:** com `seed(3)` a rede pode ficar em uma solução ruim e classificar apenas 3 das 4 combinações do XOR. Isso é parte do experimento, **não** um erro no código. Para comparar métodos e seeds diferentes, use o `avaliacao_de_redes.py` (seção 9).', 7),
(18, 2, '6. Inicialização e reprodutibilidade', '### A seed\n\n`random.seed(3)` torna a sequência pseudoaleatória **reproduzível** no script `rede_neural.py`. A seed **não** é um parâmetro aprendido. Pesos iniciais diferentes alteram o ponto de partida do Gradient Descent.\n\n### Simetria\n\nSe os dois neurônios ocultos tiverem pesos e bias iniciais idênticos, e as conexões de saída forem igualmente inicializadas, eles receberão gradientes idênticos e continuarão aprendendo a **mesma** representação. Inicializar os pesos de maneira diferente **quebra essa simetria**.\n\n### Saturação\n\nA Sigmoid fica próxima de `0` ou `1` quando `z` tem módulo grande. Nesse caso sua derivada se aproxima de zero e os ajustes podem ficar minúsculos (*vanishing gradients*). Peso grande não é sinônimo automático de saturação: importa o `z` final.\n\n### Inicialização uniforme e Xavier\n\n- **Uniforme simples:** `random.uniform(-1, 1)`.\n- **Xavier/Glorot uniforme:** sorteia pesos em `[-limite, limite]`, com\n\n$$ limite = \\sqrt{\\frac{6}{\\text{fan\\_in} + \\text{fan\\_out}}} $$\n\nNa rede 2–2–1:\n\n| Camada | `fan_in` | `fan_out` | Limite |\n| --- | :-: | :-: | :-: |\n| Oculta | `2` | `2` | `sqrt(1.5)` ≈ `1.225` |\n| Saída | `2` | `1` | `sqrt(2)` ≈ `1.414` |\n\n> **Atenção:** o Xavier melhora a escala inicial em muitas situações, **mas não garante sucesso** nem superioridade em cada arquitetura e seed.', 8),
(19, 2, '7. Comparação experimental feita durante a aula', 'Foram testadas **20 seeds** (`0` a `19`), **30.000 épocas** e taxa `0.8`, com os pesos reinicializados a cada execução:\n\n| Indicador | Uniforme `[-1, 1]` | Xavier |\n| --- | :-: | :-: |\n| Redes com 4/4 acertos | `16/20` | `15/20` |\n| Taxa de sucesso | `80%` | `75%` |\n| Perda média final | `0.050152` | `0.062662` |\n\nAmbos os métodos falharam nas seeds `3`, `9`, `11` e `12`. O Xavier também falhou na seed `18`, em que o uniforme teve sucesso. Quando falharam, as redes frequentemente ficaram com perda perto de `0.25` e previsões próximas de `0.5` em dois casos que exigiam respostas diferentes.\n\n> **Conclusão válida:** a inicialização uniforme teve melhor resultado **nessas 20 execuções**. A diferença de um único sucesso não prova superioridade geral. Seriam necessários mais experimentos, comparando também o tempo de convergência, a distribuição da perda e a estabilidade.\n\n### Cuidados na avaliação\n\n- A avaliação deve acontecer **depois de terminar a época** (ou o treinamento), usando os pesos finais daquele momento. Se a métrica é acumulada dentro do laço que atualiza os pesos a cada exemplo, ela mistura estados diferentes do modelo.\n- Para cada seed, reinicialize **de verdade** todos os parâmetros. Trocar apenas o valor da seed, sem sortear pesos novos, não reinicia a rede.', 9),
(20, 2, '8. Erros reais encontrados e como corrigir', '### Erro 1: subtração em vez de multiplicação\n\n**Errado:**\n\n```python\ndelta1 = delta3 * w5 - n1 * (1 - n1)\ndelta2 = delta3 * w6 - n2 * (1 - n2)\n```\n\n**Correto:**\n\n```python\ndelta1 = delta3 * w5 * n1 * (1 - n1)\ndelta2 = delta3 * w6 * n2 * (1 - n2)\n```\n\nA regra da cadeia exige o **produto** das derivadas. Usar subtração muda completamente o gradiente e pode provocar crescimento indevido dos parâmetros.\n\n### Erro 2: `OverflowError: math range error`\n\nO cálculo direto de `math.exp(-z)` pode transbordar quando `z` é muito negativo. Use a implementação estável da Sigmoid da seção 3. Isso evita o erro numérico, mas **não** corrige equações de gradiente incorretas.\n\n### Erro 3: avaliar a cada época sem necessidade\n\nRepetir a avaliação completa a cada época, quando só se deseja o resultado final, desperdiça processamento. Avalie ao final do treinamento ou em intervalos definidos, se quiser acompanhar o progresso.', 10),
(21, 2, '9. Código de referência: avaliacao_de_redes.py', 'Abaixo está uma versão didática equivalente do arquivo [`avaliacao_de_redes.py`](https://github.com/IsaacFaleirosQuevedo/ZeroToAI/blob/main/Aula_02/avaliacao_de_redes.py), com um gerador pseudoaleatório independente para cada execução. O arquivo do projeto mantém os cálculos explicitamente separados por neurônio.\n\n```python\nimport math\nimport random\n\nDADOS = [([0, 0], 0), ([0, 1], 1), ([1, 0], 1), ([1, 1], 0)]\n\n\ndef sigmoid(z):\n    if z >= 0:\n        return 1 / (1 + math.exp(-z))\n    e = math.exp(z)\n    return e / (1 + e)\n\n\ndef inicializar_pesos(seed, metodo):\n    rng = random.Random(seed)\n    if metodo == \"uniforme\":\n        limite_oculta = limite_saida = 1.0\n    elif metodo == \"xavier\":\n        limite_oculta = math.sqrt(6 / (2 + 2))\n        limite_saida = math.sqrt(6 / (2 + 1))\n    else:\n        raise ValueError(\"Método inválido\")\n\n    p = [rng.uniform(-limite_oculta, limite_oculta) for _ in range(4)]\n    p += [rng.uniform(-limite_saida, limite_saida) for _ in range(2)]\n    return p + [0.0, 0.0, 0.0]\n\n\ndef treinar_rede(seed, metodo, epocas=30000, taxa=0.8):\n    w1, w2, w3, w4, w5, w6, b1, b2, b3 = inicializar_pesos(seed, metodo)\n\n    def prever(a, b):\n        n1 = sigmoid(a * w1 + b * w2 + b1)\n        n2 = sigmoid(a * w3 + b * w4 + b2)\n        n3 = sigmoid(n1 * w5 + n2 * w6 + b3)\n        return n1, n2, n3\n\n    for _ in range(epocas):\n        for (a, b), esperado in DADOS:\n            n1, n2, n3 = prever(a, b)\n            delta3 = (n3 - esperado) * n3 * (1 - n3)\n            delta1 = delta3 * w5 * n1 * (1 - n1)\n            delta2 = delta3 * w6 * n2 * (1 - n2)\n\n            w5 -= taxa * delta3 * n1\n            w6 -= taxa * delta3 * n2\n            b3 -= taxa * delta3\n            w1 -= taxa * delta1 * a\n            w2 -= taxa * delta1 * b\n            b1 -= taxa * delta1\n            w3 -= taxa * delta2 * a\n            w4 -= taxa * delta2 * b\n            b2 -= taxa * delta2\n\n    perda = 0.0\n    acertos = 0\n    for (a, b), esperado in DADOS:\n        _, _, previsto = prever(a, b)\n        perda += 0.5 * (esperado - previsto) ** 2\n        acertos += int((previsto >= 0.5) == bool(esperado))\n    return perda, acertos\n\n\nif __name__ == \"__main__\":\n    for metodo in (\"uniforme\", \"xavier\"):\n        sucessos = 0\n        soma_perdas = 0.0\n        print(f\"\\nMétodo: {metodo.upper()}\")\n        for seed in range(20):\n            perda, acertos = treinar_rede(seed, metodo)\n            sucessos += int(acertos == 4)\n            soma_perdas += perda\n            print(f\"Seed {seed:02d} | Perda: {perda:.6f} | Acertos: {acertos}/4\")\n        print(f\"Taxa de sucesso: {sucessos / 20:.1%}\")\n        print(f\"Perda média: {soma_perdas / 20:.6f}\")\n```', 11),
(22, 2, '10. Resumo e próximos passos', '| Conceito | O que aprendemos |\n| --- | --- |\n| XOR | Não é linearmente separável |\n| Camada oculta | Constrói representações intermediárias |\n| Sigmoid | Ativação não linear e diferenciável |\n| Perda | Mede o desvio das previsões |\n| Gradiente | Mede a variação local da perda em relação aos parâmetros |\n| Backpropagation | Aplica a regra da cadeia às camadas |\n| Gradient Descent | Atualiza os parâmetros na direção de reduzir a perda |\n| Seed | Controla a sequência pseudoaleatória |\n| Xavier | Controla a escala inicial usando `fan_in` e `fan_out` |', 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tag`
--

CREATE TABLE `tag` (
  `id_tag` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tag`
--

INSERT INTO `tag` (`id_tag`, `tag`) VALUES
(1, 'Iniciante'),
(5, 'Intermediario'),
(2, 'Matematica'),
(3, 'Python'),
(4, 'XOR');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tentativas`
--

CREATE TABLE `tentativas` (
  `id_tentativa` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_card` int(11) DEFAULT NULL,
  `acertos` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `data_tentativa` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tentativa_respostas`
--

CREATE TABLE `tentativa_respostas` (
  `id_tentativa` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `id_alternativa` int(11) DEFAULT NULL,
  `correta` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alternativas`
--
ALTER TABLE `alternativas`
  ADD PRIMARY KEY (`id_alternativa`),
  ADD KEY `id_pergunta` (`id_pergunta`);

--
-- Índices de tabela `aula_tag`
--
ALTER TABLE `aula_tag`
  ADD PRIMARY KEY (`id_card`,`id_tag`),
  ADD KEY `id_tag` (`id_tag`);

--
-- Índices de tabela `card_aulas`
--
ALTER TABLE `card_aulas`
  ADD PRIMARY KEY (`id_card`);

--
-- Índices de tabela `conteudos`
--
ALTER TABLE `conteudos`
  ADD PRIMARY KEY (`id_conteudo`),
  ADD UNIQUE KEY `id_card` (`id_card`);

--
-- Índices de tabela `perguntas`
--
ALTER TABLE `perguntas`
  ADD PRIMARY KEY (`id_pergunta`),
  ADD KEY `id_conteudo` (`id_conteudo`);

--
-- Índices de tabela `subtitulos`
--
ALTER TABLE `subtitulos`
  ADD PRIMARY KEY (`id_subtitulo`),
  ADD KEY `id_conteudo` (`id_conteudo`);

--
-- Índices de tabela `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id_tag`),
  ADD UNIQUE KEY `tag` (`tag`);

--
-- Índices de tabela `tentativas`
--
ALTER TABLE `tentativas`
  ADD PRIMARY KEY (`id_tentativa`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_card` (`id_card`);

--
-- Índices de tabela `tentativa_respostas`
--
ALTER TABLE `tentativa_respostas`
  ADD PRIMARY KEY (`id_tentativa`,`id_pergunta`),
  ADD KEY `id_pergunta` (`id_pergunta`),
  ADD KEY `id_alternativa` (`id_alternativa`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alternativas`
--
ALTER TABLE `alternativas`
  MODIFY `id_alternativa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT de tabela `card_aulas`
--
ALTER TABLE `card_aulas`
  MODIFY `id_card` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `conteudos`
--
ALTER TABLE `conteudos`
  MODIFY `id_conteudo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `perguntas`
--
ALTER TABLE `perguntas`
  MODIFY `id_pergunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `subtitulos`
--
ALTER TABLE `subtitulos`
  MODIFY `id_subtitulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tag`
--
ALTER TABLE `tag`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tentativas`
--
ALTER TABLE `tentativas`
  MODIFY `id_tentativa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alternativas`
--
ALTER TABLE `alternativas`
  ADD CONSTRAINT `alternativas_ibfk_1` FOREIGN KEY (`id_pergunta`) REFERENCES `perguntas` (`id_pergunta`) ON DELETE CASCADE;

--
-- Restrições para tabelas `aula_tag`
--
ALTER TABLE `aula_tag`
  ADD CONSTRAINT `aula_tag_ibfk_1` FOREIGN KEY (`id_card`) REFERENCES `card_aulas` (`id_card`) ON DELETE CASCADE,
  ADD CONSTRAINT `aula_tag_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON DELETE CASCADE;

--
-- Restrições para tabelas `conteudos`
--
ALTER TABLE `conteudos`
  ADD CONSTRAINT `conteudos_ibfk_1` FOREIGN KEY (`id_card`) REFERENCES `card_aulas` (`id_card`) ON DELETE CASCADE;

--
-- Restrições para tabelas `perguntas`
--
ALTER TABLE `perguntas`
  ADD CONSTRAINT `perguntas_ibfk_1` FOREIGN KEY (`id_conteudo`) REFERENCES `conteudos` (`id_conteudo`) ON DELETE CASCADE;

--
-- Restrições para tabelas `subtitulos`
--
ALTER TABLE `subtitulos`
  ADD CONSTRAINT `subtitulos_ibfk_1` FOREIGN KEY (`id_conteudo`) REFERENCES `conteudos` (`id_conteudo`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tentativas`
--
ALTER TABLE `tentativas`
  ADD CONSTRAINT `tentativas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `tentativas_ibfk_2` FOREIGN KEY (`id_card`) REFERENCES `card_aulas` (`id_card`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tentativa_respostas`
--
ALTER TABLE `tentativa_respostas`
  ADD CONSTRAINT `tentativa_respostas_ibfk_1` FOREIGN KEY (`id_tentativa`) REFERENCES `tentativas` (`id_tentativa`) ON DELETE CASCADE,
  ADD CONSTRAINT `tentativa_respostas_ibfk_2` FOREIGN KEY (`id_pergunta`) REFERENCES `perguntas` (`id_pergunta`) ON DELETE CASCADE,
  ADD CONSTRAINT `tentativa_respostas_ibfk_3` FOREIGN KEY (`id_alternativa`) REFERENCES `alternativas` (`id_alternativa`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
