<?php use App\Core\Security\Csrf; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3">Partilhar Dica de Saúde</h1>
                <form method="POST" action="/saude/postar" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoria</label>
                        <select class="form-select" name="category">
                            <option value="nutricao">Nutrição</option>
                            <option value="exercicio">Exercício</option>
                            <option value="prevencao">Prevenção</option>
                            <option value="mental">Saúde mental</option>
                            <option value="familia">Família</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Conteúdo</label>
                        <textarea class="form-control" rows="5" name="body_html"></textarea>
                    </div>
                    <button class="btn" style="background-color:#5D8233;color:#fff;">Enviar para revisão</button>
                </form>
            </div>
        </div>
    </div>
</div>
