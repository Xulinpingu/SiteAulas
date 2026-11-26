# Redes multicamadas, Sigmoid e Backpropagation

> **Projeto:** ZeroToAI · **Linguagem:** Python puro · **Pré-requisito:** Aula 01 (Perceptron).

Na Aula 01, um único neurônio aprendeu o AND. Nesta aula vamos além: construímos uma pequena **rede neural** que aprende a operação **XOR** sem que ninguém defina os pesos à mão. Para isso você vai conhecer a **Sigmoid**, a **perda**, os **gradientes** e o algoritmo que faz a rede aprender: o **Backpropagation**.

## Objetivos

Ao terminar, você deverá compreender:

- por que um Perceptron não resolve o XOR;
- camadas ocultas e não linearidade;
- a Sigmoid e a sua derivada;
- função de perda, gradientes, regra da cadeia e **Backpropagation**;
- o **Gradient Descent**;
- inicialização aleatória, simetria, **Xavier/Glorot** e reprodutibilidade;
- como comparar treinamentos de forma experimental.

> **Como estudar esta aula:** as seções 1 a 4 são a teoria; as seções 5 a 7 colocam a teoria em código e em experimentos; a seção 8 lista erros reais que você provavelmente vai encontrar.

## 1. XOR e a limitação de um Perceptron

A operação **XOR** (OU exclusivo) retorna `1` quando as duas entradas são **diferentes**.

| A | B | XOR |
| :-: | :-: | :-: |
| 0 | 0 | 0 |
| 0 | 1 | 1 |
| 1 | 0 | 1 |
| 1 | 1 | 0 |

Um Perceptron decide com `z = A*w1 + B*w2 + bias` e um limiar. Essa decisão estabelece uma **fronteira linear**: os exemplos do XOR não podem ser separados corretamente por uma única reta.

### Por que é impossível? A prova algébrica

Para acertar as quatro linhas da tabela, os parâmetros precisariam satisfazer **ao mesmo tempo**:

$$ \begin{aligned} b &< 0 \\ w_2 + b &\geq 0 \\ w_1 + b &\geq 0 \\ w_1 + w_2 + b &< 0 \end{aligned} $$

As duas desigualdades do meio implicam `w1 + w2 + 2b >= 0`. Como `b < 0`, isso implica `w1 + w2 + b > 0`, o que **contradiz** a última condição. Logo, nenhum conjunto de pesos e bias resolve o XOR com um único neurônio.

## 2. A arquitetura 2–2–1

Usamos **duas entradas**, **dois neurônios ocultos** e **um neurônio de saída**:

```
Entradas     Camada oculta     Saída

  A ──┬──► N1 ──┐
      │         ├──► N3 ──► previsão
  B ──┴──► N2 ──┘

(cada entrada alimenta tanto N1 quanto N2)
```

Cada neurônio oculto combina as entradas; o terceiro combina as representações intermediárias.

### Demonstração manual

Podemos montar o XOR a partir de OR e AND:

- `N1 = OR(A, B)`
- `N2 = AND(A, B)`
- `N3` ativa quando `N1 = 1` **e** `N2 = 0`.

Isso mostra que vários neurônios conseguem **combinar saídas** para resolver o que um só não resolve. Mas aqui definimos os pesos à mão. Para que a rede **aprenda** os pesos sozinha, precisamos de um procedimento de treinamento.

### Por que a ativação não linear importa?

Várias camadas que fazem somente somas ponderadas e bias podem ser compostas em **uma única transformação afim**. Sem não linearidade nas camadas ocultas, a profundidade sozinha não resolve o XOR.

> **Resumo da seção:** camadas ocultas **mais** uma ativação não linear permitem representar o que um Perceptron simples não representa.

## 3. A função Sigmoid

Para treinar com gradientes, precisamos de uma ativação suave. A **Sigmoid** é:

$$ \sigma(z) = \frac{1}{1 + e^{-z}} $$

Ela retorna valores **entre `0` e `1`** e é **diferenciável**. Sua derivada tem uma forma muito conveniente:

$$ \sigma'(z) = \sigma(z)\,\bigl(1 - \sigma(z)\bigr) $$

