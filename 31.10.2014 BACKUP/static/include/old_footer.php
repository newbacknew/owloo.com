    <?php if(get_current_page() != 'country' && get_current_page() != 'twitter_profile' && get_current_page() != 'account' && get_current_page() != 'index') { ?>
    <div class="owloo_create_account">
        <div class="owloo_main">
            <div class="owloo_text">
                Crea una cuenta gratuita<br/>
                Descrube todas las funcionalidades de Owloo
            </div>
            <div class="owloo_button">
                <a class="owloo_btn owloo_btn_orange" href="<?=URL_ROOT_HTTPS?>userMgmt/signup.php">Regístrate hoy - GRATIS</a>
            </div>
        </div>
    </div>
    <?php }else{ ?>
    <div class="owloo_isologo_footer">
        <img src="<?=URL_IMAGES?>owloo_isologo_footer.png" alt="" />
    </div>
    <?php } ?>
    <div class="owloo_footer">
        <div class="owloo_main">
            <div class="owloo_about_us">Acerca de Owloo</div>
            <div class="owloo_footer_links">
                <ul>
                    <li><a href="<?=URL_ROOT?>features/" target="_blank">Conoce Owloo</a></li>
                    <?php if(false){ ?><li><a href="#">Medios de comunicación</a></li><?php } ?>
                </ul>
            </div>
            <div class="owloo_description">
                Owloo es una herramienta en español que ayuda a medir, comparar y hacer análisis de Facebook y Twitter.
            </div>
        </div>
    </div>
    <div class="owloo_footer_copyright">
        <div class="owloo_main">
            © 2014 Owloo, un producto de <a href="//www.latamclick.com" target="_blank" rel="nofollow">Latamclick S.A.</a> Todos los derechos reservados.
            <a class="owloo_privacy_link" href="<?=URL_ROOT?>privacy/" target="_blank">Políticas de privacidad</a>
        </div>
    </div>