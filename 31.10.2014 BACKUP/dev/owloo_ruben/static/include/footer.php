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
            <div class="owloo_footer_sections">
                <div class="owloo_about_us">Servicios</div>
                <div class="owloo_footer_links">
                    <ul>
                        <li><a href="<?=URL_ROOT?>facebook-stats/pages/hispanic/" target="_blank">Estadísticas de las páginas</a></li>
                        <li><a href="<?=URL_ROOT?>facebook-stats/world-ranking/" target="_blank">Facebook por país y ciudad</a></li>
                        <li><a href="<?=URL_ROOT?>twitter-stats/" target="_blank">Análisis de Twitter</a></li>
                    </ul>
                </div>
            </div>
            <div class="owloo_footer_sections">
                <div class="owloo_about_us">Acerca de Owloo</div>
                <div class="owloo_footer_links">
                    <ul>
                        <li><a href="<?=URL_ROOT?>features/" target="_blank">Conoce Owloo</a></li>
                        <?php if(false){ ?><li><a href="#">Medios de comunicación</a></li><?php } ?>
                    </ul>
                </div>
            </div>
            <div class="owloo_description">
                    Owloo es una startup que ofrece monitoreo y seguimiento de las redes sociales, <br/>permitiendo el análisis y la comparación de las estadísticas e indicadores de los medios sociales.
            </div>
        </div>
    </div>
    <div class="owloo_footer_copyright">
        <div class="owloo_main">
            © 2014 Owloo, un producto de <a href="//www.latamclick.com" target="_blank" rel="nofollow">Latamclick S.A.</a> Todos los derechos reservados.
            <a class="owloo_privacy_link" href="<?=URL_ROOT?>privacy/" target="_blank">Políticas de privacidad</a>
        </div>
    </div>