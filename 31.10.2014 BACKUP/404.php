<?php
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    set_current_menu('404');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noindex,nofollow">
    
    <title>Página no encontrada</title>
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
    
    <script type="text/javascript">window.setTimeout(function(){ window.location = '<?=URL_ROOT?>'; }, 15000);</script>
    
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            Página no encontrada
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <div class="owloo_404_error">
            <div class="owloo_title">404</div>
            <div class="owloo_description">Esta página no existe o fue removida.</div>
            <div class="owloo_link"><a href="<?=URL_ROOT?>">Seguir navegando &gt; </a></div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
</body>
</html>