| z | Sigmoid (aprox.) |
| :-: | :-: |
| `-2` | `0.1192` |
| `-1` | `0.2689` |
| `0` | `0.5000` |
| `1` | `0.7311` |
| `2` | `0.8808` |

> **Atenção:** o resultado da Sigmoid **não é automaticamente uma probabilidade calibrada de estar certo**. É apenas um número entre 0 e 1.

### Implementação numericamente estável

O cálculo direto de `math.exp(-z)` transborda quando `z` é muito negativo. A versão abaixo evita o overflow:

```python
import math

def sigmoid(z):
    if z >= 0:
        return 1 / (1 + math.exp(-z))
    exp_z = math.exp(z)
    return exp_z / (1 + exp_z)
```

## 4. Perda, gradiente e regra da cadeia

### A perda

Para uma saída `y` e um resultado esperado `t`, usamos a **perda quadrática**:

$$ L = \frac{1}{2}(t - y)^2 $$

Sua derivada em relação à previsão é:

$$ \frac{\partial L}{\partial y} = y - t $$

### O gradiente de um neurônio

Para um único neurônio com saída `y = sigmoid(z)` e `z = entrada*w + bias`:

$$ \frac{\partial L}{\partial w} = (y - t)\,y\,(1 - y)\,\text{entrada} $$

### Gradient Descent

Para reduzir a perda, movemos cada parâmetro **contra** o gradiente:

$$ w \leftarrow w - \eta\,\frac{\partial L}{\partial w} $$

### Backpropagation

O **Backpropagation** aplica a **regra da cadeia**, propagando os gradientes da saída para as camadas anteriores. Na nossa rede:

$$ \begin{aligned} \delta_3 &= (n_3 - t)\,n_3\,(1 - n_3) \\ \delta_1 &= \delta_3\,w_5\,n_1\,(1 - n_1) \\ \delta_2 &= \delta_3\,w_6\,n_2\,(1 - n_2) \end{aligned} $$

### Exemplo numérico

Com `n3 = 0.6`, `esperado = 1` e `n1 = 0.8`:

- `delta3 = (0.6 - 1) * 0.6 * 0.4 = -0.096`
- `gradiente_w5 = delta3 * n1 = -0.0768`
- Com `w5 = 0.5` e `taxa = 0.5`: `w5_novo = 0.5 - 0.5 * (-0.0768) = 0.5384`

> **Regra crucial:** calcule os deltas de **todas** as camadas **antes** de atualizar os pesos. Os gradientes devem corresponder à mesma previsão, feita com o mesmo conjunto de pesos.

## 5. O código: `rede_neural.py`

