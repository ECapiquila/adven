<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header" style="background-color:#2E5EAA;color:#fff;">Comunicados</div>
            <div class="card-body">
                <?php if (empty($comunicados)): ?>
                    <p class="mb-0">Sem comunicados por enquanto.</p>
                <?php else: ?>
                    <?php foreach ($comunicados as $item): ?>
                        <div class="mb-2"><?= htmlspecialchars($item['titulo']) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">Stories</div>
            <div class="card-body">Stories temporários aparecem aqui.</div>
        </div>
        <div class="card">
            <div class="card-header">Feed</div>
            <div class="card-body">
                <?php if (empty($feed['posts'])): ?>
                    <p class="mb-0">Partilha uma inspiração com a comunidade.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header" style="background-color:#5D8233;color:#fff;">Devocional</div>
            <div class="card-body">
                <p class="fw-semibold">Manhã</p>
                <p><?= htmlspecialchars($devocional['manha'] ?? 'Meditação do dia') ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Eventos</div>
            <div class="card-body">
                <?php if (empty($eventos)): ?>
                    <p class="mb-0">Nenhum evento registado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
