<?php use App\Core\Security\Csrf; ?>
<?php $old = $old ?? []; $errors = $errors ?? []; $error = fn(string $field) => $errors[$field][0] ?? null; ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Criar Conta</h1>
                <form method="POST" action="/register">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control <?= $error('name') ? 'is-invalid' : '' ?>" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                            <?php if ($error('name')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('name')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Telemóvel (+244)</label>
                            <input type="tel" class="form-control <?= $error('phone') ? 'is-invalid' : '' ?>" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                            <?php if ($error('phone')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('phone')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" class="form-control <?= $error('email') ? 'is-invalid' : '' ?>" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                            <?php if ($error('email')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('email')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Senha</label>
                            <input type="password" class="form-control <?= $error('password') ? 'is-invalid' : '' ?>" name="password" required>
                            <?php if ($error('password')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('password')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sou Adventista?</label>
                            <select class="form-select" name="is_adventist">
                                <option value="1" <?= ($old['is_adventist'] ?? '1') === '1' ? 'selected' : '' ?>>Sim</option>
                                <option value="0" <?= ($old['is_adventist'] ?? '1') === '0' ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" name="gender">
                                <option value="f" <?= ($old['gender'] ?? '') === 'f' ? 'selected' : '' ?>>Feminino</option>
                                <option value="m" <?= ($old['gender'] ?? '') === 'm' ? 'selected' : '' ?>>Masculino</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">País</label>
                            <input type="text" class="form-control" name="country" value="<?= htmlspecialchars($old['country'] ?? 'Angola') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Província</label>
                            <input type="text" class="form-control <?= $error('province') ? 'is-invalid' : '' ?>" name="province" value="<?= htmlspecialchars($old['province'] ?? '') ?>">
                            <?php if ($error('province')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('province')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Município</label>
                            <input type="text" class="form-control <?= $error('municipio') ? 'is-invalid' : '' ?>" name="municipio" value="<?= htmlspecialchars($old['municipio'] ?? '') ?>">
                            <?php if ($error('municipio')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('municipio')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro" value="<?= htmlspecialchars($old['bairro'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Região (ID)</label>
                            <input type="number" class="form-control" name="region_id" value="<?= htmlspecialchars($old['region_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Distrito (ID)</label>
                            <input type="number" class="form-control" name="district_id" value="<?= htmlspecialchars($old['district_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Igreja (ID)</label>
                            <input type="number" class="form-control" name="church_id" value="<?= htmlspecialchars($old['church_id'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button class="btn" style="background-color:#5D8233;color:#fff;">Concluir Registo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
