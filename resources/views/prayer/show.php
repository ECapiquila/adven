<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-2"><?= htmlspecialchars($prayer['title']) ?></h1>
        <p class="text-muted">Categoria: <?= htmlspecialchars($prayer['category']) ?> · Privacidade: <?= htmlspecialchars($prayer['privacy']) ?></p>
        <p><?= nl2br(htmlspecialchars($prayer['body'])) ?></p>
        <div class="mt-3 d-flex gap-2">
            <form method="POST" action="/oracao/<?= $prayer['id'] ?>/reagir">
                <input type="hidden" name="_token" value="<?= \App\Core\Security\Csrf::token() ?>">
                <input type="hidden" name="type" value="orar">
                <button class="btn btn-outline-primary btn-sm" type="submit">🙏 Orar</button>
            </form>
            <form method="POST" action="/oracao/<?= $prayer['id'] ?>/reagir">
                <input type="hidden" name="_token" value="<?= \App\Core\Security\Csrf::token() ?>">
                <input type="hidden" name="type" value="encorajar">
                <button class="btn btn-outline-success btn-sm" type="submit">👍 Encorajar</button>
            </form>
            <form method="POST" action="/oracao/<?= $prayer['id'] ?>/reagir">
                <input type="hidden" name="_token" value="<?= \App\Core\Security\Csrf::token() ?>">
                <input type="hidden" name="type" value="amor">
                <button class="btn btn-outline-danger btn-sm" type="submit">❤️ Amor</button>
            </form>
        </div>
        <hr>
        <form method="POST" action="/oracao/<?= $prayer['id'] ?>/responder">
            <input type="hidden" name="_token" value="<?= \App\Core\Security\Csrf::token() ?>">
            <div class="mb-3">
                <label class="form-label">Responder ao pedido</label>
                <textarea class="form-control" rows="3" name="body" required></textarea>
            </div>
            <button class="btn btn-primary" style="background-color:#5D8233;border:none;">Enviar Resposta</button>
        </form>
    </div>
</div>
