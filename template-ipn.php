<?php
/**
 * Template Name: PayPal IPN Listener
 * Description: IPN Listener.
 *
 */

get_header();
 
if (isset($_GET['ipn_listener']) && $_GET['ipn_listener'] == 'paypal') {
	error_log(date('[Y-m-d H:i e] '). "Received IPN notification!". PHP_EOL, 3, LOG_FILE);
    verifyIPN();    
}

function verifyIPN()
{
    $testmode = false;
    $paypal_ipn_url = $testmode ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 'https://ipnpb.paypal.com/cgi-bin/webscr';

    if ( ! count($_POST)) {
    	error_log(date('[Y-m-d H:i e] '). "Missing POST data!". PHP_EOL, 3, LOG_FILE);
    	return false;
    }
        
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
        $keyval = explode('=', $keyval);
        if (count($keyval) == 2) {
            // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
            if ($keyval[0] === 'payment_date') {
                if (substr_count($keyval[1], '+') === 1) {
                    $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                }
            }
            $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
    }

    // Build the body of the verification post request, adding the _notify-validate command.
    $req = 'cmd=_notify-validate';
    $get_magic_quotes_exists = false;
    if (function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
        if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }

    // Post the data back to PayPal, using curl. Throw exceptions if errors occur.
    $ch = curl_init($paypal_ipn_url);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: PHP-IPN-Verification-Script',
        'Connection: Close',
    ));
    $res = curl_exec($ch);
    if ( ! ($res)) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);
    	error_log(date('[Y-m-d H:i e] '). "cURL error: [$errno] $errstr". PHP_EOL, 3, LOG_FILE);
    	return false;
    }

    $info = curl_getinfo($ch);
    $http_code = $info['http_code'];
    if ($http_code != 200) {
    	error_log(date('[Y-m-d H:i e] '). "PayPal responded with http code $http_code". PHP_EOL, 3, LOG_FILE);
    	return false;
    }

    curl_close($ch);

    // Check if PayPal verifies the IPN data, and if so, return true.
    if (strcmp ($res, "VERIFIED") == 0) {
    	if( strcmp ($_POST['payment_status'], "Completed") == 0) {
        	error_log(date('[Y-m-d H:i e] '). "Payment completed. ".$res." ".$_POST['item_name']." ".$_POST['item_number']." ".$_POST['reference_id']." ".$_POST['payment_status']. PHP_EOL, 3, LOG_FILE);
    	    //Reserve item
    	    reserveItem($_POST['item_number']);
    	    reserveItem($_POST['reference_id']);
    	}
        return true;
    } else {
    	error_log(date('[Y-m-d H:i e] '). "Verification failed ".$_POST['reference_id']."RES:".$res. PHP_EOL, 3, LOG_FILE);
        return false;
    }
}

function reserveItem($post_id) {
    global $wpdb;
    if ( $post_id != '' ) {
    	error_log(date('[Y-m-d H:i e] '). "Post ID for reservation: $post_id". PHP_EOL, 3, LOG_FILE);
        $status = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id LIKE $post_id AND meta_key LIKE 'status'");
        if ( $status == 'Available' ) {
        	error_log(date('[Y-m-d H:i e] '). "Item $post_id is available and ready for reservation". PHP_EOL, 3, LOG_FILE);
            $wpdb->update( 'wp_postmeta',
            	array( 'meta_value' => 'Reserved' ),
            	array( 'post_id' => $post_id, 'meta_key' => 'status' )
            );
        	error_log(date('[Y-m-d H:i e] '). "Item $post_id reserved". PHP_EOL, 3, LOG_FILE);
            return true;
        }
    }
    return false;
}

?>

<?php get_footer(); ?>
