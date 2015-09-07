<?php
include("config/config.php");
include("twitter/twitterfunctions.php");
//Fetch Data From Twitter
$screenname = "";
if ($_POST["txttwittername"])
{
    $screenname = $_POST["txttwittername"];
    $url = "https://api.twitter.com/1.1/users/lookup.json";
    $parameters = "screen_name=" . $screenname;
    $retdata = getdata($url, $parameters);
} else if ($_GET["twittername"]) {
    $screenname = $_GET["twittername"];
    $url = "https://api.twitter.com/1.1/users/lookup.json";
    $parameters = "screen_name=" . $screenname;
    $retdata = getdata($url, $parameters);
} else {
    echo "<script>window.location='" . SITE_URL . "404.php'</script>";
}
if ($retdata) {
    $twdatas = json_decode($retdata, true);
    foreach ($twdatas as $twdata) {
        if ($twdata[0]["message"]) {
            echo "<br/>Error : " . $twdata[0]["message"];
            echo "<br/>Code : " . $twdata[0]["code"];
            echo "<script>window.location='" . SITE_URL . "404.php'</script>";
        } else {
            $hfflag = 0;
            $twitter_id = $twdata["id"];
            $twitter_name = $twdata["name"];
            $screen_name = $twdata["screen_name"];
            $profile_image_url = $twdata["profile_image_url"];
            $description = $twdata["description"];
            $location = $twdata["location"];
            $timezone = $twdata["time_zone"];
            $creationdate = $twdata["created_at"];
            $lang = $twdata["lang"];
            $verified = $twdata["verified"];
            $followers_count = $twdata["followers_count"];
            $following_count = $twdata["friends_count"];
            $tweetcount = $twdata["statuses_count"];
            $listedcount = $twdata["listed_count"];
            // Save Data Into DB
            $qry = "";
            $qry = " Select owloo_user_id, owloo_followers_count, owloo_following_count,owloo_tweetcount, owloo_listed_count";
            $qry = $qry . " from owloo_user_master";
            $qry = $qry . " Where owloo_user_twitter_id = '" . $twitter_id . "'";
            $qry = $qry . " AND owloo_screen_name = '" . $screen_name . "'";
            $chk_oldpass = mysql_query($qry);
            $fetch_cntr = mysql_fetch_array($chk_oldpass);
            if ($fetch_cntr['owloo_user_id'] == "") {
                $qry = "";
                $qry = " INSERT INTO owloo_user_master ( owloo_user_twitter_id, owloo_user_name,";
                $qry = $qry . " owloo_screen_name, owloo_user_photo, owloo_user_description,";
                $qry = $qry . " owloo_user_location, owloo_user_language, owloo_user_verified_account,";
                $qry = $qry . " owloo_user_timezone, owloo_user_created_on, owloo_followers_count,";
                $qry = $qry . " owloo_following_count, owloo_tweetcount, owloo_listed_count,";
                $qry = $qry . " owloo_user_status, owloo_created_on, owloo_updated_on) VALUES (";
                $qry = $qry . " '" . $twitter_id . "',";
                $qry = $qry . " '" . $twitter_name . "',";
                $qry = $qry . " '" . $screen_name . "',";
                $qry = $qry . " '" . $profile_image_url . "',";
                $qry = $qry . " '" . $description . "',";
                $qry = $qry . " '" . $location . "',";
                $qry = $qry . " '" . $lang . "',";
                $qry = $qry . " '" . $verified . "',";
                $qry = $qry . " '" . $timezone . "',";
                $qry = $qry . " '" . $creationdate . "',";
                $qry = $qry . " '" . $followers_count . "',";
                $qry = $qry . " '" . $following_count . "',";
                $qry = $qry . " '" . $tweetcount . "',";
                $qry = $qry . " '" . $listedcount . "',";
                $qry = $qry . " '1',";
                $qry = $qry . " '" . Date("Y-m-d") . "',";
                $qry = $qry . " '" . Date("Y-m-d") . "')";
                $result = mysql_query($qry);
                $update_id = mysql_insert_id();
                $hfflag = 1;
            } else {
                $update_id = $fetch_cntr['owloo_user_id'];
                $qry = "";
                $qry = "Update owloo_user_master Set ";
                $qry = $qry . " owloo_user_twitter_id = '" . $twitter_id . "',";
                $qry = $qry . " owloo_user_name = '" . $twitter_name . "',";
                $qry = $qry . " owloo_screen_name = '" . $screen_name . "',";
                $qry = $qry . " owloo_user_photo = '" . $profile_image_url . "',";
                $qry = $qry . " owloo_user_description = '" . $description . "',";
                $qry = $qry . " owloo_user_location = '" . $location . "',";
                $qry = $qry . " owloo_user_language = '" . $lang . "',";
                $qry = $qry . " owloo_user_verified_account = '" . $verified . "',";
                $qry = $qry . " owloo_user_timezone = '" . $timezone . "',";
                $qry = $qry . " owloo_user_created_on = '" . $creationdate . "',";
                $qry = $qry . " owloo_followers_count = '" . $followers_count . "',";
                $qry = $qry . " owloo_following_count = '" . $following_count . "',";
                $qry = $qry . " owloo_tweetcount = '" . $tweetcount . "',";
                $qry = $qry . " owloo_listed_count = '" . $listedcount . "',";
                $qry = $qry . " owloo_updated_on = '" . Date("Y-m-d") . "'";
                $qry = $qry . " Where owloo_user_twitter_id = '" . $twitter_id . "'";
                $qry = $qry . " AND owloo_screen_name = '" . $screen_name . "'";
                mysql_query($qry);
                $qry = "";
                $qry = "Delete from owloo_daily_track where owloo_updated_on = '" . Date("Y-m-d") . "'";
                mysql_query($qry);
                $qry = "";
                $qry = " INSERT INTO owloo_daily_track ( owloo_user_twitter_id, owloo_followers_count,";
                $qry = $qry . " owloo_following_count, owloo_tweetcount, owloo_listed_count,";
                $qry = $qry . " owloo_updated_on) VALUES (";
                $qry = $qry . " '" . $update_id . "',";
                $qry = $qry . " '" . ($followers_count - $fetch_cntr['owloo_followers_count']) . "',";
                $qry = $qry . " '" . ($following_count - $fetch_cntr['owloo_following_count']) . "',";
                $qry = $qry . " '" . ($tweetcount - $fetch_cntr['owloo_tweetcount']) . "',";
                $qry = $qry . " '" . ($listedcount - $fetch_cntr['owloo_listed_count']) . "',";
                $qry = $qry . " '" . Date("Y-m-d") . "')";
                mysql_query($qry);
            }
        }
    }
} else {
    echo "<script>window.location='" . SITE_URL . "404.php'</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title><?php echo User_Page_Title; ?></title>
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <meta name="description" content="<?php echo User_Page_Desc; ?>" />
        <meta name="keywords" content="<?php echo User_Page_Keyword; ?>" />
        <script type="text/javascript" src="<?php echo SITE_URL; ?>js/jquery.js"></script>
        <script>
            function voteuser(uid,points)
            {
                var voteflg = $('#votcnt').val();
                if(voteflg == 0)
                {
                    var urlrat = "savevotes.php";
                    var datastr = "uid=" + uid + "&pts=" + points;
                    $.ajax({
                        type: "GET",
                        url: urlrat,
                        data: datastr,
                        cache: false,
                        success: function(html)
                        {
                            if(html == 1)
                            {
                                var totalpt = parseInt($("#score").html()) + parseInt(points);
                                if(totalpt >= 0)
                                {
                                    totalpt = "+" + totalpt;
                                }
                                var votescnt = parseInt($("#votes").html()) + 1;
                                $('#score').html(totalpt);
                                $('#votes').html(votescnt);
                                $('#votcnt').val("1");
                                $('#votemsg').html("<?php echo Rate_Success; ?>");
                            }
                            else
                            {
                            }
                        }
                    });
                }
                else
                {
                    $('#votemsg').html("<?php echo Alread_Rate; ?>");
                }
            }
        </script>
    </head>
    <body>
        <?php include("header.php"); ?>
        <div id="solidblack"></div>
        <div id="body">
            <div id="mainbody">
                <div id="results">
                    <center>
                        <img id="loaderIMG" src="<?php echo SITE_URL; ?>images/twitter-logo.gif"/>
                    </center>
                    <div id="body">
                        <div id="chartDiv">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="padding-right:10px;">
                                            <a href="https://twitter.com/<?php echo $screen_name; ?>" target="_blank">
                                                <img src="<?php echo $profile_image_url; ?>" style="border: 2px solid #000000; box-shadow: 5px 5px 5px #696969; border-radius: 5px;"/>
                                            </a>
                                        </td>
                                        <td>
                                            <b>
                                                <a href="https://twitter.com/<?php echo $screen_name; ?>" target="_blank">
                                                    <?php echo $screen_name; ?> (@<?php echo $screen_name; ?>)
                                                </a>
                                            </b><br>
                                            <?php echo $description; ?>.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <?php echo Location; ?> <?php echo $location; ?><br/>
                            <?php echo Time_Zone; ?> <?php echo $timezone; ?><br/>
                            <?php echo Creation_Date; ?> <?php echo $creationdate; ?><br/>
                            <?php echo Listed; ?> <?php echo $listedcount; ?><br/>
                            <?php echo Tweets; ?> <?php echo $tweetcount; ?><br/>
                            <?php echo Followers; ?> <?php echo $followers_count; ?><br/>
                            <?php echo Following; ?> <?php echo $following_count; ?><br/>
                            <?php
                            if ($verified == 1) {
                                $verimg = '<img src="images/verified.png" alt="Y"/>';
                            } else {
                                $verimg = '<img src="images/not-verified.png" alt="N"/>';
                            }
                            ?>    
                            <?php echo Verified_Account; ?> <?php echo $verimg; ?><br/>
                            <?php
                            if ($lang == "en") {
                                $language = English;
                            }
                            if ($lang == "es") {
                                $language = Spanish;
                            }
                            ?>
                            <?php echo Language; ?> <?php echo $language; ?><br/>
                            <?php
                            $qry = "";
                            $qry = " SELECT count(*) as totalcnt, sum(owloo_rating_points) as totalpoints";
                            $qry = $qry . " FROM owloo_user_rating";
                            $qry = $qry . " Where owloo_user_id = '" . $update_id . "'";
                            $chk_qry = mysql_query($qry);
                            $fetch_cntr = mysql_fetch_array($chk_qry);
                            $totalvote = $fetch_cntr['totalcnt'];
                            $totalpoints = $fetch_cntr['totalpoints'];
                            if ($totalpoints == "") {
                                $totalpoints = 0;
                            }
                            if ($totalpoints >= 0) {
                                $totalpoints = "+" . $totalpoints;
                            }
                            ?>
                            <b><?php echo Owloo_User_Rating; ?></b><br>
                            <span id="score" style="font: normal normal 110% Verdana, sans-serif; font-weight:bold;"><?php echo $totalpoints; ?> </span>
                            <span id="upArrowSpan" onclick="voteuser('<?php echo $update_id; ?>','10');">
                                <img id="upArrow" src="images/green-arrow-15.png" style="vertical-align:bottom;"/>
                            </span>
                            <span id="downArrowSpan" onclick="voteuser('<?php echo $update_id; ?>', '-10');">
                                <img id="downArrow" src="images/red-arrow-15.png" style="vertical-align:bottom;">
                            </span>
                            (<span id="votes"><?php echo $totalvote; ?></span> <?php echo Votes; ?>)
                            <input type="hidden" id="votcnt" value="0"/>
                            <input type="hidden" id="hfuidid" value="<?php echo $update_id; ?>"/>
                            <input type="hidden" id="hfflag" value="<?php echo $hfflag; ?>"/>
                            <input type="hidden" id="hftweetcnt" value="<?php echo $tweetcount; ?>"/>
                            <input type="hidden" id="hfscreen" value="<?php echo $screen_name; ?>"/>
                            <script>
                                $(document).ready(function()
                                {
                                    $.ajax({
                                        type: "GET",
                                        url: "savelog.php",
                                        data: "uid=" + $("#hfuidid").val(),
                                        cache: false,
                                        success: function(html)
                                        {
                                        }
                                    });
                                    // Check for New/Update Flag.
                                    // Get Screenname.
                                    qrystr = "";
                                    qrystr = qrystr + "uid=" + $("#hfuidid").val();
                                    qrystr = qrystr + "&screen=" + $("#hfscreen").val();
                                    qrystr = qrystr + "&twcnt=" + $("#hftweetcnt").val();
                                    qrystr = qrystr + "&flg=" + $("#hfflag").val();
                                    //alert("savehashmention.php?" + qrystr);
                                    $.ajax({
                                        type: "GET",
                                        url: "savehashmention.php",
                                        data: qrystr,
                                        cache: false,
                                        success: function(html)
                                        {
                                        }
                                    });
                                });
                            </script>
                            <div id="votemsg">
                            </div>
                        </div>
                        <?php if($hfflag == 1) { ?>
                            <div id="chartDiv">
                                <center>
                                    <br/><br/>
                                    <?php echo Processing_Message;?><br/><br/>
                                    <img id="loaderIMG" src="<?php echo SITE_URL; ?>images/twitter-logo.gif"/>
                                    <br/><br/>
                                </center>
                            </div>
                        <?php  }
                        else
                        { ?>
                        <script src="js/highcharts.js"></script>
                        <div id="chartDiv">
                            <?php include("chart/tweetperhour.php"); ?>
                        </div>
                        <div id="chartDiv">
                            <?php include("chart/tweetperday.php"); ?>
                        </div>
                        <div id="chartDiv">
                            <?php include("chart/curseword.php"); ?>
                        </div>
                        <div id="chartDiv">
                            <?php include("chart/mostmentions.php"); ?>
                        </div>
                        <div id="chartDiv">
                            <?php include("chart/mostusedhashtag.php"); ?>
                        </div>
                        <div id="chartDiv">
                            <?php include("chart/followergrowth.php"); ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="solidblack"></div>
        <?php include("footer.php"); ?>
    </body>
</html>