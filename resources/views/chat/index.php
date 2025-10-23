<div class="row">
    <div class="col-lg-4 mb-3">
        <div class="list-group shadow-sm">
            <?php foreach ($threads as $thread): ?>
                <a href="/chat?thread=<?= $thread['id'] ?>" class="list-group-item list-group-item-action <?= ($activeThreadId == $thread['id']) ? 'active' : '' ?>">
                    Conversa #<?= $thread['id'] ?>
                </a>
            <?php endforeach; ?>
            <?php if (empty($threads)): ?>
                <div class="list-group-item text-muted">Sem conversas.</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color:#5D8233;color:#fff;">Mensagens</div>
            <div class="card-body" id="chat-messages" data-thread="<?= $activeThreadId ?>">
                <?php foreach ($messages as $message): ?>
                    <div class="mb-3" data-message="<?= $message['id'] ?>">
                        <div class="fw-semibold">Utilizador #<?= htmlspecialchars($message['sender_id']) ?> <small class="text-muted"><?= htmlspecialchars($message['created_at']) ?></small></div>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($message['body'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($activeThreadId): ?>
            <div class="card-footer">
                <form method="POST" action="/chat/enviar" class="d-flex gap-2 align-items-center">
                    <input type="hidden" name="_token" value="<?= \App\Core\Security\Csrf::token() ?>">
                    <input type="hidden" name="thread_id" value="<?= $activeThreadId ?>">
                    <input type="text" name="body" class="form-control" placeholder="Escreva uma mensagem" required>
                    <button class="btn btn-primary" style="background-color:#2E5EAA;border:none;">Enviar</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
