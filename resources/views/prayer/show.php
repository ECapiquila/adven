<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-2"><?= htmlspecialchars($prayer['title']) ?></h1>
        <p class="text-muted">Categoria: <?= htmlspecialchars($prayer['category']) ?> · Privacidade: <?= htmlspecialchars($prayer['privacy']) ?></p>
        <p><?= nl2br(htmlspecialchars($prayer['body'])) ?></p>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm">🙏 Orar</button>
            <button class="btn btn-outline-success btn-sm">👍 Encorajar</button>
            <button class="btn btn-outline-danger btn-sm">❤️ Amor</button>
        </div>
    </div>
</div>
