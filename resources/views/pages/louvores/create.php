<section class="module">
    <header class="module-header">
        <h1>Submeter Louvor</h1>
        <p>Faça o upload da pauta, letra e gravação demo para avaliação.</p>
    </header>
    <form class="stacked" method="POST" action="#" enctype="multipart/form-data" onsubmit="return false;">
        <?= csrf_field() ?>
        <label for="title">Título</label>
        <input type="text" id="title" name="title" placeholder="Nome do louvor" required>

        <label for="composer">Compositor</label>
        <input type="text" id="composer" name="composer" placeholder="Quem compôs a música">

        <label for="audio">Gravação demo (MP3)</label>
        <input type="file" id="audio" name="audio" accept="audio/mpeg">

        <label for="lyrics">Letra</label>
        <textarea id="lyrics" name="lyrics" rows="6"></textarea>

        <button type="submit" class="btn btn-primary" disabled>Enviar (em breve)</button>
    </form>
</section>
