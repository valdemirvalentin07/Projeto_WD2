<?php
session_start();
if (!isset($_SESSION['usuario'], $_SESSION['tipo']) || $_SESSION['tipo'] !== 'tecnico') {
    header("Location: Login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'], $_POST['descricao'], $_POST['valor']) || !is_numeric($_POST['id']) || !is_numeric($_POST['valor'])) {
        die("Dados inválidos.");
    }

    $id = $_POST['id'];
    $descricao = trim($_POST['descricao']);
    $valor = $_POST['valor'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=thander', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE ordem_servicos SET orcamento_descricao = ?, orcamento_valor = ? WHERE id = ?");
        $stmt->execute([$descricao, $valor, $id]);

        header("Location: orcamento.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar orçamento: " . $e->getMessage());
    }
} else {
    header("Location: orcamento.php");
    exit;
}
