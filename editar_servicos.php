<?php
session_start();
if (!isset($_SESSION['usuario'], $_SESSION['tipo'])) {
    header("Location: Login.html");
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=thander', 'appuser', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}

$mensagem = '';
$tipoMensagem = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $aparelho = $_POST['aparelho_marca'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE ordem_servicos SET nome = ?, telefone = ?, endereco = ?, aparelho_marca = ?, descricao = ?, status = ? WHERE id = ?");
        $stmt->execute([$nome, $telefone, $endereco, $aparelho, $descricao, $status, $id]);
        $mensagem = "Ordem atualizada com sucesso.";
        $tipoMensagem = "success";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar: " . $e->getMessage();
        $tipoMensagem = "danger";
    }
}

$stmt = $pdo->prepare("SELECT * FROM ordem_servicos WHERE id = ?");
$stmt->execute([$id]);
$ordem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ordem) {
    die("Ordem não encontrada.");
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Editar Ordem - Thander</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/ordem.css">
</head>
<body>

<!-- Navbar -->
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

<div class="container mt-5">
  <h2>Editar Ordem de Serviço (ID: <?= $ordem['id'] ?>)</h2>

  <?php if ($mensagem): ?>
    <div class="alert alert-<?= $tipoMensagem ?> mt-3"><?= htmlspecialchars($mensagem) ?></div>
  <?php endif; ?>

  <form method="post" class="mt-4">
    <div class="mb-3">
      <label class="form-label">Nome</label>
      <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($ordem['nome']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Telefone</label>
      <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($ordem['telefone']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Endereço</label>
      <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($ordem['endereco']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Aparelho / Marca</label>
      <input type="text" name="aparelho_marca" class="form-control" value="<?= htmlspecialchars($ordem['aparelho_marca']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" class="form-control" rows="3" required><?= htmlspecialchars($ordem['descricao']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <?php
        $statusList = ['Aberta', 'Em andamento', 'Concluída', 'Cancelada'];
        foreach ($statusList as $status) {
            $selected = ($ordem['status'] === $status) ? 'selected' : '';
            echo "<option $selected>$status</option>";
        }
        ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="ordens_cadastradas.php" class="btn btn-secondary">Voltar</a>
  </form>
</div>

<!-- Rodapé -->
<footer class="footer shadow mt-5" style="background-color: #0808B7;">
  <div class="container text-center py-3">
    <p class="text-white m-0">&copy; Thander Assistência Técnica 2025. Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
