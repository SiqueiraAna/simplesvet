<?php

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'complicadovet';
$username = 'root';
$password = '';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Função para gerar CSV
function generateCSV($query, $filename, $headers) {
    global $pdo;
    $stmt = $pdo->query($query);
    $file = fopen($filename, 'w');

    // Adiciona o cabeçalho
    fputcsv($file, $headers);

    // Adiciona os dados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($file, $row);
    }

    fclose($file);
    echo "Arquivo $filename gerado com sucesso.<br>";
}

// Gera Clientes.csv
generateCSV(
    "SELECT Id, Nome, Telefone1, Telefone2, Email FROM Clientes",
    "Clientes.csv",
    ['Id', 'Nome', 'Telefone1', 'Telefone2', 'Email']
);

// Gera Animais.csv
generateCSV(
    "SELECT Id, IdCliente, Nome, Raca, Especie, HistoricoClinico, Nascimento FROM Animais",
    "Animais.csv",
    ['Id', 'IdCliente', 'Nome', 'Raca', 'Especie', 'HistoricoClinico', 'Nascimento']
);

?>
