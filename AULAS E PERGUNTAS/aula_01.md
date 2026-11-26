# Perceptron: como um neurônio aprende

> **Projeto:** ZeroToAI · **Linguagem:** Python puro · **Pré-requisitos:** variáveis, funções e laços `for`.

Nesta aula você constrói, do zero e sem nenhuma biblioteca, o modelo de aprendizado de máquina mais simples que existe: o **Perceptron**. Ele aprende a operação lógica **AND** olhando apenas para exemplos, sem que ninguém escreva a regra por ele.

## Objetivos

Ao terminar, você deverá compreender:

- o cálculo de uma previsão com **pesos** e **bias (viés)**;
- a função de ativação **degrau**;
- a diferença entre **previsão**, **erro**, **época** e **taxa de aprendizado**;
- a **regra de aprendizado** do Perceptron;
- por que acertar os dados de treinamento **não prova generalização**.

> **Como estudar esta aula:** leia as seções em ordem, refaça à mão os cálculos das seções 2 e 3 e só depois execute o código da seção 4.

## 1. O problema: ensinar o AND

O primeiro modelo aprende a operação lógica **AND** (E): a saída só é `1` quando **ambas** as entradas são `1`.

| A | B | Saída esperada |
| :-: | :-: | :-: |
| 0 | 0 | 0 |
| 0 | 1 | 0 |
| 1 | 0 | 0 |
| 1 | 1 | 1 |

> **Ideia central:** não vamos programar a resposta com `if a == 1 and b == 1`. Queremos que o modelo **ajuste seus próprios parâmetros** a partir dos exemplos.

## 2. Anatomia do Perceptron

Um Perceptron faz duas coisas: **soma** as entradas ponderadas e **decide** se responde `0` ou `1`.

### A soma ponderada

$$ z = A \cdot w_1 + B \cdot w_2 + b $$

| Termo | Nome | O que faz |
| --- | --- | --- |
| `A`, `B` | Entradas | Informações fornecidas ao modelo |
| `w1`, `w2` | Pesos | Indicam como cada entrada influencia a soma; valores negativos podem inibir a ativação |
| `b` | Bias (viés) | Desloca o limiar da decisão, mesmo quando as entradas são zero |
| `z` | Soma ponderada | Valor calculado antes da ativação |

### A função degrau

$$ f(z) = \begin{cases} 1, & \text{se } z \geq 0 \\ 0, & \text{se } z < 0 \end{cases} $$

Ela converte `z` em uma classe: `0` ou `1`. Repare que `z = 0` **já conta como `1`**.

### Exemplo com números

Com `w1 = 0.5`, `w2 = 0.5` e `bias = -0.7`:

| A | B | Conta | z | Previsão |
| :-: | :-: | --- | :-: | :-: |
| 0 | 0 | `0·0.5 + 0·0.5 - 0.7` | `-0.7` | `0` |
| 0 | 1 | `0·0.5 + 1·0.5 - 0.7` | `-0.2` | `0` |
| 1 | 0 | `1·0.5 + 0·0.5 - 0.7` | `-0.2` | `0` |
| 1 | 1 | `1·0.5 + 1·0.5 - 0.7` | `0.3` | `1` |

Esses parâmetros reproduzem o AND: só a última linha dispara.

> **Tente você:** troque o bias para `-0.4` e refaça a tabela. Ainda é um AND?

### O bias não é outro peso da mesma entrada

Os pesos multiplicam entradas específicas. O bias é somado **diretamente**. Quando `A = B = 0`, a soma vale `z = bias`: ele ajusta a fronteira de decisão independentemente das entradas.

## 3. Como o Perceptron aprende

Aprender, aqui, significa **corrigir os parâmetros sempre que o modelo erra**.

### Passo 1: calcular o erro

$$ erro = esperado - previsto $$

| esperado | previsto | erro | Significado |
| :-: | :-: | :-: | --- |
| `1` | `0` | `+1` | o modelo respondeu baixo demais |
| `0` | `1` | `-1` | o modelo respondeu alto demais |
| igual | igual | `0` | o modelo acertou |

