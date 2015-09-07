<?php include("config/config.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title><?php echo Most_Listed_Page_Title; ?></title>
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <meta name="description" content="<?php echo Most_Listed_Page_Desc; ?>" />
        <meta name="keywords" content="<?php echo Most_Listed_Page_Keyword; ?>" />
    </head>
    <body>
        <?php include("header.php"); ?>
        <div id="solidblack"></div>
        <div id="body">
            <div id="mainbody">
                <div id="results">
                    <div id="results">
                        <center>
                            <img id="loaderIMG" src="images/twitter-logo.gif"/>
                        </center>
                        <br>
                        <table id="languagefilter">
                            <tbody>
                                <tr>
                                    <td style="font-size: 13px; font-weight: bold; color: #FFFFFF; background-color: #000000; padding:2px; text-align:center;">
                                        <?php echo Most_Listed_Account_Title;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo Privacy_Text; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table id="languagefilter">
                            <tbody>
                                <?php
                                    $currenturl = 'http://' .$_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
                                    $actualurl = explode('?',$currenturl);
                                ?>
                                <tr>
                                    <td colspan="2" style="font-size: 13px; font-weight: bold; color: #FFFFFF; background-color: #000000; padding:2px;">
                                        <?php echo Select_Lang;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%"><?php echo All_Lang; ?></td>
                                    <td>
                                        <a href="<?php echo $actualurl[0];?>">
                                            <?php echo No_Filter; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo Single_Lang; ?></td>
                                    <td>
                                        <a href="<?php echo $actualurl[0];?>?lang=en" style="font-weight:normal"><?php echo English; ?></a> |
                                        <a href="<?php echo $actualurl[0];?>?lang=es" style="font-weight:normal"><?php echo Spanish; ?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="width:100%;">
                            <div id="accountList"><br>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="background-color: #000000;">
                                                <div id="accountListHeading">
                                                    <?php echo Most_Listed_Account_Result;?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b><?php echo Rank; ?></b>
                                            </td>
                                            <td>
                                                <b><?php echo Account; ?></b>
                                            </td>
                                            <td>
                                                <b><?php echo Listed_Count; ?></b>
                                            </td>
                                        </tr>
                                        <?php
                                            $cnt = 1;
                                            $qry = "";
                                            $qry = " SELECT owloo_screen_name, owloo_listed_count FROM owloo_user_master";
                                            if($_GET["lang"] == "en" || $_GET["lang"] == "es")
                                            {
                                                $qry = $qry . " Where owloo_user_language = '" . $_GET["lang"] . "'";
                                            }
                                            $qry = $qry . " Order By owloo_listed_count DESC";
                                            $qry = $qry . " LIMIT 0, 100";
                                            $qrydata = mysql_query($qry);
                                            while ($fetchdata = mysql_fetch_array($qrydata))
                                            {
                                                $acnm = "";
                                                $acnm = '<a href="' . SITE_URL . 'userpage.php?twittername=';
                                                $acnm = $acnm . $fetchdata["owloo_screen_name"] . '">';
                                                $acnm = $acnm . '@' . $fetchdata["owloo_screen_name"];
                                                $acnm = $acnm . "</a>";
                                                echo '<tr>';
                                                echo '<td>' . $cnt . '</td>';
                                                echo '<td>' . $acnm . '</td>';
                                                echo '<td>' . $fetchdata["owloo_listed_count"] . '</td>';
                                                echo '</tr>';
                                                $cnt = $cnt + 1;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="solidblack"></div>
            <?php include("footer.php"); ?>
    </body>
</html>