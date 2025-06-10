<?php
session_start();

if (!isset($_SESSION['usuario'], $_SESSION['tipo'])) {
    header("Location: Login.html");
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=thander', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}

$mensagem = '';
$tipoMensagem = '';

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $tipoMensagem = $_SESSION['tipoMensagem'] ?? 'info';
    unset($_SESSION['mensagem'], $_SESSION['tipoMensagem']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['excluir']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM ordem_servicos WHERE id = ?");
        if ($stmt->execute([$id])) {
            $mensagem = "Ordem ID $id excluída com sucesso.";
            $tipoMensagem = 'success';
        } else {
            $mensagem = "Erro ao excluir a ordem.";
            $tipoMensagem = 'danger';
        }
    } elseif (isset($_POST['editar']) && is_numeric($_POST['id'])) {
        header("Location: gerar_orcamento.php?id=" . intval($_POST['id']));
        exit;
    }
}

// Consulta para agrupar ordens por status
$relStatus = $pdo->query("SELECT status, COUNT(*) AS total FROM ordem_servicos GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);

// Consulta para listar todas as ordens
$ordens = $pdo->query("SELECT * FROM ordem_servicos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ordens cadastradas - Thander</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/ordem.css">
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow">
    <div class="container">
     <i class="navbar-brand fw-bold text-purple">Thander Assistência Técnica</i>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="tecnico.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="orcamento.php" class="nav-link active">Orçamentos</a></li>
          <li class="nav-item"><a href="tecnico.php" class="nav-link">Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="navbar-spacer"></div>

<div class="container">
  <h2 class="text-center">Ordens de Serviço cadastradas</h2>

  <?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?= htmlspecialchars($tipoMensagem) ?> mt-4"><?= htmlspecialchars($mensagem) ?></div>
  <?php endif; ?>

  <!-- Resumo por status -->
  <div class="mt-4">
    <h5>Resumo por Status</h5>
    <table class="table table-bordered bg-white">
      <thead>
        <tr>
          <th>Status</th>
          <th>Total de Ordens</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($relStatus as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= intval($row['total']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Listagem completa -->
  <div class="mt-5">
    <h5>Listagem Completa</h5>
    <table class="table table-striped bg-white">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Telefone</th>
          <th>Endereço</th>
          <th>Aparelho/Marca</th>
          <th>Descrição</th>
          <th>Status</th>
          <th>Data de Entrada</th>
          <th>Data de Retirada</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($ordens as $o): ?>
        <tr>
          <td><?= intval($o['id']) ?></td>
          <td><?= htmlspecialchars($o['nome']) ?></td>
          <td><?= htmlspecialchars($o['telefone']) ?></td>
          <td><?= htmlspecialchars($o['endereco']) ?></td>
          <td><?= htmlspecialchars($o['aparelho_marca']) ?></td>
          <td><?= htmlspecialchars($o['descricao']) ?></td>
          <td><?= htmlspecialchars($o['status']) ?></td>
          <td><?= date('d/m/Y', strtotime($o['data_entrada'])) ?></td>
          <td><?= $o['data_retirada'] ? date('d/m/Y', strtotime($o['data_retirada'])) : '-' ?></td>
          <td>
            <!-- Botões Editar e Excluir -->
            <form method="post" style="display:inline;">
              <input type="hidden" name="id" value="<?= intval($o['id']) ?>">
              <button type="submit" name="editar" class="btn btn-sm btn-primary">Gerar Orçamento</button>
            </form>
            <form method="post" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta ordem?');">
              <input type="hidden" name="id" value="<?= intval($o['id']) ?>">
              <button type="submit" name="excluir" class="btn btn-sm btn-danger">Excluir</button>
            </form>

            <!-- Mostrar orçamento existente -->
            <?php if (!empty($o['orcamento_descricao']) && !empty($o['orcamento_valor'])): ?>
              <div class="mt-2 p-2 bg-light border rounded">
                <strong>Orçamento:</strong><br>
                <span class="text-success">R$ <?= number_format($o['orcamento_valor'], 2, ',', '.') ?></span><br>
                <small><?= nl2br(htmlspecialchars($o['orcamento_descricao'])) ?></small>
              </div>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<footer class="footer shadow mt-5" style="background-color: #0808B7;">
  <div class="container text-center py-3">
    <p class="text-white m-0">&copy; Thander Assistência Técnica 2025. Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