### Passo 2: atualizar os parâmetros

$$ \begin{aligned} w_1 &\leftarrow w_1 + \eta \cdot erro \cdot A \\ w_2 &\leftarrow w_2 + \eta \cdot erro \cdot B \\ b &\leftarrow b + \eta \cdot erro \end{aligned} $$

A letra **η (eta)** representa a **taxa de aprendizado**. Ela controla o tamanho das correções, e **não** garante, por si só, um treinamento mais rápido ou melhor.

O que essa regra implica:

- Se a previsão está correta, `erro = 0`: nenhum parâmetro muda nesse exemplo.
- Se `A = 0`, a atualização do peso associado a `A` é zero.
- O bias pode mudar mesmo com `A = B = 0`.
- Corrigir um exemplo pode, temporariamente, prejudicar outro; por isso repetimos o treinamento.

### Cálculo manual

Peso inicial `0.5`, taxa `0.2`, erro `-1` e entrada `1`:

$$ w_{novo} = 0.5 + 0.2 \cdot (-1) \cdot 1 = 0.3 $$

O erro negativo **diminuiu** o peso: o modelo havia respondido alto demais.

## 4. O código: `perceptron.py`

```python
# Exemplos AND: ([A, B], resultado esperado)
dados = [
    ([0, 0], 0),
    ([0, 1], 0),
    ([1, 0], 0),
    ([1, 1], 1),
]

peso1 = 0.0
peso2 = 0.0
bias = 0.0
taxa_aprendizado = 0.2


def prever(a, b):
    z = a * peso1 + b * peso2 + bias
    return 1 if z >= 0 else 0


# Uma época percorre TODOS os exemplos uma vez.
for epoca in range(20):
    for entradas, esperado in dados:
        a, b = entradas
        previsao = prever(a, b)
        erro = esperado - previsao

        peso1 += taxa_aprendizado * erro * a
        peso2 += taxa_aprendizado * erro * b
        bias += taxa_aprendizado * erro


print("Resultados após o treinamento:")
for entradas, esperado in dados:
    a, b = entradas
    print(f"{entradas} | esperado={esperado} | previsto={prever(a, b)}")

print(f"Pesos: {peso1:.2f}, {peso2:.2f} | Bias: {bias:.2f}")
```

Para executar, abra o terminal na pasta da aula:

```
python perceptron.py
```

No Windows, se `python` não funcionar, tente `py perceptron.py`.

### Lendo o código por blocos

| Bloco | O que faz |
| --- | --- |
| `dados` | Os quatro exemplos do AND, no formato `([A, B], esperado)` |
| `peso1`, `peso2`, `bias` | Parâmetros iniciais, todos em `0.0` |
| `taxa_aprendizado` | O η da regra de aprendizado, aqui `0.2` |
| `prever(a, b)` | Calcula `z` e aplica o degrau |
| `for epoca in range(20)` | Repete o treinamento por 20 épocas |
| `erro = esperado - previsao` | Mede o quanto o modelo errou neste exemplo |
| linhas com `+=` | Aplicam a regra de atualização da seção 3 |
| `print(...)` | Mostra esperado × previsto e os parâmetros finais |

Repare que a linha do bias **não multiplica por nenhuma entrada**: o bias é somado diretamente.

## 5. Acompanhando o treinamento passo a passo

Com os parâmetros todos em `0.0`, o primeiro `z` vale `0` e o degrau responde `1` para **qualquer** entrada. Veja a primeira época:

| Exemplo | Esperado | Previsto | Erro | `peso1` | `peso2` | `bias` |
| :-: | :-: | :-: | :-: | :-: | :-: | :-: |
| `[0, 0]` | `0` | `1` | `-1` | `0.0` | `0.0` | `-0.2` |
| `[0, 1]` | `0` | `0` | `0` | `0.0` | `0.0` | `-0.2` |
| `[1, 0]` | `0` | `0` | `0` | `0.0` | `0.0` | `-0.2` |
| `[1, 1]` | `1` | `0` | `+1` | `0.2` | `0.2` | `0.0` |

