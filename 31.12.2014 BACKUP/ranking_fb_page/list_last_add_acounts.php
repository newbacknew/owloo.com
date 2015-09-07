<?php
$qry = "";
	$qry = "SELECT username, name, about, description, picture, date_add FROM facebook_page WHERE (about IS NOT NULL OR description IS NOT NULL) AND active = 1";
	$qry = $qry . " Order By date_add DESC, id_page DESC";
	$qry = $qry . " LIMIT 0, 4";
    
	$qrydata = mysql_query($qry);
	while ($fetchdata = mysql_fetch_array($qrydata)){?>
        <div class="owloo_wrap_tw_last_add">
            <div>
                <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fetchdata["username"])?>/" title="Estadísticas de <?=$fetchdata['name']?> en Facebook"><img alt="<?=$fetchdata["name"]?>" class="owloo_tw_last_add_img" src="<?=convert_imagen_to_https($fetchdata["picture"])?>"></a>
            </div>
            <div>
                <div><a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fetchdata["username"])?>/" title="Estadísticas de <?=$fetchdata['name']?> en Facebook"><?=$fetchdata["name"]?></a></div>
                <div><?=(!empty($fetchdata["description"])?$fetchdata["description"]:$fetchdata["about"])?></div>
            </div>
        </div>
<?php } ?>