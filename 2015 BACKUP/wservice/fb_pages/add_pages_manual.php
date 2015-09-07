<?php

die();

set_time_limit(0);

define('DB_USER', 'owloo_admin');
define('DB_PASS', 'iiRTwMxs=%am');
define('DB_NAME', 'owloo_owloo_3_1');
define('DB_NAME_TW', 'owloo_twitter_3_1');

//Conexión a la base de datos
$conn = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME, $conn) or die('www.owloo.com');
mysql_query('SET NAMES \'utf8\'');

function get_url_content($url){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
}

function add_https_to_url($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "https://" . $url;
    }
    return $url;
}

function get_fb_page_id_from_url($url){
    
    if(strpos($url, 'facebook.com/') === false){}else{
        $url = add_https_to_url($url);
    }
    
    //$datos = get_url_content('https://graph.facebook.com/'.$url);
    
    $datos = @file_get_contents('http://23.88.103.193/~david/owl/get_fb_data.php?url='.urlencode('https://graph.facebook.com/'.$url));
    $datos = json_decode ($datos, true);
    
    if(isset($datos['id'])){
        return $datos['id'];
    }
    
    return NULL;
}

function is_exist_fb_page($fb_id){
    $sql = 'SELECT * FROM facebook_page WHERE fb_id = '.mysql_real_escape_string($fb_id).';';
    $res = mysql_query($sql) or die(mysql_error());
    if($fila = mysql_fetch_assoc($res)){
        return true;
    }
    return false;
}

function add_fb_page($id, $username, $name, $about, $description, $link, $picture, $cover, $location, $is_verified, $likes, $talking_about, $category){
    
    $id = mysql_real_escape_string($id);
    $username = mysql_real_escape_string($username);
    $name = mysql_real_escape_string($name);
    $about = (!empty($about)?"'".mysql_real_escape_string($about)."'":'NULL');
    $description = (!empty($description)?"'".mysql_real_escape_string($description)."'":'NULL');
    $link = (!empty($link)?"'".mysql_real_escape_string($link)."'":'NULL');
    $picture = (!empty($picture)?"'".mysql_real_escape_string($picture)."'":'NULL');
    $cover = (!empty($cover)?"'".mysql_real_escape_string($cover)."'":'NULL');
    $id_country = 0;
    $location = (!empty($id_country)?mysql_real_escape_string($id_country):'NULL');
    $is_verified = ($is_verified?1:0);
    $likes = (is_numeric($likes)?mysql_real_escape_string($likes):'NULL');
    $talking_about = (is_numeric($talking_about)?mysql_real_escape_string($talking_about):'NULL');
    
    $hispanic = 0;
    if(!empty($location))
        $hispanic = 0;

    $sql = "INSERT INTO facebook_page VALUES(NULL, $id, '$username', '$name', $about, $description, $link, $picture, $cover, $location, $is_verified, $likes, $talking_about, NULL, $hispanic, 1, NULL, NOW(), NOW());";
    $res = mysql_query($sql) or die(mysql_error());
    if(mysql_affected_rows() > 0){
        /*$id_page = mysql_insert_id();
        $id_sub_category = add_fb_page_sub_category($category);
        if(!empty($id_sub_category)){
            add_fb_pages_sub_categories($id_page, $id_sub_category);
            return $id_page;
        }*/
        return true;
    }
    return NULL;
}
    
