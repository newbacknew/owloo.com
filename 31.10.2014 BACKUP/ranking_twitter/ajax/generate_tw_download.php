<?php require_once('../../owloo_config.php'); ?>
<table>
    <tr>
        <td>
            <div class="owloo_tw_download">
                <div class="owloo_close">X</div>
                <div class="owloo_ads_box owloo_ads_300x250"><?=get_owloo_ads('300x250')?></div>
                <div class="owloo_loader" id="owloo_tw_dow">
                    <div class="owloo_loading">
                        <div>Estamos preparando tu descarga</div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
<script>
    $(document).ready(function(){
        var my_time = 1;
        function myTimer() {
            my_time++;
            if(my_time > 9){
                $('#owloo_tw_dow').html('<a class="owloo_btn owloo_btn_blue" href="<?=URL_ROOT?>ranking_twitter/genera_csv/get_csv.php">Descargar ahora</a>');
                clearInterval(my_interval_time);
            }
        }
        var my_interval_time = setInterval(function(){myTimer()}, 1000);
    });
</script>