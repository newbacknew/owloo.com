<?php

    $screenname = "katyperry";
    
    $fetch_cntr = '{"0":"7","owloo_user_id":"7","1":"21447363","owloo_user_twitter_id":"21447363","2":"KATY PERRY ","owloo_user_name":"KATY PERRY ","3":"katyperry","owloo_screen_name":"katyperry","4":"http:\/\/pbs.twimg.com\/profile_images\/423542935368380416\/ryEG2fNO_normal.jpeg","owloo_user_photo":"http:\/\/pbs.twimg.com\/profile_images\/423542935368380416\/ryEG2fNO_normal.jpeg","5":"CURRENTLY\u2728BEAMING\u2728ON THE PRISMATIC WORLD TOUR 2014!","owloo_user_description":"CURRENTLY\u2728BEAMING\u2728ON THE PRISMATIC WORLD TOUR 2014!","6":"","owloo_user_location":"","7":"en","owloo_user_language":"en","8":"1","owloo_user_verified_account":"1","9":"Alaska","owloo_user_timezone":"Alaska","10":"Fri Feb 20 23:45:56 +0000 2009","owloo_user_created_on":"Fri Feb 20 23:45:56 +0000 2009","11":"56742350","owloo_followers_count":"56742350","12":"157","owloo_following_count":"157","13":"6070","owloo_tweetcount":"6070","14":"143401","owloo_listed_count":"143401","15":"1","owloo_user_status":"1","16":"2013-07-10","owloo_created_on":"2013-07-10","17":"2014-09-10","owloo_updated_on":"2014-09-10"}';
	$fetch_cntr = json_decode($fetch_cntr, true);
    
//echo '<h1>EL usuario ya ha sido actualizado hoy</h1>';	
	$twitter_id = $fetch_cntr['owloo_user_twitter_id'];
	$update_id = $fetch_cntr['owloo_user_id'];
	$twitter_name = $fetch_cntr['owloo_user_name'];
	$screen_name = $fetch_cntr['owloo_screen_name'];
	$profile_image_url = $fetch_cntr['owloo_user_photo'];
	$description = $fetch_cntr['owloo_user_description'];
	$location = $fetch_cntr['owloo_user_location'];
	$lang = $fetch_cntr['owloo_user_language'];
	$verified = $fetch_cntr['owloo_user_verified_account'];
	$timezone = $fetch_cntr['owloo_user_timezone'];
	$creationdate = $fetch_cntr['owloo_user_created_on'];
	$followers_count = $fetch_cntr['owloo_followers_count'];
	$following_count = $fetch_cntr['owloo_following_count'];
	$tweetcount = $fetch_cntr['owloo_tweetcount'];
	$listedcount = $fetch_cntr['owloo_listed_count'];
	$hfflag = 0;
	$user_not_udate = true; //Para evitar que vuelva a actualizarse

?>
    <div class="owloo_main">
        <div class="owloo_country_section">
            <h2>
                 Resumen de las estadísticas
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 1</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                 <div class="owloo_left">
                    <table>
                        <tbody>
                            <tr>
                                <td class="owloo_country_table_th">Seguidores</td>
                                <td class="owloo_country_table_th owloo_country_table_1"><?=owloo_number_format($followers_count)?></td>
                            </tr>
                            <tr>
                                <td>Siguiendo</td>
                                <td><?=owloo_number_format($following_count)?></td>
                            </tr>
                            
                            <tr>
                                <td>Tweets</td>
                                <td><?=owloo_number_format($tweetcount)?></td>
                            </tr>
                            <tr>
                                <td>Cuenta verificada</td>
                                <td><?php
                                        if($verified == 1){
                                            $verimg = '<img src="'.URL_IMAGES.'owloo_user_verified_account.png" class="owloo_tooltip" title="Cuenta verificada" alt="Si"/>';
                                        }else{
                                            $verimg = '<img src="'.URL_IMAGES.'owloo_tw_not_verified.png" class="owloo_tooltip" title="Cuenta no verificada" alt="No"/>';
                                        }
                                        echo $verimg;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>En Owloo desde</td>
                                <td><?php
                                        $date = '10-Jul-2013';
                                        echo $date;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Localización</td>
                                <td><?=(!empty($location)?$location:'<span class="owloo_not_change_audition"><em>n/a</em></span>')?></td>
                            </tr>
                            <tr>
                                <td>Idioma</td>
                                <td><?php
                                        $language  = '';
                                        if ($lang == "en") {
                                            $language = 'Inglés';
                                        }
                                        if ($lang == "es") {
                                            $language = 'Español';
                                        }
                                        echo $language;?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="owloo_right">
                    <p>
                        <div class="owloo_tw_profile">
                            <a href="https://twitter.com/<?=$screen_name?>" rel="nofollow" target="_blank">
                                <img src="<?=convert_imagen_to_https($profile_image_url)?>" alt="" title="" />
                            </a>
                            <a  class="owloo_resume_tw_user_title" href="https://twitter.com/<?=$screen_name?>" rel="nofollow" target="_blank">
                                <?=$twitter_name?> (@<?=$screen_name?>)
                            </a><br/>
                            <?=$description?>.
                        </div>
                    </p>
                    <p>
                        <strong><?=$twitter_name?></strong> cuenta con <strong><?=owloo_number_format($followers_count)?> seguidores</strong> en todo el mundo, con <strong><?=owloo_number_format($tweetcount)?> tweets</strong> publicados. Actualmente le está <strong>siguiendo a <?=owloo_number_format($following_count)?> perfiles</strong>.
                    </p>
                </div>
            </div>
        </div>

        <div>
            <div class="owloo_country_section ">
                <h2>
                    Crecimiento de seguidores de <?=$twitter_name?>
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 2</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_more">
                         <div class="owloo_tw_charts_content" id="owloo_tw_chart_1"></div>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_less">
                        <p id="owloo_tw_chart_desc_1">
                            
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Tweets por hora durante todo el periodo
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 3</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_less">
                        <p id="owloo_tw_chart_desc_2">
                            
                        </p>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_more">
                        <div class="owloo_tw_charts_content" id="owloo_tw_chart_2"></div>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Tweets publicados por día
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 4</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_more">
                         <div class="owloo_tw_charts_content" id="owloo_tw_chart_3"></div>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_less">
                        <p id="owloo_tw_chart_desc_3">
                            
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Perfiles más mencionados
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 5</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_more">
                         <p id="owloo_tw_chart_desc_4">
                            
                         </p>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_less">
                        <div class="owloo_tw_table_content" id="owloo_tw_chart_4"></div>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Hashtags más usados
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 6</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_less">
                         <div class="owloo_tw_table_content" id="owloo_tw_chart_5"></div>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_more">
                        <p id="owloo_tw_chart_desc_5">
                            
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Descarga las estadísticas de @<?=$screen_name?>
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 7</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content">
                     <div class="owloo_tw_download">
                        <div id="owloo_tw_download_sl_btn" class="owloo_btn owloo_btn_big owloo_btn_orange">Descargar ahora</div>
                        <h4 class="owloo_tw_download_stats">Reporte completo con datos históricos</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>