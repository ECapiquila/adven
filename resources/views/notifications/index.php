<div class="card shadow-sm">
    <div class="card-header" style="background-color:#2E5EAA;color:#fff;">Notificações</div>
    <div class="card-body">
        <?php if (empty($items)): ?>
            <p class="text-muted mb-0">Sem notificações novas.</p>
        <?php else: ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($items as $notification): ?>
                    <?php $data = json_decode($notification['data_json'] ?? '{}', true); ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><strong><?= htmlspecialchars($notification['type']) ?></strong> · <?= htmlspecialchars($notification['created_at']) ?></span>
                        </div>
                        <pre class="mb-0" style="white-space: pre-wrap; background: transparent; border: none;"><?= htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
