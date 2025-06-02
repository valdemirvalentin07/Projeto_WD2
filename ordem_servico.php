<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['tipo'])) {
    header("Location: Login.html");
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=thander','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['acao'] ?? '') {
        case 'inserir':
            $nome    = $_POST['nome'];
            $tel     = $_POST['telefone'];
            $end     = $_POST['endereco'];
            $ap      = $_POST['aparelho_marca'];
            $desc    = $_POST['descricao'];
            $stmt = $pdo->prepare("
              INSERT INTO ordem_servicos
                (nome, telefone, endereco, aparelho_marca, descricao, status)
              VALUES (?,?,?,?,?, 'Aberta')
            ");
            $stmt->execute([$nome, $tel, $end, $ap, $desc]);
            break;

        case 'editar':
            $id   = $_POST['id_ordem'];
            $nova = $_POST['nova_descricao'];
            $stmt = $pdo->prepare("UPDATE ordem_servicos SET descricao = ? WHERE id = ?");
            $stmt->execute([$nova, $id]);
            break;

        case 'status':
            $id     = $_POST['id_ordem_status'];
            $status = $_POST['novo_status'];
            $stmt   = $pdo->prepare("UPDATE ordem_servicos SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            break;
    }
    
}

$ordens = $pdo->query("SELECT * FROM ordem_servicos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ordem de Serviço - Thander</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow">
    <div class="container">
      <span class="navbar-brand fw-bold text-white">Thander Assistência Técnica</span>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="home.html" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Marcas</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Serviços</a></li>
          <li class="nav-item">
            <a href="logout.php" class="btn btn-outline-light nav-link">Sair</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="navbar-spacer"></div>

<div class="container">
  <h2 class="text-center">Gerenciar Ordem de Serviço</h2>

  <ul class="nav nav-tabs mt-4" id="ordemTab" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#add">Adicionar</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#edit">Editar</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#status">Alterar Status</button></li>
  </ul>

  <div class="tab-content">

    <!-- Adicionar -->
    <div class="tab-pane fade show active" id="add">
      <form method="post" class="p-4 bg-white border border-top-0">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nome Completo</label>
            <input type="text" name="nome" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Número de Telefone</label>
            <input type="tel" name="telefone" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Endereço</label>
            <input type="text" name="endereco" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Aparelho / Marca</label>
            <input type="text" name="aparelho_marca" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Descrição do Problema</label>
            <textarea name="descricao" class="form-control" rows="3" required></textarea>
          </div>
        </div>
        <button type="submit" name="acao" value="inserir" class="btn btn-success mt-3">Adicionar Ordem</button>
      </form>
    </div>
      <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
    <div class="alert alert-success mt-3" role="alert">
      Cadastro realizado com sucesso!
    </div>
  <?php endif; ?> 

    <!-- Editar -->
    <div class="tab-pane fade" id="edit">
      <form method="post" class="p-4 bg-white border border-top-0">
        <div class="mb-3">
          <label class="form-label">ID da Ordem</label>
          <input type="number" name="id_ordem" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nova Descrição</label>
          <textarea name="nova_descricao" class="form-control" required></textarea>
        </div>
        <button type="submit" name="acao" value="editar" class="btn btn-primary">Editar Ordem</button>
      </form>
    </div>

    <!-- Alterar Status -->
    <div class="tab-pane fade" id="status">
      <form method="post" class="p-4 bg-white border border-top-0">
        <div class="mb-3">
          <label class="form-label">ID da Ordem</label>
          <input type="number" name="id_ordem_status" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Novo Status</label>
          <select name="novo_status" class="form-select">
            <option>Aberta</option>
            <option>Em andamento</option>
            <option>Concluída</option>
            <option>Cancelada</option>
          </select>
        </div>
        <button type="submit" name="acao" value="status" class="btn btn-warning">Alterar Status</button>
      </form>
    </div>

  </div>


  <main class="container">
<footer class="footer">
  
    <p class="m-0">&copy; Thander Assistência Técnica 2025. Todos os direitos reservados.</p>
 
</footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
