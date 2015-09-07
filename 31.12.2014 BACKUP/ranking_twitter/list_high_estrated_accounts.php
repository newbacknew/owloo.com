<?php

$cont = 1;
//Paginador
$pp = 25; // cantidad de elementos que se mostraran por pagina
$cr = 0; // cantidad de elementos 
$start = 0; // DEL PAGINADOR
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#owloo_container_country_data table tr').hover(
			function(){
				$(this).find("div.owloo_cont_list_view_more").css('display', 'inline');
				if($(this).prev().find("td"))
					$(this).prev().find("td").css('border-bottom', '1px solid #FFF');
				$(this).find("td").css('border-top', '1px solid #8ECEEE');
				$(this).find("td").css('border-bottom', '1px solid #8ECEEE');
			},
			function(){
				$(this).find("div.owloo_cont_list_view_more").css('display', 'none');
				if($(this).prev().find("td"))
					$(this).prev().find("td").css('border-bottom', '1px solid #E9E9E9');
				$(this).find("td").css('border-top', '1px solid #FFF');
				$(this).find("td").css('border-bottom', '1px solid #E9E9E9');
			}
		);
	});
</script>
<div id="owloo_container_country_data" class="owloo_container_twitter_followers">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th width="8%" scope="col">RANK</th>
            <th width="75%" scope="col">PERFIL</th>
            <th width="17%" scope="col" class="owloo_td_last_child">VALORACIÓN</th>
		</tr>
	<?php
	
	$qry = "";
	$qry = " SELECT sum( owloo_rating_points ) AS count, b.owloo_screen_name, b.owloo_user_photo, b.owloo_user_verified_account, b.owloo_user_id";
	$qry = $qry . " FROM owloo_user_rating a LEFT JOIN owloo_user_master b";
	$qry = $qry . " ON a.owloo_user_id = b.owloo_user_id";
	$qry = $qry . " GROUP BY a.owloo_user_id";
	$qry = $qry . " ORDER BY count DESC";
	$qry = $qry . " LIMIT 0, 100";
	$qrydata = mysql_query($qry);
	while ($fetchdata = mysql_fetch_array($qrydata)){
		
		//Paginador
		$cr++;
		$fshow = "";
		if($cr < $start+1) { $fshow='';}
		if($cr > $start + $pp) { $fshow=' inactive';}
		//paginador
		
		$acnm = "";
		$acnm = '<a href="' . URL_ROOT . 'twitter.php?page=userpage&twittername=';
		$acnm = $acnm . $fetchdata["owloo_screen_name"] . '">';
		$acnm = $acnm . '@' . $fetchdata["owloo_screen_name"];
		$acnm = $acnm . "</a>";
		
		?>
		<tr id="fn_<?=$cr?>"  class="<?=$fshow?>">
			<td><span><?=str_pad($cont++, 2, "0", STR_PAD_LEFT);?></span></td>
			<td><div class="owloo_td_country_name"><img class="owloo_user_twitter_small" src="<?=str_replace('_normal.', '_mini.', $fetchdata["owloo_user_photo"]); ?>" title="" alt="" /><?='<a href="'.URL_ROOT.'twitter-stats/userpage/'.$fetchdata["owloo_screen_name"].'">@'.$fetchdata["owloo_screen_name"].'</a>'?><?php if($fetchdata["owloo_user_verified_account"]){?><img class="owloo_user_twitter_verified tooltip" src="<?=URL_IMAGES.'/owloo_user_verified_account.png'?>" title="Cuenta verificada" alt="" /><?php } ?><div class="owloo_cont_list_view_more owloo_cont_list_view_more_tw"><div class="owloo_view_details"><a class="owloo_menu_header_blue" href="<?=URL_ROOT.'twitter.php?page=userpage&twittername='.$fetchdata["owloo_screen_name"]?>">ver estadísticas</a><a class="owloo_menu_header_orange" href="<?=URL_ROOT.'buy-twitter-stats.php?username='.$fetchdata["owloo_screen_name"].'&userid='.$fetchdata["owloo_user_id"]?>">comprar</a></div></div></div></td>
			<td class="owloo_td_last_child"><?=number_format($fetchdata["count"], 0, '.', ' ')?></td>
		</tr>
		<?php
		
	} ?>
	</table>
