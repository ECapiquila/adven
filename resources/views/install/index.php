<?php use App\Core\Security\Csrf; ?>
<?php $old = $old ?? []; $errors = $errors ?? []; $error = fn(string $field) => $errors[$field][0] ?? null; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color:#2E5EAA;color:#fff;">Instalação da Plataforma</div>
            <div class="card-body">
                <form method="POST" action="/install">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="mb-3">
                        <label class="form-label">Nome da Aplicação</label>
                        <input type="text" class="form-control <?= $error('app_name') ? 'is-invalid' : '' ?>" name="app_name" value="<?= htmlspecialchars($old['app_name'] ?? 'Rede Adventista') ?>">
                        <?php if ($error('app_name')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('app_name')) ?></div><?php endif; ?>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Driver</label>
                            <select class="form-select" name="db_driver">
                                <option value="mysql" <?= ($old['db_driver'] ?? '') === 'mysql' ? 'selected' : '' ?>>MySQL</option>
                                <option value="sqlite" <?= ($old['db_driver'] ?? '') === 'sqlite' ? 'selected' : '' ?>>SQLite</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Host</label>
                            <input type="text" class="form-control <?= $error('db_host') ? 'is-invalid' : '' ?>" name="db_host" value="<?= htmlspecialchars($old['db_host'] ?? '127.0.0.1') ?>">
                            <?php if ($error('db_host')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('db_host')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Porta</label>
                            <input type="text" class="form-control" name="db_port" value="<?= htmlspecialchars($old['db_port'] ?? '3306') ?>">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="form-label">Base de Dados</label>
                            <input type="text" class="form-control <?= $error('db_name') ? 'is-invalid' : '' ?>" name="db_name" value="<?= htmlspecialchars($old['db_name'] ?? 'adven') ?>">
                            <?php if ($error('db_name')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('db_name')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Utilizador</label>
                            <input type="text" class="form-control <?= $error('db_user') ? 'is-invalid' : '' ?>" name="db_user" value="<?= htmlspecialchars($old['db_user'] ?? 'root') ?>">
                            <?php if ($error('db_user')): ?><div class="invalid-feedback"><?= htmlspecialchars($error('db_user')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Senha</label>
                            <input type="password" class="form-control" name="db_password" value="<?= htmlspecialchars($old['db_password'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="alert alert-info mt-4">
                        A instalação criará o utilizador administrador <strong>admin@demo.com</strong> com a senha <strong>Admin@12345</strong>.
                    </div>
                    <button class="btn btn-primary" style="background-color:#5D8233;border:none;">Iniciar Instalação</button>
                </form>
            </div>
        </div>
    </div>
</div>
