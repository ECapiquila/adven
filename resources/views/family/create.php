<?php use App\Core\Security\Csrf; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3">Abrir Conversa de Aconselhamento</h1>
                <form method="POST" action="/familia/abrir-conversa" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="mb-3">
                        <label class="form-label">Assunto</label>
                        <input type="text" class="form-control" name="subject">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" rows="5" name="body"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Privacidade</label>
                        <select class="form-select" name="privacy">
                            <option value="pastor_equipe">Pastor e equipa Lar &amp; Família</option>
                            <option value="igreja_limitada">Diretoria da igreja</option>
                        </select>
                    </div>
                    <button class="btn" style="background-color:#D4AF37;color:#2E5EAA;">Iniciar Conversa</button>
                </form>
            </div>
        </div>
    </div>
</div>
