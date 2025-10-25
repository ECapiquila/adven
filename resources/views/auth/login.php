<section class="auth-card">
    <h1>Entrar</h1>
    <form method="POST" action="/login">
        <?= csrf_field() ?>
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Palavra-passe</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</section>
