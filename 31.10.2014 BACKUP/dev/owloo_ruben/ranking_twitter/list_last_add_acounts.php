<?php
require_once('../owloo_config.php');
    
$datos = array(
     array
        (
            'owloo_screen_name' => 'UnderArmourES',
            'owloo_user_name' => 'Under Armour Spain',
            'owloo_user_description' => 'Twitter Oficial de Under Armour España. Tenemos un único propósito: hacer mejorar al atleta',
            'owloo_user_photo' => 'http://pbs.twimg.com/profile_images/3046706404/3887213ea49870ba54b1bedc6108311c_normal.png',
            'owloo_created_on' => '2014-09-09'
        )

    , array
        (
            'owloo_screen_name' => 'teresitaaa_',
            'owloo_user_name' => 'Techi',
            'owloo_user_description' => 'Por culpa de twitter creé esta segunda cuenta. Tito Torres es mi pastor, nada me faltará.',
            'owloo_user_photo' => 'http://pbs.twimg.com/profile_images/501918074929700864/bHq9T9oZ_normal.jpeg',
            'owloo_created_on' => '2014-09-09'
        )

    , array
        (
            'owloo_screen_name' => 'Haceb',
            'owloo_user_name' => 'Haceb',
            'owloo_user_description' => 'Participa en el concurso de diseño que premia tu ingenio, creatividad y visión. Haceb Proyecta Diversión, Premio 2014 http://t.co/QgTL83AoK5',
            'owloo_user_photo' => 'http://pbs.twimg.com/profile_images/504750099025494018/CMivtvJH_normal.jpeg',
            'owloo_created_on' => '2014-09-09'
        )

    , array
        (
            'owloo_screen_name' => 'CubaMINREX',
            'owloo_user_name' => 'Cuba MINREX',
            'owloo_user_description' => '• Ministerio de Relaciones Exteriores de la República de Cuba • • Ministry of Foreign Affairs of the Republic of Cuba •',
            'owloo_user_photo' => 'http://pbs.twimg.com/profile_images/1128670326/cubaminrex_normal.png',
            'owloo_created_on' => '2014-09-09'
        )

);


	foreach ($datos as $fetchdata){ ?>
        <div class="owloo_wrap_tw_last_add">
            <div>
                <a href="<?=URL_ROOT?>twitter-stats/userpage/<?=$fetchdata["owloo_screen_name"]?>"><img alt="<?=$fetchdata["owloo_user_name"]?>" class="owloo_tw_last_add_img" src="<?=convert_imagen_to_https($fetchdata["owloo_user_photo"])?>"></a>
                <div>
                    <span class="owloo_tw_last_add_follow_pre">+</span><a href="https://twitter.com/intent/user?screen_name=<?=$fetchdata["owloo_screen_name"]?>" rel="nofollow" target="_blank" class="owloo_tw_last_add_follow">Seguir</a>
                </div>
            </div>
            <div>
                <div><a href="<?=URL_ROOT?>twitter-stats/userpage/<?=$fetchdata["owloo_screen_name"]?>"><?=$fetchdata["owloo_user_name"]?></a></div>
                <div><?=$fetchdata["owloo_user_description"]?></div>
            </div>
        </div>
<?php } ?>