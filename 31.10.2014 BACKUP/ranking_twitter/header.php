<div id="header">
    <div id="mainheader">
        <ul id="headerLine_top">
            <li>
                <span id="line1leftpart1">
                    <a href="<?php echo SITE_URL; ?>"><?php echo Site_Title;?></a>
                </span>
            </li>
            <li id="line1leftpart2">
                <a href="#">
                    <img src="<?php echo SITE_URL; ?>images/twitter.png" alt="<?php echo Twitter; ?>"/>
                </a>
            </li>
            <li>
                <a href="$">
                    <img src="<?php echo SITE_URL; ?>images/facebook.png" alt="<?php echo Facebook; ?>"/>
                </a>
            </li>
            <li>
                <a href="$">
                    <img src="<?php echo SITE_URL; ?>images/linkedin.png" alt="<?php echo LinkedIn; ?>"/>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo SITE_URL; ?>images/email.png" alt="<?php echo Email; ?>"/>
                </a>
            </li>
        </ul>
        <ul id="headerLine">
            <li>
                <a href="<?php echo SITE_URL; ?>topaccount.php" class="topAccounts"><?php echo Top_Accounts; ?></a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>topchart.php" class="topAccounts"><?php echo Top_Twitter_Charts;?></a>
            </li>
        </ul>
        <ul id="headerLine_search">
            <form action="<?php echo SITE_URL; ?>userpage.php" method="post">
            <li class="acc_name">
                <span id="userInputSpan">@<input id="txttwittername" name="txttwittername" size="20" placeholder="<?php echo Account_Name; ?>" type="textbox"/>
                </span>
            </li>
            <li>
                <input id="btnstats" value="<?php echo Get_User_Stats;?>" type="submit"/>
            </li>
            </form>
        </ul>
    </div>
</div>