$pages = explode(',', 'clarovideo,127504107267515,155110471251542,gaesecuador,promakeupblognet,ntvguatemala,upydarroyo,upydvalladolid,carlsjrguat,dionisioherreraduque,257774027764087,institutostendhal,203630783099625,instlecuisinier,conlanaoficial,edgarjaratv,obradorvirtual,redaccionmedica,juanes,nickyjampr,donomar,malumamusik,gabrielgarciamarquezauthor,reykon,officialsofiavergara,capofans,pipebuenofans,parcerospirry,neutrogenaca,mrbean,lynxeffect,costacoffee,glostickscouk,freepeople,starbucksuk,nikefootballuk,nextonline,asda,ebaycouk,tesco,aoletsgo,britishairways,maltesers,samsungmobileuk,autocad,benjerryuk,bentleymotors,wispa,xboxuk,riverisland,galaxy,boohoocom,cadburycremeegg,newlookfashion,topshop,amazonuk,walls,kikepionphotographer,adidasoriginalsuk,argos,everything5pounds,aussiehair,cex,nutellauk,marmite,jdsportsofficial,gadventures,accessorizeuk,nandosunitedkingdom,o2uk,sainsburys,secretescapes,justeat,doveuki,dorothyperkins,stylefruitscouk,lucozadeenergy,maxfactoruk,topman,youvebeentangoed,ribenauk,hmunitedkingdom,womenfreebiescouk,audiuk,jackwillsfans,impulsefragrances,greggsthebakers,haribouk,bootsofficialuk,benefitcosmeticsuk,ee,knittingnanas,mikado,lidluk,ikeauk,vodafoneuk,johnlewisretail,homebargains,jaffacakes,statravel,extraofficialuk,aldiuk,drpepperuk,soapandglory,chainreactioncycles,barclaysuk,carphonewarehouse,cadburyuk,hotukdeals,burtonmenswear,bmwuk,glamourmaguk,dailymail,britishvogue,zoomagazine,skynews,lovemagazine,theindependentonline,mtvuk,bbcearth,houseandgardenuk,egypttodaycouk,goaluk,bbcsport,bbcnews,xvideos,societatcc,pippermintbar,transparentamazon,amazoninstantvideous,amazoninstantvideouk,pressking,natuclinikguate,wwwntvcomgt,ciaramolinapsicologaemocional,miguelangelrevilla,blogfarmacia,asociaciondry,locosporlasbecas,babycenterenespanol,greenpeacespain,457766960932231,grupomascoteros,fundacionvicenteferrer,auladeespecializacionfotografica,emagister,unidoscontraelcancer,unescoes,noakeiko,141698185862840,162913417108035,accioncontraelhambre,reciclavidrio,efespana,amigostierra,fundacioncarreras,wwfespana,masterunir,totemguard,bebesnuk,oxfamintermon,caixaforum,llevamosmagia,practicopedia,iebusinessschool,saespain,consumidoresocu,baasgalgo,huddlecom,nubelo,arcticrising,nextinnovacion,redepyme,ayuntamientodemadrid,dondelotiro,cursogram,revistahola,as,bravoporti,revistaeljueves,tueuropafm,marieclairees,revistaelmueble,cadena100,sportes,menshealthes,barcablog,serpadreses,20minutoses,telvacom,diezminutoses,discoverychannelespana,solomotoes,flaixfm,rockfm,lavozdegalicia,factoriadeficcion,elconfidencial,levantateycardenas,gaycommunity,fansgoltelevision,cadenaser,divinityes,tuponteaprueba,informativostelecinco,yahooactualidad,europapresses,lainformacion,polonia,lasexta,fotogramases,elperiodicocatalunya,quees,telemundo33,canalplusespana,ondacero,elmundotoday,newsweekespanol,sonido1021,gqespana,muchomaxmaximafm,elpaissemanal,mondosonoro,yahoocelebes,memedeportes,grafficainfo,vilaweb,ipadizateblog,heraldodearagon,elpartidodelas12,lasietetv,eleconomistaes,marianrivera,malarodriguezoficial,laquintaestacion,officialdiegolopez,jordialbaofficial,mariavalverdeoficial,bustamantemusic,alkilados,bcncat,hibues,hotelespanya,hotelconencantoalmendralejo,posadasespana,destiniacom,barcelohotels,logitravel,publicitis,wamhello,invertiaweb,increnta,smartdigital,signoscym,mcomarketingonline,los3guisantes,grupoabbsolute,egeide,sidnposicionamientoseo,pokoad,salsacreativa01,agenciapublicidadtaos,makkaocomunicacion,barcelonactiva,gruetzi,advertispublicidad,ogilvyes,220404824699526,buzzmn,thebrandnation,proximitybarcelona,showerfb,internetrepublica,socialmood,llorenteycuenca,tuatusocialmediapr,silviaalbertinco,barcelonalovesentrepreneurs,bizbarcelona,mobileworldcongress,innovem,grupcinc,greenweekendorg,internetsociety,crearteintegral,ananamag,leejeanschile,valentinacentroamerica,academicaoficial,bistro8187,zillow,591219794264130,franciscojaviergarcia7965692,419834578146315,radioguantanamo,593727474050058,vanheusen,frenchconnection,khaledalsawy,143102205766360,videonews24it,106626879360459,yosoytingales00,charapaquesabe,101359003258132,biointegra,sap,sabah,deepikapadukone,guaufilms,copaamerica,casatoledomoda,tgmodaoficial,telepopmusik,pmoindia,mittromney,nasa,joycemeyerministries,prabowosubianto,isupportnarendramodi,nelsonmandela,bjp4india,receptayyiperdogan,ted,nasaearthobservatory,theanimalrescuesite,walkfreeorg,aapkaarvind,57357arabicofficialpage,descubremenet,planetate,escritordjs,loscrimenesdelajedrez,portaldearte,coolteaguatemala,inesadbolivia,hanascloset,lacabreraguatemala,elgounafceg,itthaddotcom,almasryofficial,enppiclub,wwwismailycluborg,smouhasportingclub,wadideglafc,seattlesounders,lagalaxy,portlandtimbers,footcra,dhjfootball,fathunionsportofficiel,husafootball,kackenitraac,kawkabi,matfoot,almlbaltwnsy,csssfax,184018498320341,dvinsagramunt,scgmuangthongunited,chonburifootballclub,buriramutd,theredwarriorsofficial,21164211233,1fsvmainz05es,esteghlalfc,mcdowellmohunbagan,punefc,neftcipfk,alhilalsudan,gormahiafc,144021392295640,sriwijayafc,aremafcofficial,persebayasatoe,semenpadangfc,realpersija,persijajakartaofficial,mitrakukar,fcbasel1893,mhfootballclub,maccabitlvfc,rhino,thevelvetunderground,thedubaimall,glistockisti,zaloravietnam,homeshop18,ikeaegypt,myjabong,g2acom,lootcrate,altoavellaneda,submarino,zalando,ccmegaplaza,altorosario,citystarsofficial,cairofestivalcitymall,retailmenot,jbkkick2000,luluhypermarket,ninelineapparel,malldelsol,thevitaminshoppe,lidlromania,bliblidotcom,tventas,jkshopvn,altage,puntacarretas,scheels,awokcom,tjmaxx,multiplazaelsalvador,tpido,metrocentroelsalvador,gittigidiyor,kaymumaroc,xshopvnstore,multiplazacostarica,littlefashionasia,jockeyplaza,portonesshoppingsitiooficial,wwwyamevn,drmartens,grouponpolska,rakutenichiba,spirithalloween,kaymumyanmar,malloftheemirates,nestonmain,aviamoda,goodsph,hotsalege,galeriasmonterrey,gaubongcaocapcom,julep,modanisa,lamaquinista,mendozaplazashopping,arabianoud,laredoutept,dillards,shoppingqueenvox,grouponhk,ruelala,hypercarrefourtunisie,sanmarinogye,publicgr,nefsak,kaymupk,zaloramalaysia,cbssunday,samsungpolska,bigwaustralia,alshoppingonline,darazpk,bigcbigservice,familymart,soleiloutlet,wwfpl,lgpolska,newsweekpolska,hbopolska,perfumeriedouglaspolska,avonpolska,fundacjadkmspolska,mbankpolska,olxpolska,markronson,alhilalsaudifc,alittihad1,fkzeljeznicar,fudbalskiklubsarajevo,acspartapraha,gomvfc,napapijri,djmaddogmusic,tpmazembe1,154469811299860,atalantabc,fcparma,udinesecalcio1896,delfinopescara1936,padovacalcio,vicenzacalcio1902,bresciacalciosocial,avivamientoemmanuelvenezuela,relacsis,oncosalud,jamesarthur,yesfm,hpespana,cursosccc,tulipan,7canarias,vodafonees,robinschulzofficial,spainnaturhouse,selfbankesp,skodaes,skoda,skodacanarias,latamgodaddy,campuspartymexico,godaddy,kindleespana,kerusanchez,prisa,felixbaumgartner,lenovoes,mediamarktes,glamourespana,bourjoisespana,lossimses,nafnafespana,tassimoes,volkswagenesp,ingdirectespana,deichmannes,ticketmasteres,spartanracespain,westwinges,kikomakeupmilanoespana,audispain,mmsespana,bbvaespana,nuevaespana,philipsspain,orlandospain,gymcompanyespana,siemprelista,mothercarees,weledaespana,disneychanneles,phonehousees,skipespana,mediasetesp,mytastees,chiccocompanyespana,norautoesp,mazdaes,citroenes,heroespana,justeates,letsbonusespana,icexes,fridaysesp,dolcegustoes,arielespanaes,sportzonees,europcares,remingtones,comedycentrales,ccespcordoba,esteticamodacabello,activtradeses,calvoespanaoficial,jaguarspain,theavenermusic,amenacom,orangeesp,ono,euskaltel,nominalia,lineadirectaaseguradora,fundacioncocacola,cocacolazeroespana,1393245584230208,cepsaespana,diadelabanderita,mcdonaldsfrance,tovelo,shawnmendesofficial,svsandhausen1916ev,scmagdeburg,fussballclubingolstadt,meinscp,1fclokleipzig,ssvulm1846fussball,tennisborussiaberlin,svdarmstadt1898ev,spvggunterhaching,borussianeunkirchen,rwoberhausen,ersterfcs,wattenscheid09,rotweissessen1907,dfruttaarreglosfrutales,radioculturaisc,uerdingen05,kickersoffenbach,svwaldhofmannheim,alemanniaaachen,fcenergie,msvduisburg,kleeblattfuerth,dscarminiabielefeld,rbleipzig,1fcunionberlin,meinvfl,achtzehn99,karlsruhersc,vflwolfsburgfussball,fchansa,fcviktoria1889berlin,bicityorg,berlinerfcdynamo,354084788112485,293241084209861,sveichede,svlippstadt08,fkpirmasens,svn1929,lueneburgersk,spvggoberfrankenbayreuth,tsv1860rosenheim,ssvreutlingen,149084531769305,166106277449,fchennef05,sfsiegen,fortunakoeln,budissabautzen,155157207856206,dulcemariajujuy,vflosnabrueck,svwwde,stuttgarterkickers,ssvjahn,scpreussen,holsteinkiel,hallescherfc,fcrotweisserfurt,diehimmelblauen,vfraalen1921,fch1846,fcerzgebirgeaue,premiosysorteostv,fsvfrankfurt,229753537041589,ofangelina,premierleague,erosnow,mobogeniemexico,gatorade,schweppes,burnenergy,104019549633137,vfbluebeck,nulldrei,161968500518092,fccarlzeissjena1903,berlinerathletikklub07,zfcmeuselwitz,fsvoptik,fsvzwickau,170953719623364,sflotte,wormatia,eintrachttrier,bvcloppenburg,ssvg02,tuskoblenz,fc08homburg,ksvhessen,vereinshomepage,365672646811360,sva01de,bayernhofinteam,fceintracht2010,wuerzburgerkickers,farmaoptics,tortasdelgordocomco,landsteinerscientificoficial,pisafarmaceutica,pfizermexico,genommalabmedicamentos,budweiserperu,comisionestatalatencionyprotecciondelosperiodistas,semanticwebbuilder,oralbca,colgateca,carlosaugustomoralesl,aleidaalavez') ;