Ao longo das épocas, os parâmetros evoluem assim:

| Fim da época | `peso1` | `peso2` | `bias` | Acertos |
| :-: | :-: | :-: | :-: | :-: |
| 1 | `0.2` | `0.2` | `0.0` | 1 de 4 |
| 2 | `0.4` | `0.2` | `-0.2` | 2 de 4 |
| 3 | `0.4` | `0.2` | `-0.4` | 4 de 4 |
| 4 em diante | `0.4` | `0.2` | `-0.4` | 4 de 4 (nada mais muda) |

A saída impressa pelo programa é:

```
Resultados após o treinamento:
[0, 0] | esperado=0 | previsto=0
[0, 1] | esperado=0 | previsto=0
[1, 0] | esperado=0 | previsto=0
[1, 1] | esperado=1 | previsto=1
Pesos: 0.40, 0.20 | Bias: -0.40
```

## 6. Interpretando os resultados

O modelo aprende a classificar corretamente as quatro combinações do AND. Isso significa que ele encontrou uma configuração útil de pesos e bias para **esses exemplos**.

É importante distinguir três ideias:

- **Época:** passagem completa por todos os exemplos.
- **Acurácia de treinamento:** fração das respostas corretas nesses exemplos.
- **Generalização:** desempenho em exemplos **novos**; não pode ser demonstrada apenas testando os mesmos dados usados no treinamento.

> **Um detalhe de ponto flutuante:** ao final, `peso1 = 0.4`, `peso2 = 0.2` e `bias` é aproximadamente `-0.4`. Para `A = 1` e `B = 0`, `z = 0.4 - 0.4`, que na matemática exata vale `0` e faria o degrau responder `1` (errado!). No Python, o bias acumulado fica em `-0.4000000000000001` por causa do arredondamento, `z` vira `-5.55e-17` e a previsão sai `0`. A solução encontrada está **exatamente na fronteira** de decisão: o Perceptron para de corrigir assim que acerta e não procura nenhuma folga de segurança.

O Perceptron com ativação degrau é diferente de um neurônio com **Sigmoid** treinado por gradientes, que você vai estudar na Aula 02.

## 7. Experimentos opcionais

1. **Menos épocas.** Troque `range(20)` por `range(1)` e compare. *Observado:* com uma única época, só 1 dos 4 exemplos é classificado corretamente.
2. **Outras taxas.** Experimente `taxa_aprendizado = 0.01` e `1.0` e registre se o resultado muda. *Observado:* nos dois casos o modelo chega a 4 de 4; mudam apenas os valores finais (com `1.0`, por exemplo, `2.0`, `1.0` e `-3.0`).
3. **Lógica OR.** Modifique os dados para `([0,0],0), ([0,1],1), ([1,0],1), ([1,1],1)` e veja se o modelo consegue aprender. *Observado:* ele aprende (4 de 4).
4. **Lógica XOR.** Use os dados `([0,0],0), ([0,1],1), ([1,0],1), ([1,1],0)`. *Observado:* o modelo nunca passa de 2 de 4 acertos, mesmo com centenas de épocas. Um único Perceptron não consegue acertar os quatro simultaneamente: a operação **não é linearmente separável**.

## 8. Resumo

| Conceito | Papel |
| --- | --- |
| Peso | Influência de uma entrada sobre `z` |
| Bias | Deslocamento do limiar de ativação |
| Função degrau | Converte `z` em classe `0` ou `1` |
| Erro | Diferença `esperado - previsto` |
| Taxa de aprendizado | Escala a atualização dos parâmetros |
| Época | Uma passagem sobre todos os dados |

> **Próxima aula:** múltiplos neurônios, XOR, Sigmoid, derivadas, Backpropagation e comparação de inicializações.
