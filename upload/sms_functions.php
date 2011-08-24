<?php
require_once 'getstatus_functions.php';

function insertUser($data = array()){
    global $config, $db;

    if(!empty($data)){
        $fieldList = array();
        $valueList = array();

        foreach($data as $field => $value){
            $fieldList[] = $field;
            $valueList[] = "'".$value."'";
        }

        $fields = implode(', ', $fieldList);
        $values = implode(', ', $valueList);

        $query = "INSERT INTO users ($fields) VALUES ($values)";

        if(mysql_query($query)){
            return mysql_insert_id($db);
        }else{
            // comment out line below to get the error message
            //die(mysql_error($db));
            return false;
        }
    }else{
        return false;
    }
}

/**
 * Function to check the gateway based on config
 */
function checkGateway(){
    global $config;

    $smsGateways = $config['SmsGateways'];

    if(!empty($smsGateways['Clickatell']['api_id'])){
        writeLog('SMS: Selecting Clickatell as gateway');
        return _clickatell_inbound();
    }

    if(!empty($smsGateways['Aspiro']['username'])){
        writeLog('SMS: Selecting Aspiro as gateway');
        return _aspiro_inbound();
    }
}

/**
 * Function to call outbound gateway for replying status
 */
function checkReply($data){
    global $config;

    if($config['SmsGateways']['replyStatus']){
        $smsGateway = $config['SmsGateways'];

        if(!empty($smsGateway['Clickatell']['api_id'])){
            return _clickatell_outbound($data);
        }

        if(!empty($smsGateway['Aspiro']['username'])){
            return _aspiro_outbound($data);
        }

        return false;
    }else{
        return false;
    }
}

/**
 * SMS Gateway for Clickatell
 * http://www.clickatell.com
 */
function _clickatell_inbound(){
    global $config;

    $gateway = $config['SmsGateways']['Clickatell'];
    $inbound = $_POST ? $_POST : ($_GET ? $_GET : null);

    if(!empty($inbound)){
        if(!empty($inbound['api_id']) && $inbound['api_id'] == $gateway['api_id']){

            $user = mysql_fetch_array(mysql_query("SELECT id FROM users WHERE mobile = ".$inbound['from']), MYSQL_ASSOC);
            if(empty($user['id'])){
                die('Invalid sender mobile number');
            }

            $auction = mysql_fetch_array(mysql_query("SELECT id FROM auctions WHERE id = ".trim($inbound['text'])), MYSQL_ASSOC);
            if(empty($auction['id'])){
                die('Invalid auction id');
            }

            $data['user_id'] 			= $user['id'];
            $data['auction_id'] 		= $auction['id'];
            $data['time_increment'] 	= get('time_increment', $data['auction_id'], 0);
            $data['bid_debit'] 			= get('bid_debit', $data['auction_id'], 0);
            $data['price_increment'] 	= get('price_increment', $data['auction_id'], 0);

            // For replying
            $data['reply']['targetNumber'] = $inbound['from'];
        }else{
            return false;
        }
    }else{
        return false;
    }

    return $data;
}

function _clickatell_outbound($data){
    global $config;

    $gateway = $config['SmsGateways']['Clickatell'];

    $path  = '/http/sendmsg?';
    $path .= 'user='.$gateway['user'].'&';
    $path .= 'password='.$gateway['password'].'&';
    $path .= 'api_id='.$gateway['api_id'].'&';
    $path .= 'to='.$data['reply']['targetNumber'].'&';
    $path .= 'text='.$data['Auction']['message'];

    $fp = fsockopen("api.clickatell.com", 80, $errno, $errstr, 30);

    fputs($fp, "GET ".$path." HTTP/1.0\r\n");
    fputs($fp, "Host: api.clickatell.com\r\n");
    fputs($fp, "User-agent: Mozilla/4.0 (compatible: MSIE 7.0; Windows NT 6.0)\r\n");
    fputs($fp, "Connection: close\r\n\r\n");

    fputs($fp, $xml);

    while (!feof($fp)) {
        $buf .= fgets($fp,128);
    }

    fclose($fp);
    writeLog('SMS Reply: Returned buffer -> '.$buf);

    return $buf;
}

/**
 * SMS Gateway for aspiro
 * <url here>
 */
