<?php
function informar($mensaje){
	//Enviar aviso por email
	$para = 'hsteiner@latamclick.com, mmolinas@latamclick.com';
	$titulo = 'Owloo - Check countries';
	$cabeceras = 'From: owloo@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
}

function eliminaRegistrosHoy(){
	try{
		$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
		mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
		$sql = "DELETE FROM record_country WHERE date = DATE_FORMAT(now(),'%Y-%m-%d');";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
	}catch(Exception $e) {
		informar("Se ha detectado un error en la verificación de la captura de ranking de países. Favor verificar estado...");
		exit();
	}
}

function checkState(){
	$ban = 0;
	try{	
		$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
		mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
	
		$num_register = 0;
		$num_country = 0;
		
		$query = "SELECT count(*) num_register FROM record_country WHERE date = DATE_FORMAT(now(),'%Y-%m-%d') AND total_user is not null AND total_female is not null AND total_male is not null;"; 
		$que = mysql_query($query, $conexion) or die(mysql_error());
		if($fila = mysql_fetch_assoc($que)){	
			$num_register = $fila['num_register'];
		}
		
		$query = "SELECT count(*) num_country FROM country;"; 
		$que = mysql_query($query, $conexion) or die(mysql_error());
		if($fila = mysql_fetch_assoc($que)){	
			$num_country = $fila['num_country'];
		}
		
		mysql_close($conexion);
		
		if($num_register == $num_country) $ban = 1;
		
		return $ban;
	}
	catch(Exception $e) {
		informar("Se ha detectado un error en la verificación de la captura de ranking de países. Favor verificar estado...");
		exit();
	}
	
	return $ban;
}


if(!checkState()){
	eliminaRegistrosHoy();
	informar("Se ha detectado un error en la captura de ranking de países. Favor verificar estado...");
}


