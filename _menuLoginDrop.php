<a href="#" class="button small rounded no-page-fade no-label-on-mobile no-margin-bottom"><span class="icon-lock left"></span><span>Entrar</span></a>
<div class="dropdown-list custom-content">
    <h5>Acessar área de membro</h5>
    <div class="login-form-container">
        <form class="login-form" method="POST" action="_loginmembro.php">
            <div class="row">
                <div class="column width-12">
                    <div class="field-wrapper">
                        <input type="text" name="cpf" minlength="4" maxlength="60" class="form-name form-element small" placeholder="Seu e-mail, CPF ou Matrícula">
                    </div>
                </div>
                <div class="column width-12">
                    <div class="field-wrapper">
                        <input type="password" minlength="2" maxlength="30" name="senha" class="form-email form-element small" placeholder="Sua senha.">
                    </div>
                </div>
                <div class="column width-6 pb-10">
                    <button type="submit" name="btn-acessarusuario" value="ENTRAR" class="column full-width button small bkg-blue border-hover-blue rounded hard-shadow"><span class="text-medium weight-bold color-white color-hover-blue">Entrar</span></button>
                </div>
                <div class="column width-6 pb-10 right">
                    <a data-content="inline" data-aux-classes="tml-newsletter-modal" data-toolbar="" data-modal-mode data-modal-width="600" data-lightbox-animation="fadeIn" class="lightbox-link text-small color-charcoal" href="#recuperarsenhauser">Recuperar senha.</a>
                </div>
                <!-- <div class="column width-12">
                    <a href="<?php echo $loginUrl; ?>" class="column full-width button small bkg-facebook bkg-hover-facebook color-white color-hover-white rounded hard-shadow">
                        <span class="icon-facebook small left"></span>  Login com Facebook
                    </a>
                </div> -->
            </div>
        </form>
        <div class="form-response"></div>
    </div>
</div>