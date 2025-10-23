<?php use App\Core\Security\Csrf; ?>
<?php $old = $old ?? []; $errors = $errors ?? []; $error = fn(string $field) => $errors[$field][0] ?? null; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3">Abrir Conversa de Aconselhamento</h1>
                <form method="POST" action="/familia/abrir-conversa" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="mb-3">
                        <label class="form-label">Assunto</label>
                        <input type="text" class="form-control <?= $error('subject') ? 'is-invalid' : '' ?>" name="subject" value="<?= htmlspecialchars($old['subject'] ?? '') ?>">
                        <?php if ($error('subject')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('subject')) ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control <?= $error('body') ? 'is-invalid' : '' ?>" rows="5" name="body"><?= htmlspecialchars($old['body'] ?? '') ?></textarea>
                        <?php if ($error('body')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('body')) ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Privacidade</label>
                        <select class="form-select" name="privacy">
                            <option value="pastor_equipe" <?= ($old['privacy'] ?? '') === 'pastor_equipe' ? 'selected' : '' ?>>Pastor e equipa Lar &amp; Família</option>
                            <option value="igreja_limitada" <?= ($old['privacy'] ?? '') === 'igreja_limitada' ? 'selected' : '' ?>>Diretoria da igreja</option>
                        </select>
                    </div>
                    <button class="btn" style="background-color:#D4AF37;color:#2E5EAA;">Iniciar Conversa</button>
                </form>
            </div>
        </div>
    </div>
</div>
