<?php require_once('../../owloo_config.php'); ?>
function load_twitter_avatar(){
    $(".owloo_user_twitter_avatar").each(function (e) {
        $(this).attr('src', $(this).attr('id'));
    })
}
window.setTimeout(function(){ load_twitter_avatar() }, 1000);
$(function(){
    $("#owloo_txtsearch").autocomplete({source: "<?=URL_ROOT?>ranking_twitter/search_profile.php",minLength: 2,select: productoSeleccionado,focus: productoFoco });
    function productoFoco(event, ui){  event.preventDefault(); } 
    function productoSeleccionado(event, ui){
        if(ui.item.value != 0)
            window.location= '<?=URL_ROOT?>twitter-stats/userpage/'+ui.item.label;
        event.preventDefault();
    }
});