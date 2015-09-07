<?php

/**
 * PHP Curl status update script
 * @since Sep 2010
 * @version 29.5.2012
 * @link http://360percents.com/posts/php-curl-status-update-working-example/
 * @author Luka Pušić <pusic93@gmail.com>
 */
/*
 * Required parameters
 */
$status = 'Your status.';
$email = 'latamowl@gmail.com';
$pass = 'a$123456';
/*
 * Optional parameters
 */
$uagent = 'Mozilla/4.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)';
$cookies = 'cookies.txt';
touch($cookies);
$device_name = 'Home'; #in case you have location checking turned on
$debug = true;

/*
 * @return form input field names & values
 */

function parse_inputs($html) {
    $dom = new DOMDocument;
    @$dom->loadxml($html);
    $inputs = $dom->getElementsByTagName('input');
    return($inputs);
}

/*
 * @return form action url
 */

function parse_action($html) {
    $dom = new DOMDocument;
    @$dom->loadxml($html);
    $form_action = $dom->getElementsByTagName('form')->item(0)->getAttribute('action');
    if (!strpos($form_action, "//")) {
        $form_action = "https://m.facebook.com$form_action";
    }
    return($form_action);
}

function login() {
    /*
     * Grab login page and parameters
     */
    $loginpage = grab_home();
    $form_action = parse_action($loginpage);
    $inputs = parse_inputs($loginpage);
    $post_params = "";
    foreach ($inputs as $input) {
        switch ($input->getAttribute('name')) {
            case 'email':
                $post_params .= 'email=' . urlencode($GLOBALS['email']) . '&';
                break;
            case 'pass':
                $post_params .= 'pass=' . urlencode($GLOBALS['pass']) . '&';
                break;
            default:
                $post_params .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
        }
    }
    echo "[i] Using these login parameters: $post_params";
    /*
     * Login using previously collected form parameters
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIEJAR, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['uagent']);
    curl_setopt($ch, CURLOPT_URL, $form_action);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
    $loggedin = curl_exec($ch);
    if ($GLOBALS['debug']) {
        echo $loggedin;
    }
    curl_close($ch);
    /*
     * Check if location checking is turned on or you have to verify location
     */
    if (strpos($loggedin, "machine_name") || strpos($loggedin, "/checkpoint/") || strpos($loggedin, "submit[Continue]")) {
        echo "\n[i] Found a checkpoint...\n";
        checkpoint($loggedin);
        echo "\n[i] Checkpoints passed...\n";
    }
}

/*
 * pass checkpoints
 */

function checkpoint($html) {
    $form_action = parse_action($html);
    $inputs = parse_inputs($html);
    $post_params = "";
    foreach ($inputs as $input) {
        switch ($input->getAttribute('name')) {
            case "":
                break;
            case "submit[I don't recognize]":
                break;
            case "submit[Don't Save]":
                break;
            case "machine_name":
                $post_params .= 'machine_name=' . urlencode($GLOBALS['device_name']) . '&';
                break;
            default:
                $post_params .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
        }
    }
    if ($GLOBALS['debug']) {
        echo "\nCheckpoint form action: $form_action\n";
        echo "\nCheckpoint post params: $post_params\n";
    }
    //Verify the machine
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIEJAR, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['uagent']);
    curl_setopt($ch, CURLOPT_URL, $form_action);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
    $home = curl_exec($ch);
    if ($GLOBALS['debug']) {
        echo "This is fucking shit: -- $home --";
    }
    curl_close($ch);

    if (strpos($home, "machine_name") || strpos($home, "/checkpoint/") || strpos($home, "submit[Continue]")) {
        echo "\n[i] Solving another checkpoint...\n";
        checkpoint($home);
    }
}

/*
 * grab and return the homepage
 */

function grab_home() {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIEJAR, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['uagent']);
    curl_setopt($ch, CURLOPT_URL, 'https://m.facebook.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $html = curl_exec($ch);
    if ($GLOBALS['debug']) {
        echo $html;
    }
    curl_close($ch);
    return($html);
}

/*
 * update facebook status
 */

function update($status) {
    $html = grab_home();

    $form_action = parse_action($html);
    $inputs = parse_inputs($html);
    $post_params = "status=$status&";
    foreach ($inputs as $input) {
        $post_params .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
    }


    if ($GLOBALS['debug']) {
        echo "\nStatus update form action: $form_action\n";
        echo "\nStatus update params: $post_params\n";
    }
    /*
     * post the update
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIEJAR, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['uagent']);
    curl_setopt($ch, CURLOPT_URL, $form_action);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
    $updated = curl_exec($ch);
    if ($GLOBALS['debug']) {
        echo $updated;
    }
    curl_close($ch);
}

function logout() {
    $dom = new DOMDocument;
    @$dom->loadxml(grab_home());
    $links = $dom->getElementsByTagName('a');
    foreach ($links as $link) {
        if (strpos($link->getAttribute('href'), 'logout.php')) {
            $logout = $link->getAttribute('href');
            break;
        }
    }

    $url = 'https://m.facebook.com' . $logout;
    /*
     * just logout lol
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIEJAR, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $GLOBALS['cookies']);
    curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['uagent']);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $loggedout = curl_exec($ch);
    if ($GLOBALS['debug']) {
        echo "\nLogout url = $url\n";
        echo $loggedout;
    }
    curl_close($ch);
    echo "\n[i] Logged out.\n";
}

login();
update($GLOBALS['status']);
logout();
unlink($cookies);
?>
