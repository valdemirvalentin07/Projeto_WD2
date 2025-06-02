<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrador - Thander</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow">
        <div class="container">
            <i class="navbar-brand fw-bold text-purple">Thander Assistência Técnica</i>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="home.html" class="nav-link text-white">Home</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Marcas</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Serviços</a></li>
                    <li class="nav-item">
                        <a href="pagina_login.html" class="btn btn-outline-purple nav-link text-white">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 75px - 50px);">
    <div class="container" style="max-width: 650px;">
        <div class="rounded shadow p-0" style="border: 2px solid #0808B7;">
            <div class="text-center p-3" style="background-color: #0808B7;">
                <h3 class="m-0 text-light">Administrador</h3>
            </div>
            <div class="p-4">
                <h5 class="mb-4">Ações Disponíveis:</h5>

                <div class="d-grid gap-3">
                    <a href="cadastrar_tecnico.php" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Cadastrar Técnico</a>
                    <a href="ordem_servico.php" class="btn btn-primary"><i class="bi bi-journal-text"></i> Registrar Ordem de Serviço</a>
                    <a href="gerar_relatorios.php" class="btn btn-primary"><i class="bi bi-file-earmark-bar-graph"></i> Gerar Relatórios</a>
                    <a href="login.php" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Login / Logout</a>
                </div>

            </div>
        </div>
    </div>
</main>

<footer class="footer shadow mt-5">
    <div class="footer">
        <p style="color: white; text-align: center;">&copy; Thander Assistência Técnica 2025. Todos direitos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
