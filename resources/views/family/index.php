<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Lar &amp; Família</h1>
    <a href="/familia/abrir-conversa" class="btn" style="background-color:#D4AF37;color:#2E5EAA;">Nova Conversa</a>
</div>
<?php if (empty($threads)): ?>
    <div class="alert alert-info">Sem conversas activas.</div>
<?php else: ?>
    <?php foreach ($threads as $thread): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h2 class="h5 mb-1"><?= htmlspecialchars($thread['subject']) ?></h2>
                <p class="text-muted">Estado: <?= htmlspecialchars($thread['status']) ?></p>
                <a href="/familia/t/<?= (int) $thread['id'] ?>" class="btn btn-outline-primary btn-sm">Abrir</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
