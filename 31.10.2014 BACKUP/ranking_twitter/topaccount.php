<?php include("config/config.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title><?php echo Home_Page_Title; ?></title>
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <meta name="description" content="<?php echo Home_Page_Desc; ?>" />
        <meta name="keywords" content="<?php echo Home_Page_Keyword; ?>" />
    </head>
    <body>
      <?php include("header.php"); ?>
        <div id="solidblack"></div>
        <div id="body">
            <div id="mainbody">
                <div style="display: none;" id="loading"><br>
                    <img id="loaderIMG" src="<?php echo SITE_URL; ?>images/twitter-loader-128.gif">
                    <br>
                </div>
                <div id="results">
                    <center>
                        <img id="loaderIMG" src="<?php echo SITE_URL; ?>images/twitter-logo.gif">
                    </center>
                    <table id="topacctsnote">
                        <tbody>
                            <tr>
                                <td style="font-size: 13px; font-weight: bold; color: #FFFFFF; background-color: #000000; padding:2px; text-align:center;">
                                    <?php echo Top_Account_Label;?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo Privacy_Text;?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <ul>
                        <li>
                            <a href="<?php echo SITE_URL; ?>mostfollowedaccounts.php">
                               <?php echo Most_Followed_Accounts; ?></a>: <?php echo Most_Followed_Accounts_Text; ?>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>mostmentionedaccounts.php">
                                <?php echo Most_Mentioned_Accounts; ?></a>: <?php echo Most_Mentioned_Accounts_Text; ?>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>mostlistedaccounts.php">
                                <?php echo Most_Listed_Accounts; ?></a>: <?php echo Most_Listed_Accounts_Text; ?>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>mostsearchedaccounts.php">
                                <?php echo Most_Searched_Accounts; ?></a>: <?php echo Most_Searched_Accounts_Text; ?>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>highestratedaccounts.php">
                                <?php echo Highest_Rated_Accounts; ?></a>: <?php echo Highest_Rated_Accounts_Text; ?>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>lowestratedaccounts.php">
                                <?php echo Lowest_Rated_Accounts; ?></a>: <?php echo Lowest_Rated_Accounts_Text; ?>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>filthiestaccounts.php">
                                <?php echo Filthiest_Accounts; ?></a>: <?php echo Filthiest_Accounts_Text; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="solidblack"></div>
        <?php include("footer.php"); ?>
    </body>
</html>