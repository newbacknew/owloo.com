<?php include("config/config.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title><?php echo Curse_Page_Title; ?></title>
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <meta name="description" content="<?php echo Curse_Page_Desc; ?>" />
        <meta name="keywords" content="<?php echo Curse_Page_Keyword; ?>" />
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
                                        <?php echo Curse_Account_Title; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php
                                        if ($_POST["txtcurseword"])
                                        {
                                            $qry = "";
                                            $qry = " Select owloo_curse_text from owloo_curse_words";
                                            $qry = $qry . " Where owloo_curse_text = '" . $_POST["txtcurseword"] . "'";
                                            $chk = mysql_query($qry);
                                            $fetch_cntr = mysql_fetch_array($chk);
                                            if ($fetch_cntr['owloo_curse_text'] == "")
                                            {
                                                $qry = "";
                                                $qry = "INSERT INTO owloo_curse_words (owloo_curse_text) VALUES (";
                                                $qry = $qry . " '" . $_POST["txtcurseword"] . "')";
                                                mysql_query($qry);
                                            }
                                            echo Save_Success;
                                        }
                                        ?>
                                        <form method="post">
                                            <input id="txtcurseword" name="txtcurseword" size="20" placeholder="<?php echo Curse_Word; ?>" type="textbox"/>
                                            &nbsp;&nbsp;<input id="btnstats" value="<?php echo Save_Curse_Word; ?>" type="submit" style="width:200px;"/>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br/>
                    </div>
                </div>
            </div>
            <div id="solidblack"></div>
            <?php include("footer.php"); ?>
    </body>
</html>