<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
 
	require_once('system/initiater.php');
	$site->restricted_page('login.php');
	
	$site->template_show_header();
?>
<div class="row show-grid">
    <div class="span7">
		<h1>Red de contactos</h1>
		<table class="table table-striped" id="friendlist">
			<thead>
				<tr>
					<th style="width: 50%">Nombre</th>
					<th style="width: 25%">Estado</th>
					<th style="width: 25%">Actividad</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$site->get_friendlist();
				?>
			</tbody>
		</table>
	</div>
	
	<div class="span4_mod">
		<h1>Buscar contáctos</h1>
		<p>Buscar a través de cuentas activas</p>
		<form id="user_search" class="form-search" action="">
			<input type="text" placeholder="Nombre de usuario" name="query" id="search_query" /> <button id="search_now" class="btn btn-success">Buscar ahora!</button>
			<input type="hidden" name="sf_mbr" value="true" />
			<hr />
		</form>
		<div id="search_result"></div>
	</div>
</div>
<?php
$site->template_show_footer();