<?php
    require_once('../../owloo_config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="http://www.owloo.com/static/js/jquery.min.js?v=1.1"></script>
    <script type="text/javascript" src="<?=URL_ROOT?>ranking_twitter/ajax/social-locker.js"></script>
    <script type="text/javascript">
    jQuery.noConflict();                    
    jQuery(document).ready(function ($) {
        $("#lock-my-div").sociallocker({
            text: {
                header: "El contenido está bloqueado!", // replace content with this heading
                message: "Comparte para poder ver el contenido." // hidden content message
            },

            theme: "starter", // Theme

            locker: {
                close: false,
                timer: 0
            },

            buttons: {   // Buttons you want to show on box
                order: ["facebook-like", "twitter-tweet", "google-plus"] 
            },

            facebook: {  
                appId: "554618394649460",
                like: {
                    title: "Me gusta",
                    url: jQuery('#owloo-social-unlock').attr('data-url') // link to like in Facebook button
                },
                share: {
                    title: "Compartir",
                    url: jQuery('#owloo-social-unlock').attr('data-url') // link to like in Facebook button
                }
            },

            twitter: {
                tweet: {
                    title: "Tweet", 
                    text: jQuery('#owloo-social-unlock').attr('data-tweet'), // tweet text
                    url: jQuery('#owloo-social-unlock').attr('data-url') //tweet link
                }
            },

            google: {                                
                plus: {
                    title: "Plus +1",
                    url: jQuery('#owloo-social-unlock').attr('data-url') // Google plus link for +1
                }
            },

            linkedin: {
                url: jQuery('#owloo-social-unlock').attr('data-url'),      // LinkedIn url to share 
                share: {
                    title: "Compartir"
                }
            }
        });
    });
</script>
<style type="text/css">
    #lock-my-div {background: none repeat scroll 0 0 #FFFFFF !important;}
    .jo-sociallocker {bmargin-left: auto;margin-right: auto;text-align: center;}
    .jo-sociallocker-button{display: inline-block;vertical-align: top;}
    .fb_iframe_widget span {vertical-align: top !important;}
    .jo-sociallocker-button.jo-sociallocker-button-facebook {margin-right: 30px;}
</style>
</head>
<body>
    <div class="owloo_description">
        Para descargar las estadísticas completas de @<?php echo 'katyperry'; ?> con datos históricos, por favor compárte primero esta página
    </div>
    <div id="highly-configurable">
        <div id="lock-my-div" style="display: none;" class="owloo_tw_download_sl_content to-lock"  data-url="<?=URL_ROOT?>twitter-stats/userpage/<?php echo 'katyperry'; ?>" data-tweet="Estadísticas de @<?php echo 'katyperry'; ?> en Twitter http://owloo.com/twitter-stats/userpage/<?php echo 'katyperry'; ?> #owloo">
            <a class="owloo_btn owloo_btn_blue owloo_btn_big" href="<?=URL_ROOT?>ranking_twitter/genera_csv/get_csv.php">Descargar ahora</a>
        </div>
    </div>
</body>
</html>