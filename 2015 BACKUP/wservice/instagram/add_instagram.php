<?php

    include_once ('../config.php');
    
    if(!isset($_GET['instagram_username']) || !isset($_GET['instagram_category'])){
        header('Location: search.php');
        exit();
    }
    $category_id = $_GET['instagram_category'];
    
    if(!(is_numeric($category_id) && $category_id > 0)){
        header('Location: search.php?empty_category');
        exit();
    }
    
    function get_instagram_name_category($category_id){
        if(!empty($category_id)){
            $query = 'SELECT id_category, category FROM instagram_category WHERE id_category = $1;';
            $categories = db_query($query, array($category_id));
            if($fila = mysql_fetch_assoc($categories)){
                return $fila['category'];
            }
        }
        return NULL;
    }
    
    function get_instagram_profile_id($instagram_id){
        $query = 'SELECT id_profile FROM instagram_profiles WHERE instagram_id = $1;';
        $categories = db_query($query, array($instagram_id));
        if($fila = mysql_fetch_assoc($categories)){
            return $fila['id_profile'];
        }
        return false;
    }
    
    function instagram_clean_username($username){
        
        $username = urldecode($username);
        
        $search = array('https', 'http', '://', 'www.', 'instagram.com/');
        $replace = array('');
        
        $username = str_replace($search, $replace, $username);
        
        $chart_position = strpos( $username, '/');
        
        if($chart_position){
            $username = substr($username, 0, $chart_position);
        }
        
        return strtolower($username);
        
    }
    
    function instagram_search_username($username){
        //$username = instagram_clean_username($username);
        $data = get_url_content("https://api.instagram.com/v1/users/search?q=$username&client_id=".INSTAGRAM_CLIENT_ID);
        return json_decode($data, true);
    }
    
    function instagram_user_data($user_id){
        $data = get_url_content("https://api.instagram.com/v1/users/$user_id/?client_id=".INSTAGRAM_CLIENT_ID);
        return json_decode($data, true);
    }
    
    function instagram_user_posts($user_id){
        $data = get_url_content("https://api.instagram.com/v1/users/$user_id/media/recent/?client_id=".INSTAGRAM_CLIENT_ID."&count=".INSTAGRAM_COUNT_DATA);
        return json_decode($data, true);
    }
    
    $count_data = 100;
    
    $username = instagram_clean_username($_GET['instagram_username']);
    $found_user = NULL;
    $mensaje = '';
    $new_register = false;
    
    $data = instagram_search_username($username);
    
    if($data['meta']['code'] == 200){
        $ban = 0;
        foreach ($data['data'] as $user) {
            if($user['username'] == $username){
                
                $user_data = instagram_user_data($user['id']);
                
                if($user_data['meta']['code'] == 200){
                    $found_user = $user_data['data'];
                    $ban = 1;
                    
                    $profile_id = get_instagram_profile_id($found_user['id']);
                    
                    if(empty($profile_id)){
                        
                        if(empty($found_user['bio'])){
                            $found_user['bio'] = 'NULL';
                        }
                        if(empty($found_user['website'])){
                            $found_user['website'] = 'NULL';
                        }
                        
                        $query = 'INSERT INTO instagram_profiles VALUES(NULL, $1, \'$2\', '.($found_user['bio']!="NULL"?"'$3'":"$3").', '.($found_user['website']!="NULL"?"'$4'":"$4").', \'$5\', \'$6\', $7, 1, NOW(), NOW());';
                        $row = db_query($query, array($found_user['id'], $found_user['username'], $found_user['bio'], $found_user['website'], $found_user['profile_picture'], $found_user['full_name'], $category_id), 1);
                        $new_register = true;
                        $profile_id = get_instagram_profile_id($found_user['id']);
                        if(!empty($profile_id)){
                            $query = 'INSERT INTO instagram_record VALUES($1, $2, $3, $4, NOW());';
                            $row = db_query($query, array($profile_id, $found_user['counts']['media'], $found_user['counts']['followed_by'], $found_user['counts']['follows']), 1);
                        }
                    }
                    
                }
                else {
                    $mensaje = 'Ha ocurrido un error: '.$data['meta']['code'];
                }
                
                break;
                /*
                    [username] => voxpy
                    [bio] => 
                    [website] => 
                    [profile_picture] => http://images.ak.instagram.com/profiles/profile_348464328_75sq_1365541382.jpg
                    [full_name] => pain nagato
                    [id] => 348464328
                    
                    [username] => vox_py
                    [bio] => ¡Tan lejos como quieras llegar! ¡Ahí estamos! Seguinos en twitter.com/voxpy Hazte fan en facebook.com/voxpy
                    [website] => http://www.vox.com.py
                    [profile_picture] => http://images.ak.instagram.com/profiles/profile_645835338_75sq_1384284537.jpg
                    [full_name] => Vox Paraguay
                    [counts] => Array
                        (
                            [media] => 262
                            [followed_by] => 480
                            [follows] => 517
                        )
        
                    [id] => 645835338
                */
            }
        }
        if($ban == 0){
            $mensaje = 'Usuario no encontrado...';
        }
    }
    else {
        $mensaje = 'Ha ocurrido un error: '.$data['meta']['code'];
    }
    
    $query = 'SELECT id_category, category FROM instagram_category ORDER BY 1;'; 
    $categories = db_query($query, array());
    
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Owloo Instagram</title>
    <style>
        .instagram_username {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
        }
        .instagram_username input {
            border: 0 none;
            font-size: 15px;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        select {
            font-size: 15px;
        }
        input[type="submit"] {
            background-color: #0071b1;
            border: medium none;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
            margin-top: 20px;
            padding: 7px 20px;
        }
    </style>
<body>
    <form action="add_instagram.php" method="get">
        <div class="instagram_username">
            <input type="text" name="instagram_username" placeholder="Analiza una página de Instagram, ejemplo http://http://instagram.com/..." autocomplete="off" />
        </div>
        <div>
            <select name="instagram_category">
                <option value="0">Seleccione una categoría</option>
                <?php while($fila = mysql_fetch_assoc($categories)){ ?>
                <option value="<?=$fila['id_category']?>"><?=$fila['category']?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <input type="submit" value="Agregar" />
        </div>
    </form><br/><br/>
    <?php if(!empty($found_user)){ ?>
        <h1><?=($new_register?'Nuevo perfil agregado!':'El perfil ya existe...')?></h1>
        <div>
            <div><img src="<?=$found_user['profile_picture']?>" alt="" /></div>
            <div><b>Username:</b> <?=$found_user['username']?></div>
            <div><b>ID:</b> <?=$found_user['id']?></div>
            <div><b>Descripción:</b> <?=$found_user['bio']?></div>
            <div><b>Sitio web:</b> <?=$found_user['website']?></div>
            <div><b>Nombre completo:</b> <?=$found_user['full_name']?></div>
            <div><b>Categoría :</b> <?=get_instagram_name_category($found_user['id_category'])?></div>
            <div><b>Media:</b> <?=$found_user['counts']['media']?></div>
            <div><b>Seguido por :</b> <?=$found_user['counts']['followed_by']?></div>
            <div><b>Siguiendo a :</b> <?=$found_user['counts']['follows']?></div>
        </div>
    <?php }else{ ?>
        <?=$mensaje?>
    <?php } ?>
</body>
</html>