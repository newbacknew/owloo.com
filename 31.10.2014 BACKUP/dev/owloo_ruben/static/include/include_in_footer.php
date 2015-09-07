<div id="owloo_ajax_loader"></div>
    <?php if(get_current_page()!='account'){ ?><p id="back-top"><a href="#top"><span></span></a></p><?php } ?>
    <div id="owloo_preload_01"></div>
    <div id="owloo_msj_popup"><table><tr><td><div class="owloo_msj_popup_content"><table><tr><td><span class="owloo_close">x</span><div class="owloo_msj_popup_text"></div></td></tr></table></div></td></tr></table></div>
    <script type="text/javascript" src="<?=URL_JS?>jquery.min.js?v=1.1"></script>
    <script type="text/javascript" src="<?=URL_JS?>owloo.js?v=2.1"></script>
    <?php if(false/*get_current_page()=='index'*/){ ?><script type="text/javascript" src="<?=URL_JS?>owloo_growth_charts.js?v=2.1"></script><?php } ?>
    <?php if(get_current_page()=='country'){ ?><script type="text/javascript" src="<?=URL_JS?>owloo_country_charts.js?v=2.1&country=<?=convert_to_url_string(COUNTRY_DATA_NAME_EN)?>"></script><?php } ?>
    <?php if(get_current_page()=='twitter' || get_current_page()=='twitter_profile'){ ?><script type="text/javascript" src="<?=URL_JS?>jquery.ui.custom.min.js?v1.10.3"></script><?php } ?>
    <?php if(get_current_page()=='twitter' || get_current_page()=='twitter_profile'){ ?><script type="text/javascript" src="<?=URL_JS?>owloo_twitter.js?v2.0"></script><?php } ?>
    <?php if(get_current_page()=='twitter_profile' && (isset($hfflag) && $hfflag != 1)){ ?><script type="text/javascript">$.getScript('<?=URL_JS?>owloo_twitter_charts.js?screenname=<?=$screenname?>&v=1.0', function(){$('.owloo_country_content').removeClass('owloo_country_content_tw_chart');});</script><?php } ?>
    <?php if(get_current_page()=='page_profile' && empty($mensaje_new_fan_page)){ ?><script type="text/javascript" src="<?=URL_JS?>owloo_facebook_page.js?v2.0&page=<?=(isset($page_data)?$page_data['username']:'')?>"></script><?php } ?>
    <script src="//www.latamclick.com/feedfb"></script><script type="text/javascript">$.feedback({ajaxURL:'//www.latamclick.com/feedr',postIP:'<?=$_SERVER['REMOTE_ADDR']?>',postCODE:'owloo.com'});</script>