<?php
/** @var array $palette */
/** @var array $flash */
$themeMeta = theme_meta();
$iconKey = $themeMeta['key'] ?? 'desbravadores';
$availableIcons = ['desbravadores', 'embaixadores', 'jovens'];
if (!in_array($iconKey, $availableIcons, true)) {
    $iconKey = 'desbravadores';
}
$iconPath = '/assets/icons/' . $iconKey . '.svg';
?>
<!DOCTYPE html>
<html lang="pt-AO" x-data="{dark: localStorage.getItem('dark-mode') ?? 'system'}" x-bind:class="{'theme-dark': dark === 'dark'}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="<?= htmlspecialchars($palette['primary'] ?? '#0d47a1', ENT_QUOTES) ?>">
    <title><?= htmlspecialchars($title ?? config('app.name', 'Sabbath Connect'), ENT_QUOTES) ?></title>
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($iconPath, ENT_QUOTES) ?>">
    <link rel="apple-touch-icon" href="<?= htmlspecialchars($iconPath, ENT_QUOTES) ?>">
    <link rel="stylesheet" href="/assets/css/app.css?v=<?= urlencode((string) config('app.assets_version', 1)) ?>">
    <style>
        :root {
            --primary: <?= htmlspecialchars($palette['primary'] ?? '#0d47a1', ENT_QUOTES) ?>;
            --secondary: <?= htmlspecialchars($palette['secondary'] ?? '#1976d2', ENT_QUOTES) ?>;
            --accent: <?= htmlspecialchars($palette['accent'] ?? '#ff7043', ENT_QUOTES) ?>;
            --light: <?= htmlspecialchars($palette['light'] ?? '#fafafa', ENT_QUOTES) ?>;
            --dark: <?= htmlspecialchars($palette['dark'] ?? '#102027', ENT_QUOTES) ?>;
            --muted: <?= htmlspecialchars($palette['muted'] ?? '#607d8b', ENT_QUOTES) ?>;
            --success: <?= htmlspecialchars($palette['success'] ?? '#2e7d32', ENT_QUOTES) ?>;
            --warning: <?= htmlspecialchars($palette['warning'] ?? '#ffb300', ENT_QUOTES) ?>;
            --info: <?= htmlspecialchars($palette['info'] ?? '#0288d1', ENT_QUOTES) ?>;
        }
    </style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body data-poll-interval="<?= htmlspecialchars((string) config('app.notifications.poll_interval', 30), ENT_QUOTES) ?>">
<nav class="navbar">
    <div class="container">
        <a href="/" class="brand">Sabbath Connect</a>
        <ul>
            <li><a href="/feed">Feed</a></li>
            <li><a href="/louvores">Louvores</a></li>
            <li><a href="/oracoes">Orações</a></li>
            <li><a href="/notificacoes">🔔</a></li>
            <li><a href="/login">Entrar</a></li>
        </ul>
    </div>
</nav>
<main class="container">
    <?php if (!empty($flash['success'])): ?>
        <div class="alert success"><?= htmlspecialchars($flash['success'], ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash['error'])): ?>
        <div class="alert error"><?= htmlspecialchars($flash['error'], ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?= $content ?>
</main>
<footer class="footer">
    <div class="container">
        <small>&copy; <?= date('Y') ?> Sabbath Connect. Todos os direitos reservados.</small>
    </div>
</footer>
<script src="/assets/js/app.js?v=<?= urlencode((string) config('app.assets_version', 1)) ?>" defer></script>
</body>
</html>
