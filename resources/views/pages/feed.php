<section class="feed">
    <header class="feed-header">
        <h1>Shalom, <?= htmlspecialchars($user['name'] ?? 'Visitante', ENT_QUOTES) ?></h1>
        <p>Este é o painel principal. Em breve terás stories, comunicados, devocionais, posts e muito mais.</p>
    </header>
    <div class="feed-preview">
        <div class="card story">Pré-visualização de Stories</div>
        <div class="card notice">Slider de Comunicados</div>
        <div class="card devotion">Devocional diário</div>
        <div class="card post">Criador de publicações</div>
        <div class="card announcement">Anúncio destacado</div>
    </div>
</section>
