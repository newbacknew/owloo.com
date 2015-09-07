<?php
    require_once('../config.php');
    
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
    </form>
</body>
</html>