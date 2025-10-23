<?php use App\Core\Security\Csrf; ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Iniciar Sessão</h1>
                <form method="POST" action="/login">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="mb-3">
                        <label class="form-label">E-mail ou Telemóvel</label>
                        <input type="text" class="form-control" name="login" required>
                        <?php if (!empty($errors['login'])): ?>
                            <div class="text-danger small"><?= implode(', ', $errors['login']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" class="form-control" name="password" required>
                        <?php if (!empty($errors['password'])): ?>
                            <div class="text-danger small"><?= implode(', ', $errors['password']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($errors['token'])): ?>
                        <div class="text-danger small mb-2"><?= implode(', ', $errors['token']) ?></div>
                    <?php endif; ?>
                    <button class="btn w-100" style="background-color:#2E5EAA;color:#fff;">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
