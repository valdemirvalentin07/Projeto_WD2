<?php
session_start();

if (!isset($_SESSION['usuario'], $_SESSION['tipo']) || $_SESSION['tipo'] !== 'tecnico') {
    header("Location: Login.html");
    exit;
}

if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
    die("ID da ordem inválido.");
}

$id = intval($_REQUEST['id']);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=thander', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM ordem_servicos WHERE id = ?");
    $stmt->execute([$id]);
    $ordem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ordem) {
        die("Ordem não encontrada.");
    }
} catch (PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gerar Orçamento - Ordem #<?= $id ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Ordem de Serviço #<?= $id ?></h2>

    <div class="card mb-4">
        <div class="card-header">Dados da Ordem</div>
        <div class="card-body">
            <p><strong>Cliente:</strong> <?= htmlspecialchars($ordem['nome']) ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($ordem['telefone']) ?></p>
            <p><strong>Endereço:</strong> <?= htmlspecialchars($ordem['endereco']) ?></p>
            <p><strong>Aparelho/Marca:</strong> <?= htmlspecialchars($ordem['aparelho_marca']) ?></p>
            <p><strong>Descrição do defeito:</strong> <?= nl2br(htmlspecialchars($ordem['descricao'])) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($ordem['status']) ?></p>
            <?php if(!empty($ordem['orcamento_descricao'])): ?>
            <hr>
            <p><strong>Orçamento atual:</strong><br><?= nl2br(htmlspecialchars($ordem['orcamento_descricao'])) ?></p>
            <p><strong>Valor atual:</strong> R$ <?= number_format($ordem['orcamento_valor'], 2, ',', '.') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <h4>Gerar / Atualizar Orçamento</h4>

    <form method="post" action="salvar_orcamento.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição dos serviços</label>
            <textarea name="descricao" id="descricao" rows="5" class="form-control" required><?= htmlspecialchars($ordem['orcamento_descricao'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="valor" class="form-label">Valor (R$)</label>
            <input type="number" step="0.01" name="valor" id="valor" class="form-control" required value="<?= htmlspecialchars($ordem['orcamento_valor'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-success">Salvar Orçamento</button>
        <a href="orcamento.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>
</html>
