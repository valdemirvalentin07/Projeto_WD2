<?php 



 
session_start();
if (!isset($_SESSION['usuario'], $_SESSION['tipo'])) {
    header("Location: Login.html");
    exit;
}

require_once 'Classes/db.php';

// BUSCA OS DADOS AQUI
$stmtStatus = $pdo->query("SELECT status, COUNT(*) AS total FROM ordem_servicos GROUP BY status");
$relStatus = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);

$stmtOrdens = $pdo->query("SELECT * FROM ordem_servicos ORDER BY data_entrada DESC");
$ordens = $stmtOrdens->fetchAll(PDO::FETCH_ASSOC);

$mensagem = '';
$tipoMensagem = '';
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
          <li class="nav-item"><a href="adm.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="ordem_servico.php" class="nav-link">Ordens</a></li>
          <li class="nav-item"><a href="ordens_cadastradas.php" class="nav-link active">Cadastros</a></li>
          <li class="nav-item"><a href="adm.php" class="nav-link">Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="navbar-spacer"></div>

<div class="container">
  <h2 class="text-center">Ordens de Serviço cadastradas</h2>

  <?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?= $tipoMensagem ?> mt-4"><?= htmlspecialchars($mensagem) ?></div>
  <?php endif; ?>

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
          <td><?= $row['total'] ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

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
          <td><?= $o['id'] ?></td>
          <td><?= htmlspecialchars($o['nome']) ?></td>
          <td><?= htmlspecialchars($o['telefone']) ?></td>
          <td><?= htmlspecialchars($o['endereco']) ?></td>
          <td><?= htmlspecialchars($o['aparelho_marca']) ?></td>
          <td><?= htmlspecialchars($o['descricao']) ?></td>
          <td><?= htmlspecialchars($o['status']) ?></td>
          <td><?= date('d/m/Y', strtotime($o['data_entrada'])) ?></td>
          <td><?= $o['data_retirada'] ? date('d/m/Y', strtotime($o['data_retirada'])) : '-' ?></td>
          <td>
            <?php if ($_SESSION['tipo'] === 'tecnico'): ?>
              <?php if (empty($o['orcamento_descricao']) || empty($o['orcamento_valor'])): ?>
                <form method="post" action="gerar_orcamento.php" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $o['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-success">Gerar Orçamento</button>
                </form>
              <?php else: ?>
                <div class="mt-2 p-2 bg-light border rounded">
                  <strong>Orçamento:</strong><br>
                  <span class="text-success">R$ <?= number_format($o['orcamento_valor'], 2, ',', '.') ?></span><br>
                  <small><?= nl2br(htmlspecialchars($o['orcamento_descricao'])) ?></small>
                </div>
              <?php endif; ?>
            <?php elseif ($_SESSION['tipo'] === 'admin'): ?>
              <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $o['id'] ?>">
                <button type="submit" name="editar" class="btn btn-sm btn-primary">Editar</button>
              </form>
              <form method="post" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta ordem?');">
                <input type="hidden" name="id" value="<?= $o['id'] ?>">
                <button type="submit" name="excluir" class="btn btn-sm btn-danger">Excluir</button>
              </form>
              <form method="post" class="mt-2">
                <input type="hidden" name="id" value="<?= $o['id'] ?>">
                <div class="input-group input-group-sm">
                  <input type="date" name="data_retirada" class="form-control" value="<?= $o['data_retirada'] ?>">
                  <button type="submit" name="registrar_retirada" class="btn btn-outline-secondary">Salvar</button>
                </div>
              </form>
              <?php if (!empty($o['orcamento_descricao']) && !empty($o['orcamento_valor'])): ?>
                <div class="mt-2 p-2 bg-light border rounded">
                  <strong>Orçamento:</strong><br>
                  <span class="text-success">R$ <?= number_format($o['orcamento_valor'], 2, ',', '.') ?></span><br>
                  <small><?= nl2br(htmlspecialchars($o['orcamento_descricao'])) ?></small>
                </div>
              <?php endif; ?>
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
