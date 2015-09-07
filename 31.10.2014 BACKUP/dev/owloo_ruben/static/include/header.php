    <div id="fb-root"></div>
    <div id="alertMessage"></div>
    <div class="owloo_main">
        <div class="owloo_header">
            <div class="owloo_logo">
                <div class="owloo_logo_owloo">
                    <a href="<?=URL_ROOT?>">
                        <img src="<?=URL_IMAGES?>owloo_logo.png" alt="owloo" />
                    </a>
                </div>
                <div class="owloo_logo_latam">
                    <img src="<?=URL_IMAGES?>powered_by_latamclick.png" alt="Powered by Latamclick" />
                </div>
            </div>
            <div class="owloo_search">
                <?php if(get_current_page() == 'twitter'){ ?>
                <div class="owloo_search_twitter_profile">
                    <input type="text" class="owloo_text_search" id="owloo_txtsearch" value="Buscar un perfil de Twitter" onfocus="if(this.value=='Buscar un perfil de Twitter') this.value = '';" onblur="if(this.value=='') this.value = 'Buscar un perfil de Twitter';" />
                </div>
                <?php }else{ ?>
                <div class="owloo_select">
                    <div id="<?=((get_current_page()== 'fb_ranking' || get_current_page()== 'page_profile' || get_current_page()== 'fb_ranking_country')?'owloo_select_country_fb':'owloo_select_country')?>"></div>    
                </div>
                <?php } ?>
                <?php if(false){ ?><a href="#" class="owloo_view_fb_tw_ranking">Ver Ranking Facebook y Twitter</a><?php } ?>
                <div class="owloo_clear"></div>
            </div>
            <div class="owloo_clear"></div>
        </div>
    </div>
    <div class="owloo_menu_content">
        <div class="owloo_main">
            <div class="owloo_menu">
                <ul>
                    <li><a class="owloo_first<?=get_current_menu()=='index'?' owloo_active':''?>" href="<?=URL_ROOT?>">Inicio</a></li>
                    <li><a class="<?=get_current_menu()=='facebook'?'owloo_active':''?>" href="<?=URL_ROOT?>facebook-stats/">Facebook</a></li>
                    <li><a class="<?=get_current_menu()=='twitter'?'owloo_active':''?>" href="<?=URL_ROOT?>twitter-stats/">Twitter</a></li>
                    <li><a class="<?=get_current_menu()=='features'?'owloo_active':''?>" href="<?=URL_ROOT?>features/">Acerca de Owloo</a></li>
                    <li><a class="feedback-btn owloo_last<?=get_current_menu()=='support'?' owloo_active':''?>" href="#">Ayuda</a></li>
                </ul>
                <div class="owloo_login_content" id="owloo_login">
                    <?php if(false){ ?>
                    <a class="owloo_btn owloo_btn_blue owloo_login" href="<?=URL_ROOT_HTTPS?>userMgmt/login.php">Iniciar Sesión</a>
                    <a class="owloo_btn owloo_btn_orange" href="<?=URL_ROOT_HTTPS?>userMgmt/signup.php">Regístrate hoy - GRATIS</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="owloo_clear"></div> 
    </div>