O arquivo [`rede_neural.py`](https://github.com/IsaacFaleirosQuevedo/ZeroToAI/blob/main/Aula_02/rede_neural.py) contém a implementação completa da rede XOR 2–2–1, com **inicialização Xavier**, `random.seed(3)`, Sigmoid numericamente estável e relatório de perda a cada 1.000 épocas.

```
python rede_neural.py
```

A rede tem **9 parâmetros**: seis pesos (`w1` a `w6`) e três bias (`b1`, `b2`, `b3`). As etapas centrais do treinamento, para cada exemplo, são:

```python
n1, n2, n3 = prever(a, b)  # Forward propagation

delta3 = (n3 - esperado) * derivada_sigmoid(n3)
delta1 = delta3 * w5 * derivada_sigmoid(n1)
delta2 = delta3 * w6 * derivada_sigmoid(n2)

# Todos os deltas são calculados antes da atualização.
w5 -= taxa_aprendizado * delta3 * n1
w6 -= taxa_aprendizado * delta3 * n2
b3 -= taxa_aprendizado * delta3
w1 -= taxa_aprendizado * delta1 * a
w2 -= taxa_aprendizado * delta1 * b
b1 -= taxa_aprendizado * delta1
w3 -= taxa_aprendizado * delta2 * a
w4 -= taxa_aprendizado * delta2 * b
b2 -= taxa_aprendizado * delta2
```

> **Nota:** com `seed(3)` a rede pode ficar em uma solução ruim e classificar apenas 3 das 4 combinações do XOR. Isso é parte do experimento, **não** um erro no código. Para comparar métodos e seeds diferentes, use o `avaliacao_de_redes.py` (seção 9).

## 6. Inicialização e reprodutibilidade

### A seed

`random.seed(3)` torna a sequência pseudoaleatória **reproduzível** no script `rede_neural.py`. A seed **não** é um parâmetro aprendido. Pesos iniciais diferentes alteram o ponto de partida do Gradient Descent.

### Simetria

Se os dois neurônios ocultos tiverem pesos e bias iniciais idênticos, e as conexões de saída forem igualmente inicializadas, eles receberão gradientes idênticos e continuarão aprendendo a **mesma** representação. Inicializar os pesos de maneira diferente **quebra essa simetria**.

### Saturação

A Sigmoid fica próxima de `0` ou `1` quando `z` tem módulo grande. Nesse caso sua derivada se aproxima de zero e os ajustes podem ficar minúsculos (*vanishing gradients*). Peso grande não é sinônimo automático de saturação: importa o `z` final.

### Inicialização uniforme e Xavier

- **Uniforme simples:** `random.uniform(-1, 1)`.
- **Xavier/Glorot uniforme:** sorteia pesos em `[-limite, limite]`, com

$$ limite = \sqrt{\frac{6}{\text{fan\_in} + \text{fan\_out}}} $$

Na rede 2–2–1:

| Camada | `fan_in` | `fan_out` | Limite |
| --- | :-: | :-: | :-: |
| Oculta | `2` | `2` | `sqrt(1.5)` ≈ `1.225` |
| Saída | `2` | `1` | `sqrt(2)` ≈ `1.414` |

> **Atenção:** o Xavier melhora a escala inicial em muitas situações, **mas não garante sucesso** nem superioridade em cada arquitetura e seed.

## 7. Comparação experimental feita durante a aula

Foram testadas **20 seeds** (`0` a `19`), **30.000 épocas** e taxa `0.8`, com os pesos reinicializados a cada execução:

| Indicador | Uniforme `[-1, 1]` | Xavier |
| --- | :-: | :-: |
| Redes com 4/4 acertos | `16/20` | `15/20` |
| Taxa de sucesso | `80%` | `75%` |
| Perda média final | `0.050152` | `0.062662` |

Ambos os métodos falharam nas seeds `3`, `9`, `11` e `12`. O Xavier também falhou na seed `18`, em que o uniforme teve sucesso. Quando falharam, as redes frequentemente ficaram com perda perto de `0.25` e previsões próximas de `0.5` em dois casos que exigiam respostas diferentes.

> **Conclusão válida:** a inicialização uniforme teve melhor resultado **nessas 20 execuções**. A diferença de um único sucesso não prova superioridade geral. Seriam necessários mais experimentos, comparando também o tempo de convergência, a distribuição da perda e a estabilidade.

### Cuidados na avaliação

- A avaliação deve acontecer **depois de terminar a época** (ou o treinamento), usando os pesos finais daquele momento. Se a métrica é acumulada dentro do laço que atualiza os pesos a cada exemplo, ela mistura estados diferentes do modelo.
- Para cada seed, reinicialize **de verdade** todos os parâmetros. Trocar apenas o valor da seed, sem sortear pesos novos, não reinicia a rede.

## 8. Erros reais encontrados e como corrigir

### Erro 1: subtração em vez de multiplicação

**Errado:**

```python
delta1 = delta3 * w5 - n1 * (1 - n1)
delta2 = delta3 * w6 - n2 * (1 - n2)
```

**Correto:**

```python
delta1 = delta3 * w5 * n1 * (1 - n1)
delta2 = delta3 * w6 * n2 * (1 - n2)
```

A regra da cadeia exige o **produto** das derivadas. Usar subtração muda completamente o gradiente e pode provocar crescimento indevido dos parâmetros.

### Erro 2: `OverflowError: math range error`

O cálculo direto de `math.exp(-z)` pode transbordar quando `z` é muito negativo. Use a implementação estável da Sigmoid da seção 3. Isso evita o erro numérico, mas **não** corrige equações de gradiente incorretas.

### Erro 3: avaliar a cada época sem necessidade

Repetir a avaliação completa a cada época, quando só se deseja o resultado final, desperdiça processamento. Avalie ao final do treinamento ou em intervalos definidos, se quiser acompanhar o progresso.

## 9. Código de referência: `avaliacao_de_redes.py`

Abaixo está uma versão didática equivalente do arquivo [`avaliacao_de_redes.py`](https://github.com/IsaacFaleirosQuevedo/ZeroToAI/blob/main/Aula_02/avaliacao_de_redes.py), com um gerador pseudoaleatório independente para cada execução. O arquivo do projeto mantém os cálculos explicitamente separados por neurônio.

```python
import math
import random

DADOS = [([0, 0], 0), ([0, 1], 1), ([1, 0], 1), ([1, 1], 0)]


def sigmoid(z):
    if z >= 0:
        return 1 / (1 + math.exp(-z))
    e = math.exp(z)
    return e / (1 + e)


def inicializar_pesos(seed, metodo):
    rng = random.Random(seed)
    if metodo == "uniforme":
        limite_oculta = limite_saida = 1.0
    elif metodo == "xavier":
        limite_oculta = math.sqrt(6 / (2 + 2))
        limite_saida = math.sqrt(6 / (2 + 1))
    else:
        raise ValueError("Método inválido")

    p = [rng.uniform(-limite_oculta, limite_oculta) for _ in range(4)]
    p += [rng.uniform(-limite_saida, limite_saida) for _ in range(2)]
    return p + [0.0, 0.0, 0.0]


def treinar_rede(seed, metodo, epocas=30000, taxa=0.8):
    w1, w2, w3, w4, w5, w6, b1, b2, b3 = inicializar_pesos(seed, metodo)

    def prever(a, b):
        n1 = sigmoid(a * w1 + b * w2 + b1)
        n2 = sigmoid(a * w3 + b * w4 + b2)
        n3 = sigmoid(n1 * w5 + n2 * w6 + b3)
        return n1, n2, n3

    for _ in range(epocas):
        for (a, b), esperado in DADOS:
            n1, n2, n3 = prever(a, b)
            delta3 = (n3 - esperado) * n3 * (1 - n3)
            delta1 = delta3 * w5 * n1 * (1 - n1)
            delta2 = delta3 * w6 * n2 * (1 - n2)

            w5 -= taxa * delta3 * n1
            w6 -= taxa * delta3 * n2
            b3 -= taxa * delta3
            w1 -= taxa * delta1 * a
            w2 -= taxa * delta1 * b
            b1 -= taxa * delta1
            w3 -= taxa * delta2 * a
            w4 -= taxa * delta2 * b
            b2 -= taxa * delta2

    perda = 0.0
    acertos = 0
    for (a, b), esperado in DADOS:
        _, _, previsto = prever(a, b)
        perda += 0.5 * (esperado - previsto) ** 2
        acertos += int((previsto >= 0.5) == bool(esperado))
    return perda, acertos


if __name__ == "__main__":
    for metodo in ("uniforme", "xavier"):
        sucessos = 0
        soma_perdas = 0.0
        print(f"\nMétodo: {metodo.upper()}")
        for seed in range(20):
            perda, acertos = treinar_rede(seed, metodo)
            sucessos += int(acertos == 4)
            soma_perdas += perda
            print(f"Seed {seed:02d} | Perda: {perda:.6f} | Acertos: {acertos}/4")
        print(f"Taxa de sucesso: {sucessos / 20:.1%}")
        print(f"Perda média: {soma_perdas / 20:.6f}")
```

## 10. Resumo e próximos passos

| Conceito | O que aprendemos |
| --- | --- |
| XOR | Não é linearmente separável |
| Camada oculta | Constrói representações intermediárias |
| Sigmoid | Ativação não linear e diferenciável |
| Perda | Mede o desvio das previsões |
| Gradiente | Mede a variação local da perda em relação aos parâmetros |
| Backpropagation | Aplica a regra da cadeia às camadas |
| Gradient Descent | Atualiza os parâmetros na direção de reduzir a perda |
| Seed | Controla a sequência pseudoaleatória |
| Xavier | Controla a escala inicial usando `fan_in` e `fan_out` |

