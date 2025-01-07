<?php

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'simplesvet';
$username = 'root';
$password = '';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Upload dos arquivos
$clientesFile = $_FILES['clientes']['tmp_name'];
$animaisFile = $_FILES['animais']['tmp_name'];

// Função para processar Clientes.csv
function processClientes($file) {
    global $pdo;

    $handle = fopen($file, 'r');
    fgetcsv($handle); // Ignora o cabeçalho

    while (($data = fgetcsv($handle)) !== false) {
        $id = $data[0];
        $nome = $data[1];
        $telefones = [$data[2], $data[3]];
        $email = filter_var($data[4], FILTER_VALIDATE_EMAIL);

        // Insere na tabela pessoas
        $stmt = $pdo->prepare("INSERT INTO pessoas (id, nome) VALUES (?, ?)");
        $stmt->execute([$id, $nome]);

        // Insere telefones e email na tabela contatos
        foreach ($telefones as $telefone) {
            $tipo = strlen(preg_replace('/[^0-9]/', '', $telefone)) === 11 ? 'celular' : 'fixo';
            $telefone = formatTelefone($telefone);
            $stmt = $pdo->prepare("INSERT INTO contatos (pessoa_id, tipo, valor) VALUES (?, ?, ?)");
            $stmt->execute([$id, $tipo, $telefone]);
        }

        if ($email) {
            $stmt = $pdo->prepare("INSERT INTO contatos (pessoa_id, tipo, valor) VALUES (?, 'email', ?)");
            $stmt->execute([$id, $email]);
        }
    }
    fclose($handle);
}

// Processar telefones
function formatTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) === 10) {
        $telefone = substr_replace($telefone, '9', 2, 0);
    }
    return sprintf('(%s) %s-%s', substr($telefone, 0, 2), substr($telefone, 2, 5), substr($telefone, 7));
}

// Processar Animais.csv
function processAnimais($file) {
    global $pdo;

    $handle = fopen($file, 'r');
    fgetcsv($handle); // Ignora o cabeçalho

    while (($data = fgetcsv($handle)) !== false) {
        $id = $data[0];
        $idCliente = $data[1];
        $nome = $data[2];
        $raca = $data[3];
        $especie = $data[4];

        // Verifica ou cria espécie
        $stmt = $pdo->prepare("SELECT id FROM especies WHERE nome = ?");
        $stmt->execute([$especie]);
        $especieId = $stmt->fetchColumn();
        if (!$especieId) {
            $stmt = $pdo->prepare("INSERT INTO especies (nome) VALUES (?)");
            $stmt->execute([$especie]);
            $especieId = $pdo->lastInsertId();
        }

        // Verifica ou cria raça
        $stmt = $pdo->prepare("SELECT id FROM racas WHERE nome = ?");
        $stmt->execute([$raca]);
        $racaId = $stmt->fetchColumn();
        if (!$racaId) {
            $stmt
