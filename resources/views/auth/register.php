<?php use App\Core\Security\Csrf; ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Criar Conta</h1>
                <form method="POST" action="/register">
                    <input type="hidden" name="_token" value="<?= Csrf::token() ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Telemóvel (+244)</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Senha</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sou Adventista?</label>
                            <select class="form-select" name="is_adventist">
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" name="gender">
                                <option value="f">Feminino</option>
                                <option value="m">Masculino</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">País</label>
                            <input type="text" class="form-control" name="country" value="Angola">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Província</label>
                            <input type="text" class="form-control" name="province">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Município</label>
                            <input type="text" class="form-control" name="municipality">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="neighborhood">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button class="btn" style="background-color:#5D8233;color:#fff;">Concluir Registo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
