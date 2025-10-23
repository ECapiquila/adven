<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Saúde &amp; Bem-Estar</h1>
    <a href="/saude/postar" class="btn" style="background-color:#5D8233;color:#fff;">Partilhar Conteúdo</a>
</div>
<?php if (empty($posts)): ?>
    <div class="alert alert-info">Nenhum artigo publicado ainda.</div>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <span class="badge bg-primary text-uppercase mb-2"><?= htmlspecialchars($post['category']) ?></span>
                <h2 class="h5 mb-2"><?= htmlspecialchars($post['title']) ?></h2>
                <div class="small text-muted mb-2">Profissional verificado? <?= $post['is_verified_author'] ? 'Sim' : 'Não' ?></div>
                <p><?= $post['body_html'] ?></p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