echo count($pages);

$__cont = 1;
foreach($pages as $__page){
    if(!empty($__page)){
        
        $fb_page_id = get_fb_page_id_from_url($__page);
        
        if(!empty($fb_page_id)){
            
            //$datos = get_url_content('https://graph.facebook.com/'.$fb_page_id.'?fields=id,username,name,about,description,link,picture,cover,location,is_verified,likes,talking_about_count,were_here_count,category&locale=es_LA');
            
            $datos = @file_get_contents('http://23.88.103.193/~david/owl/get_fb_data.php?url='.urlencode('https://graph.facebook.com/'.$fb_page_id.'?fields=id,username,name,about,description,link,picture,cover,location,is_verified,likes,talking_about_count,category&locale=es_LA'));
            $datos = json_decode ($datos, true);
            
            
            if(isset($datos['id']) && isset($datos['name'])){
                /*
                echo '<br/><br/>';
                echo 'id: '.$datos['id'].'<br/>';
                echo 'username: '.$datos['username'].'<br/>';
                echo 'name: '.$datos['name'].'<br/>';
                echo 'about: '.$datos['about'].'<br/>';
                echo 'description: '.$datos['description'].'<br/>';
                echo 'link: '.$datos['link'].'<br/>';
                echo 'picture: '.$datos['picture']['data']['url'].'<br/>';
                echo 'cover: '.$datos['cover']['source'].'<br/>';
                echo 'location: '.$datos['location']['country'].'<br/>';
                echo 'is_verified: '.$datos['is_verified'].'<br/>';
                echo 'likes: '.$datos['likes'].'<br/>';
                echo 'talking_about_count: '.$datos['talking_about_count'].'<br/>';
                echo 'category: '.$datos['category'].'<br/>';
                */
                
                $datos['username'] = (!empty($datos['username'])?$datos['username']:$datos['id']);
                
                $_fb_username = $datos['username'];
                
                if(!is_exist_fb_page($datos['id'])){
                
                    $id_page = add_fb_page($datos['id'], $datos['username'], $datos['name'], $datos['about'], $datos['description'], $datos['link'], $datos['picture']['data']['url'], $datos['cover']['source'], $datos['location']['country'], $datos['is_verified'], $datos['likes'], $datos['talking_about_count'], $datos['category']);
                    
                    /*if(!empty($id_page)){
                        
                        echo $__cont++.'<br/>';
                        
                        /***** Add likes and talking_about *****
                        if(!is_exist_today_fb_page_likes_talking_about($id_page)){
                            add_fb_page_likes_talking_about($id_page, $datos['likes'], $datos['talking_about_count']);
                        }
                        /***** END - Add likes and talking_about *****
                        
                        $access_token = get_fb_page_access_token();
                        $until = date('U');
                        $since = $until - (90 * 24 * 60 * 60);
                        $local_fans_country = file_get_contents('https://graph.facebook.com/'.$datos['id'].'/insights/page_fans_country?since='.$since.'&until='.$until.'&locale=es_LA&access_token='.$access_token);
                        $local_fans_country = json_decode ($local_fans_country, true);
                        
                        //print_r($local_fans_country); die();
                        
                        if(isset($local_fans_country['data']) && $local_fans_country['data'][0]['name'] == 'page_fans_country'){
                            $sql_insert = '';
                            $aux_first = true;
                            foreach ($local_fans_country['data'][0]['values'] as $local_fan) {
                                $date = explode('T', $local_fan['end_time']);
                                foreach ($local_fan['value'] as $key => $value) {
                                    $_country_id = get_country_id_from_code($key);
                                    if(!empty($_country_id)){
                                        if(!$aux_first) $sql_insert .= ','; else $aux_first = false;
                                        $sql_insert .= "(NULL, ".mysql_real_escape_string($id_page).",".mysql_real_escape_string($_country_id).",".mysql_real_escape_string($value).",'".mysql_real_escape_string($date[0])."')";
                                    }
                                }
                            }
                            
                            if(!empty($sql_insert))
                                add_lote_fb_page_local_fan($sql_insert);
                        }
                        
                        update_fb_page_likes_talking_about_local_fans($id_page, $datos['likes'], $datos['talking_about_count'], get_first_country_local_fans($id_page));
                        
                    }
                    else {
                        $mensaje_new_fan_page = '<div>Lo sentimos, <strong>no hemos podido procesar su petición</strong>.</div>
                                                 <div>Favor, intentelo más tarde...</div>';
                    }*/
                }
            }
            else {
                $mensaje_new_fan_page = "<div>Puede que <strong>".$__page."</strong> no esté registrado en Facebook.</div>
                               <div>Favor verifique la página ingresada y vuelve a intentarlo!</div>";
            }
        }
        else {
            $mensaje_new_fan_page = "<div>Puede que <strong>".$__page."</strong> no esté registrado en Facebook.</div>
                               <div>Favor verifique la página ingresada y vuelve a intentarlo!</div>";
        }
        
        echo '<br>'.$mensaje_new_fan_page.'<br>';
        
    }
}