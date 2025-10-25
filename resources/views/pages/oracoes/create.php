<section class="module">
    <header class="module-header">
        <h1>Novo Pedido de Oração</h1>
        <p>Partilhe o motivo do pedido e, opcionalmente, indique a sua igreja.</p>
    </header>
    <form class="stacked" method="POST" action="#" onsubmit="return false;">
        <?= csrf_field() ?>
        <label for="subject">Assunto</label>
        <input type="text" id="subject" name="subject" required>

        <label for="body">Descrição</label>
        <textarea id="body" name="body" rows="6" required></textarea>

        <label for="scope">Âmbito</label>
        <select id="scope" name="scope">
            <option value="church">Minha Igreja</option>
            <option value="district">Meu Distrito</option>
            <option value="region">Minha Região</option>
        </select>

        <button type="submit" class="btn btn-primary" disabled>Enviar (workflow em desenvolvimento)</button>
    </form>
</section>