function _aspiro_inbound(){
    global $config;
    require_once 'xml_functions.php';

    $gateway = $config['SmsGateways']['Aspiro'];
    $inbound = !empty($_POST['xml']) ? $_POST['xml'] : null;

    /*$inbound = '<?xml version=\"1.0\" encoding=\"UTF-8\" ?><!DOCTYPE gate SYSTEM \"http://www.mobile-entry.com/resources/gate.dtd\"><gate><id>45518699</id><country>NO</country><accessNumber>2333</accessNumber><senderNumber>234234234</senderNumber><targetNumber>2333</targetNumber><operator>TN</operator><referenceId><![CDATA[703051701]]></referenceId><price>0</price><keyconfig><keyword>GM</keyword></keyconfig><sms><content><![CDATA[Gm b2]]></content></sms></gate>';
    /* --- */

    $inbound = str_replace('\\"', '"', $inbound);

    writeLog('SMS: Got data from gateway');
    writeLog('SMS: Data from gateway -> '.$inbound);

    if(!empty($inbound)){
        $inbound = xml2array($inbound);
        if(!empty($inbound['gate']['_c'])){
            writeLog('SMS: Got sms from '.$inbound['gate']['_c']['senderNumber']['_v']);

            $senderNumber = $inbound['gate']['_c']['senderNumber']['_v'];
            $senderNumber_nocc = substr($inbound['gate']['_c']['senderNumber']['_v'], strlen($gateway['phone_country_code']));

            $user = mysql_fetch_array(mysql_query("SELECT id FROM users WHERE mobile LIKE '%".$senderNumber."%' OR mobile LIKE '%".$senderNumber_nocc."%' LIMIT 1"), MYSQL_ASSOC);

            if(empty($user['id'])){
                writeLog('SMS: New sender mobile number, inserting to database as new user.');
                $user['id'] = insertUser(array('username' => $senderNumber, 'mobile' => $senderNumber));
                if(empty($user['id'])){
                    writeLog('SMS: Cannot inserting new mobile number into database');
                    die('Cannot inserting new mobile number into database');
                }
            }else{
                writeLog('SMS: Sender mobile number found');
            }

            $text = trim(strtoupper($inbound['gate']['_c']['sms']['_c']['content']['_v']));
            $search = array('![CDATA[', ']]', "\n", "\r", "GM", "B", " ");
            $text = str_replace($search, '', $text);

            writeLog('SMS: Got text -> '.$text);

            // Collect data for reply
            $data['reply']['country']      = $inbound['gate']['_c']['country']['_v'];
            $data['reply']['targetNumber'] = $inbound['gate']['_c']['senderNumber']['_v'];
            $data['reply']['senderNumber'] = $inbound['gate']['_c']['targetNumber']['_v'];
            $data['reply']['accessNumber'] = $inbound['gate']['_c']['accessNumber']['_v'];
            $data['reply']['operator']     = $inbound['gate']['_c']['operator']['_v'];

            // Remove the cdata stuff from text
            $referenceId = trim($inbound['gate']['_c']['referenceId']['_v']);
            $searchRefId = array('![CDATA[', ']]', "\n", "\r", " ");
            $referenceId = str_replace($searchRefId, '', $referenceId);

            // Put the ref on reply data
            $data['reply']['referenceId']  = $referenceId;

            $auction = mysql_fetch_array(mysql_query("SELECT id FROM auctions WHERE id = '".$text."'"), MYSQL_ASSOC);
            if(empty($auction['id'])){

                // Reply with specific invalid text
                $data['reply']['text'] = $gateway['reply']['invalidAuctionText'];

                // Reply
                _aspiro_outbound($data);

                writeLog('SMS: Invalid auction id');
                die('Invalid auction id');
            }else{
                writeLog('SMS: Valid auction id');
            }

            $data['user_id'] 			= $user['id'];
            $data['auction_id'] 		= $auction['id'];
            $data['time_increment'] 	= get('time_increment', $data['auction_id'], 0);
            $data['bid_debit'] 			= get('bid_debit', $data['auction_id'], 0);
            $data['price_increment'] 	= get('price_increment', $data['auction_id'], 0);

        }else{
            writeLog('SMS: Empty data in gateway XML');
            return false;
        }
    }else{
        writeLog('SMS: Got no data from gateway');
        return false;
    }

    return $data;
}

function _aspiro_outbound($data){
    global $config;
    $gateway = $config['SmsGateways']['Aspiro'];

    writeLog('SMS Reply: Replying');

    //http://www.mobile-entry.com/gate/service
    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
            <gate>
                <country>'.$data['reply']['country'].'</country>
                <accessNumber>'.$data['reply']['accessNumber'].'</accessNumber>
                <senderNumber>'.$data['reply']['senderNumber'].'</senderNumber>
                <targetNumber>'.$data['reply']['targetNumber'].'</targetNumber>
                <operator>'.$data['reply']['operator'].'</operator>
                <referenceId><![CDATA['.$data['reply']['referenceId'].']]></referenceId>
                <price>'.$gateway['reply']['price'].'</price>
                <sms>
                    <content><![CDATA['.$gateway['reply']['text'].']]></content>
                </sms>
            </gate>';

    writeLog('SMS Reply: XML to be send -> '.$xml);

    $fp = fsockopen("www.mobile-entry.com", 80, $errno, $errstr, 30);

    fputs($fp, "POST /gate/service HTTP/1.0\r\n");
    fputs($fp, "Host: www.mobile-entry.com\r\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
    fputs($fp, "Content-length: " . strlen($xml) . "\r\n");
    fputs($fp, "User-agent: Mozilla/4.0 (compatible: MSIE 7.0; Windows NT 6.0)\r\n");
    fputs($fp, "Authorization: Basic ".base64_encode($gateway['username'].":".$gateway['password'])."\r\n");
    fputs($fp, "Connection: close\r\n\r\n");

    fputs($fp, $xml);

    while (!feof($fp)) {
        $buf .= fgets($fp,128);
    }

    fclose($fp);
    writeLog('SMS Reply: Returned buffer -> '.$buf);

    return $buf;
}
?>