</div>
<?php
//Paginador
?>

<!-- Paginador -->
<div class="navcontainer">
<script language="javascript" type="text/javascript">
function next(var_phopag, var_pag){
	var_show = (var_phopag+<?=$pp?>);
	var_total = <?=$cr?>;
	for(i=0; i<=var_total; i++){
		if(i<var_phopag || i>=var_show){
			$("#fn_"+i).addClass("inactive"); 
		}
		else if(i>var_phopag || i<var_show){
			$("#fn_"+i).removeClass("inactive"); 
		}
	}
	var_total_pag = Math.ceil(<?=$cr?>/<?=$pp?>);
	for(cp=0; cp<=var_total_pag; cp++){
		if(cp==var_pag){
			$("#nav_"+cp).addClass("pactive");
		}
		else{
			$("#nav_"+cp).removeClass("pactive");
		}
	}
	
	if(var_pag < 7){
		for(cpc=0; cpc<=var_total_pag; cpc++){
			if(cpc <= 7){
				$("#nav_"+cpc).removeClass("inactive");
			} else {
				$("#nav_"+cpc).addClass("inactive");
			}
		}
		if(var_total_pag > 7){
			$("#nav_"+var_total_pag).removeClass("inactive");
		}
		
		$("#no-pag-ini,#no-pag-fin").removeClass("inactive");
		$("#no-pag-ini").addClass("inactive");
	}
	else if(var_pag > (var_total_pag - 6)){
		for(cpc=0; cpc<=var_total_pag; cpc++){
			if(cpc > var_total_pag - 7){
				$("#nav_"+cpc).removeClass("inactive");
			} else {
				$("#nav_"+cpc).addClass("inactive");
			}
		}
		if(var_total_pag > 1){
			$("#nav_1").removeClass("inactive");
		}
		
		$("#no-pag-ini,#no-pag-fin").removeClass("inactive");
		$("#no-pag-fin").addClass("inactive");
	}
	else{
		for(cpc=0; cpc<=var_total_pag; cpc++){
			if(cpc > (var_pag - 3) && cpc < (var_pag + 3)){
				$("#nav_"+cpc).removeClass("inactive");
			} else {
				$("#nav_"+cpc).addClass("inactive");
			}
		}

		$("#nav_1").removeClass("inactive");
		$("#nav_"+var_total_pag).removeClass("inactive");
		
		$("#no-pag-ini,#no-pag-fin").removeClass("inactive");
	}
	
}
</script>
<?php
for($p=0; $p*$pp <  $cr; $p++){
	$pactive = ($p*$pp) == $start ? "pactive" : "" ;
	$pc = $p+1;
	$pf = "";
	if($pc>7) {
		$pf = "inactive";
		if($pc >= ($cr / $pp)){
			$pf = 'no-pag';
			echo '<div class="nav page-space '.$pf.'" id="no-pag-fin" >...</div>';
		}
	}
	else
	if($pc == 2){
		echo '<div class="nav page-space inactive" id="no-pag-ini" >...</div>';
	}
	
	?> 
	<div onclick="next(<?=(($p*$pp)+1)?>, <?=($p+1)?>);" class="nav <?=$pactive?> <?=$pf?>" id="nav_<?=($p+1)?>"><?=($p+1)?></div>
	<?php 
}
//FIN Paginador
?>
</div>
<img class="owloo_small" src="<?=URL_IMAGES?>owloo_logo_small.png" width="55" height="17" alt="owloo" title="" />
<div class="owloo_nn"></div>