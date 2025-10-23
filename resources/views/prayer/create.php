<?php use App\Core\Security\Csrf; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3">Novo Pedido de Oração</h1>
                <form method="POST" action="/oracoes" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="title" class="form-control" required>
                        <?php if (!empty($errors['title'])): ?><div class="text-danger small"><?= implode(', ', $errors['title']) ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoria</label>
                        <select name="category" class="form-select">
                            <option value="saude">Saúde</option>
                            <option value="familia">Família</option>
                            <option value="missao">Missão</option>
                            <option value="agradecimento">Agradecimento</option>
                            <option value="outro">Outro</option>
                        </select>
                        <?php if (!empty($errors['category'])): ?><div class="text-danger small"><?= implode(', ', $errors['category']) ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="body" class="form-control" rows="4" required></textarea>
                        <?php if (!empty($errors['body'])): ?><div class="text-danger small"><?= implode(', ', $errors['body']) ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Privacidade</label>
                        <select name="privacy" class="form-select">
                            <option value="publico">Público</option>
                            <option value="privado">Privado (Pastor/Anciãos)</option>
                        </select>
                        <?php if (!empty($errors['privacy'])): ?><div class="text-danger small"><?= implode(', ', $errors['privacy']) ?></div><?php endif; ?>
                    </div>
                    <?php if (!empty($errors['token'])): ?><div class="text-danger small mb-2"><?= implode(', ', $errors['token']) ?></div><?php endif; ?>
                    <button class="btn" style="background-color:#D4AF37;color:#2E5EAA;">Submeter Pedido</button>
                </form>
            </div>
        </div>
    </div>
</div>
