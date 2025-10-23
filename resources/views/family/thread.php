<div class="card">
    <div class="card-body">
        <h1 class="h4">Conversação Familiar</h1>
        <div class="mb-3">
            <span class="badge bg-secondary">Status: <?= htmlspecialchars($thread['status']) ?></span>
            <span class="badge bg-warning text-dark">Privacidade: <?= htmlspecialchars($thread['privacy']) ?></span>
        </div>
        <p><?= nl2br(htmlspecialchars($thread['body'])) ?></p>
        <hr>
        <h2 class="h5">Mensagens</h2>
        <?php foreach ($messages as $message): ?>
            <div class="mb-3">
                <div class="fw-semibold"><?= htmlspecialchars($message['author']) ?> <small class="text-muted"><?= htmlspecialchars($message['created_at']) ?></small></div>
                <p class="mb-0"><?= nl2br(htmlspecialchars($message['body'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
