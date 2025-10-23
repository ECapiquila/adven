<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Pedidos de Oração</h1>
    <a href="/oracoes/criar" class="btn" style="background-color:#D4AF37;color:#2E5EAA;">Novo Pedido</a>
</div>
<?php if (empty($pedidos)): ?>
    <div class="alert alert-info">Ainda não existem pedidos. Crie o primeiro!</div>
<?php else: ?>
    <?php foreach ($pedidos as $pedido): ?>
        <div class="card mb-3 card-highlight">
            <div class="card-body">
                <h2 class="h5 mb-1"><?= htmlspecialchars($pedido['title']) ?></h2>
                <p class="text-muted mb-2">Categoria: <?= htmlspecialchars($pedido['category']) ?> · Estado: <?= htmlspecialchars($pedido['status']) ?></p>
                <p><?= nl2br(htmlspecialchars($pedido['body'])) ?></p>
                <a href="/oracao/<?= (int) $pedido['id'] ?>" class="btn btn-sm btn-outline-primary">Detalhes</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
