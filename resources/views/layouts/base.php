<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' | ' : '' ?><?= htmlspecialchars($appName ?? 'Rede Adventista') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body class="bg-light text-dark">
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#2E5EAA;">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Rede Adventista</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/feed">Feed</a></li>
                <li class="nav-item"><a class="nav-link" href="/reels">Reels</a></li>
                <li class="nav-item"><a class="nav-link" href="/stories">Stories</a></li>
                <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item me-lg-3">
                    <button class="btn btn-sm btn-outline-light" onclick="toggleTheme();">
                        Alternar Tema
                    </button>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuGlobal" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Menu Global
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg p-3" aria-labelledby="menuGlobal" style="min-width: 320px;">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="dropdown-header">Conteúdo</h6>
                                <a class="dropdown-item" href="/feed">Feed</a>
                                <a class="dropdown-item" href="/reels">Reels</a>
                                <a class="dropdown-item" href="/stories">Stories</a>
                                <a class="dropdown-item" href="/blog">Blog</a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Comunidade</h6>
                                <a class="dropdown-item" href="/ministerios">Ministérios</a>
                                <a class="dropdown-item" href="/eventos">Eventos</a>
                                <a class="dropdown-item" href="/comunicados">Comunicados</a>
                                <a class="dropdown-item" href="/investidas">Investidas</a>
                                <a class="dropdown-item" href="/oracoes">Pedidos de Oração</a>
                                <a class="dropdown-item" href="/saude">Saúde &amp; Bem-Estar</a>
                                <a class="dropdown-item" href="/familia">Lar &amp; Família</a>
                                <a class="dropdown-item" href="/louvores">Louvores</a>
                            </div>
                            <div class="col-6">
                                <h6 class="dropdown-header">Formação</h6>
                                <a class="dropdown-item" href="/cursos">Cursos</a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Finanças</h6>
                                <a class="dropdown-item" href="/carteira">Carteira</a>
                                <a class="dropdown-item" href="/doacoes">Doações</a>
                                <a class="dropdown-item" href="/anunciar">Anúncios</a>
                                <a class="dropdown-item" href="/assinaturas">Assinaturas</a>
                                <a class="dropdown-item" href="/saques">Saques</a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Estrutura</h6>
                                <a class="dropdown-item" href="/regioes">Regiões</a>
                                <a class="dropdown-item" href="/distritos">Distritos</a>
                                <a class="dropdown-item" href="/igrejas">Igrejas</a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Ajuda &amp; Contas</h6>
                                <a class="dropdown-item" href="/configuracoes">Configurações</a>
                                <a class="dropdown-item" href="/termos">Termos</a>
                                <a class="dropdown-item" href="/privacidade">Privacidade</a>
                                <a class="dropdown-item" href="/logout">Sair</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="/chat">Chat</a></li>
                <li class="nav-item"><a class="nav-link" href="/login">Entrar</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?= $content ?? '' ?>
</main>
<div class="fixed-mobile-nav d-lg-none">
    <a href="/feed">Home</a>
    <a href="/chat">Chat</a>
    <a href="/reels">Reels</a>
    <a href="/menu">Menu</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1Z6X6pqlZB+0hZp6G7niu735Sk7lN9g" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
