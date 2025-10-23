<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-header" style="background-color:#2E5EAA;color:#fff;">Atribuir Cargo</div>
            <div class="card-body">
                <form method="POST" action="/gerenciar/cargos">
                    <input type="hidden" name="_token" value="<?= \App\Core\Security\Csrf::token() ?>">
                    <div class="mb-3">
                        <label class="form-label">Utilizador</label>
                        <select class="form-select" name="user_id" required>
                            <option value="">Seleccione</option>
                            <?php foreach (($users ?? []) as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cargo</label>
                        <select class="form-select" name="role_name" required>
                            <option value="">Seleccione</option>
                            <?php foreach (($roles ?? []) as $role): ?>
                                <option value="<?= htmlspecialchars($role['name']) ?>"><?= htmlspecialchars($role['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Igreja</label>
                        <select class="form-select" name="church_id" required>
                            <option value="">Seleccione</option>
                            <?php foreach (($churches ?? []) as $church): ?>
                                <option value="<?= $church['id'] ?>"><?= htmlspecialchars($church['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $fieldErrors): ?>
                                <?php foreach ($fieldErrors as $message): ?>
                                    <div><?= htmlspecialchars($message) ?></div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <button class="btn btn-primary w-100" style="background-color:#5D8233;border:none;">Atribuir</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color:#D4AF37;">Atribuições Recentes</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Utilizador</th>
                            <th>Cargo</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (($recentAssignments ?? []) as $assignment): ?>
                            <tr>
                                <td><?= htmlspecialchars($assignment['user_name']) ?></td>
                                <td><?= htmlspecialchars($assignment['role_name']) ?></td>
                                <td><?= htmlspecialchars($assignment['created_at']) ?></td>
                                <td>
                                    <a href="/gerenciar/cargos/<?= $assignment['id'] ?>/revogar" class="btn btn-sm btn-outline-danger">Revogar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
