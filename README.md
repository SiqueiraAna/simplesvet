# Desafio SimplesVet

## A missão

Um petshop chamado Memel's Pet acabou de contratar o **SimplesVet** e quer importar os dados do seu sistema atual (o **ComplicadoVet**).

O **ComplicadoVet** atualmente tem somente 2 tabelas `Clientes` e `Animais`, com a seguinte estrutura:

### Clientes

- Id
- Nome
- Telefone1
- Telefone2
- Email

### Animais

- Id
- IdCliente
- Nome
- Raca
- Especie
- HistoricoClinico
- Nascimento

## O que deve ser feito

### 1. Gerando o CSV

Criar um script em `PHP 7.x` para pegar os dados que estão no ComplicadoVet ([dump do MySQL neste arquivo](./complicadovet.sql)), e gerar 2 arquivos `.csv` (`Clientes.csv` e `Animais.csv`).

### 2. Upload do CSV

Em seguida, crie uma página em `HTML/CSS/JS` com um formulário em que a pessoa possa fazer o upload dos dois arquivos gerados no passo anterior.

### 3. Processamento

O back-end para receber este arquivo deve ser escrito em `PHP 7.x`.

Este script deve ler os dados do arquivo `Clientes.csv`, inserir o `Id` e `Nome` na tabela `pessoas` e os valores das colunas `Telefone1`, `Telefone2` e `Email` devem virar, cada um, um registro na tabela `contatos`, recebendo a chave estrangeira da respectiva pessoa.

O email tem que ser válido e o telefone deve estar formatado no padrão: (xx) xxxxx-xxxx. Caso o email seja inválido, não deve ser inserido o registro.

É preciso identificar o tipo do telefone, se é celular ou fixo. Caso o telefone esteja desformatado, ou seja um celular faltando o nono dígito, ele deve ser corrigido e inserido da maneira correta. Ex: 11 8745-2935 deve virar (11) 98745-2935

O arquivo `Animais.csv` tem uma coluna contendo o nome da `espécie`, o seu script deve verificar se existe uma espécie com esse nome, caso exista, deve usar o ID dela para associar ao animal que será inserido. Caso não exista, o seu script deve criar uma nova espécie, pegar o ID que acabou de ser inserido e criar o animal com essa espécie.

A mesma lógica deve ser usada para o campo `Raça`.

Ps: Faça um commit a cada função de terminar, não faça um commit grande com tudo... isso nos ajuda a entender o processo de desenvolvimento.