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
                <div class="fw-semibold"><?= htmlspecialchars($message['sender_name'] ?? ('Utilizador #' . $message['sender_id'])) ?> <small class="text-muted"><?= htmlspecialchars($message['created_at']) ?></small></div>
                <?php if (!empty($message['is_staff_note'])): ?><span class="badge bg-info text-dark">Nota interna</span><?php endif; ?>
                <p class="mb-0"><?= nl2br(htmlspecialchars($message['body'])) ?></p>
            </div>
        <?php endforeach; ?>
        <form method="POST" action="/familia/t/<?= $thread['id'] ?>/mensagem" class="mt-4">
            <input type="hidden" name="_token" value="<?= \App\Core\Security\Csrf::token() ?>">
            <div class="mb-3">
                <label class="form-label">Nova mensagem</label>
                <textarea class="form-control" rows="3" name="body" required></textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_staff_note" value="1" id="staffNote">
                <label class="form-check-label" for="staffNote">Marcar como nota interna da equipa</label>
            </div>
            <button class="btn btn-primary" style="background-color:#2E5EAA;border:none;">Enviar</button>
        </form>
    </div>
</div>
