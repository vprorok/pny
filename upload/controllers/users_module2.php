<?php

class licensing
{

    public function validate_license( $api_fingerprint, $server, $RPC, $license )
    {
        $method = false;
        $returned = licensing::parse_xml( licensing::validate_local_key( ) );
        if ( $returned['status'] == "grab_new_key" || $returned['status'] == "expired" )
        {
            $returned = licensing::parse_xml( licensing::go_remote( $method, $server, $license ) );
            if ( empty( $returned ) )
            {
                $returned['status'] = "invalid";
            }
            if ( $returned['status'] == "active" || $returned['status'] == "reissued" )
            {
                licensing::go_remote_api( $RPC, $api_fingerprint, $license );
                $returned = licensing::parse_xml( licensing::validate_local_key( true ) );
            }
        }
        if ( $returned['status'] != "active" && $returned['status'] != "reissued" )
        {
            if ( empty( $returned ) )
            {
                $returned['status'] = "invalid";
            }
            $errors = false;
            if ( $returned['status'] == "suspended" )
            {
                $errors = __( "This license has been suspended.", true );
            }
            else if ( $returned['status'] == "pending" )
            {
                $errors = __( "This license is pending admin approval.", true );
            }
            else if ( $returned['status'] == "expired" )
            {
                $errors = __( "This license is expired.", true );
            }
            else if ( $returned['status'] == "renew" )
            {
                $errors = $returned['message'];
            }
            else if ( $returned['status'] == "active" && strcmp( md5( "56d9ce577e79bd83d8f98e2fec53e04a".$token ), $returned['access_token'] ) != 0 )
            {
                $errors = __( "This license has an invalid checksum.", true );
            }
            else
            {
                $errors = __( "License invalid. Please review the Knowledge Base for information on how to resolve this problem.", true );
            }
        }
        return $errors ? $errors : $returned;
    }

    public function store_local_key( $local_key )
    {
        Cache::write( "license_key_".Configure::read( "App.serverName" ), $local_key, "week" );
    }

    public function get_stored_local_key( )
    {
        return Cache::read( "license_key_".Configure::read( "App.serverName" ), "week" );
    }

    public function write_best_method( $method )
    {
        return "phpaudit_exec_socket";
    }

    public function get_best_method( )
    {
        return "phpaudit_exec_socket";
    }

    public function info( $path_to_key )
    {
        $raw_array = licensing::parse_local_key( $path_to_key );
        if ( !is_array( $raw_array ) || $raw_array === false )
        {
            return array(
                "status" => false,
                "expires" => "Unknown",
                "message" => "No local key found for this installation. Please login to the client area to get a new key."
            );
        }
        $local_key_expires = $raw_array[1] == "never" ? "Never" : date( "r", $raw_array[1] );
        if ( $raw_array[9] && strcmp( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$raw_array[9] ), $raw_array[10] ) != 0 )
        {
            return array(
                "status" => false,
                "expires" => $local_key_expires,
                "message" => "Custom variables checksum failed for this local key."
            );
        }
        if ( strcmp( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$raw_array[1] ), $raw_array[2] ) != 0 )
        {
            return array(
                "status" => false,
                "expires" => $local_key_expires,
                "message" => "License expiry checksum failed for this local key."
            );
        }
        if ( $raw_array[1] < time( ) && $raw_array[1] != "never" )
        {
            return array(
                "status" => false,
                "expires" => $local_key_expires,
                "message" => "Your local license key has expired."
            );
        }
        $directory_array = @explode( ",", $raw_array[3] );
        $valid_dir = licensing::path_translated( );
        $valid_dir = @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$valid_dir );
        if ( !in_array( $valid_dir, $directory_array ) )
        {
            return array(
                "status" => false,
                "expires" => $local_key_expires,
                "message" => "Local license key failed to validate the installation path."
            );
        }
        $host_array = @explode( ",", $raw_array[4] );
        if ( !in_array( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$_SERVER['HTTP_HOST'] ), $host_array ) )
        {
            return array(
                "status" => false,
                "expires" => $local_key_expires,
                "message" => "Local license key failed to validate the host address."
            );
        }
        $ip_array = @explode( ",", $raw_array[5] );
        return array(
            "status" => false,
            "expires" => $local_key_expires,
            "message" => "Local license key failed to validate the IP address."
        );
        return array(
            "status" => true,
            "expires" => $local_key_expires,
            "message" => "The local license key is valid."
        );
    }

    public function validate_local_key( $debug = false )
    {
        $raw_array = licensing::parse_local_key( );
        if ( !is_array( $raw_array ) || $raw_array === false )
        {
            return "<verify status='grab_new_key' message='The local license key was empty.' />";
        }
        if ( $raw_array[9] && strcmp( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$raw_array[9] ), $raw_array[10] ) != 0 )
        {
            return "<verify status='invalid' message='The custom variables were tampered with.' />";
        }
        if ( strcmp( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$raw_array[1] ), $raw_array[2] ) != 0 )
        {
            return "<verify status='invalid' message='The local license key checksum failed.' ".$raw_array[9]." />";
        }
        if ( $raw_array[1] < time( ) && $raw_array[1] != "never" )
        {
            return "<verify status='expired' message='Fetching a new local key.' ".$raw_array[9]." />";
        }
        if ( $raw_array[13] && strcmp( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$raw_array[13] ), $raw_array[14] ) != 0 )
        {
            return "<verify status='invalid' message='The download controls were tampered with.' />";
        }
        if ( $raw_array[15] && strcmp( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$raw_array[15] ), $raw_array[16] ) != 0 )
        {
            return "<verify status='invalid' message='The SPBAS version has been tampered with.' />";
        }
        $directory_array = @explode( ",", $raw_array[3] );
        $valid_dir = licensing::path_translated( );
        $valid_dir = @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$valid_dir );
        if ( !in_array( $valid_dir, $directory_array ) )
        {
            return "<verify status='invalid' message='The file path did not match what was expected.' ".$raw_array[9]." />";
        }
        $host_array = @explode( ",", $raw_array[4] );
        $ip_array = @explode( ",", $raw_array[5] );
        $server_addr = licensing::server_addr( );
        $ip_check = @in_array( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$server_addr ), $ip_array );
        if ( !$ip_check )
        {
            $server_addr = substr( $server_addr, 0, strrpos( $server_addr, "." ) );
            $ip_check = @in_array( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$server_addr ), $ip_array );
        }
        if ( !$ip_check )
        {
            $server_addr = substr( $server_addr, 0, strrpos( $server_addr, "." ) );
            $ip_check = @in_array( @md5( "56d9ce577e79bd83d8f98e2fec53e04a".$server_addr ), $ip_array );
        }
        if ( !$ip_check )
        {
            return "<verify status='invalid_key' message='The IP address did not match what was expected.' ".$raw_array[9]." />";
        }
        return "<verify status='active' message='The license key is valid.' ".$raw_array[9]." />";
    }

    public function parse_xml( $data )
    {
        $parser = @xml_parser_create( "" );
        @xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
        @xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
        @xml_parse_into_struct( $parser, $data, $values, $tags );
        @xml_parser_free( $parser );
        if ( isset( $values[0]['attributes'] ) )
        {
            return $values[0]['attributes'];
        }
        return false;
    }

    public function get_key( )
    {
        $data = licensing::get_stored_local_key( );
        if ( !$data )
        {
            return false;
        }
        $buffer = @str_replace( "<", "", $data );
        $buffer = @str_replace( ">", "", $buffer );
        $buffer = @str_replace( "?PHP", "", $buffer );
        $buffer = @str_replace( "?", "", $buffer );
        $buffer = @str_replace( "/*--", "", $buffer );
        $buffer = @str_replace( "--*/", "", $buffer );
        return str_replace( "\n", "", $buffer );
    }

    public function parse_local_key( )
    {
        $raw_data = @base64_decode( @licensing::get_key( ) );
        $raw_array = @explode( "|", $raw_data );
        if ( @is_array( $raw_array ) && count( $raw_array ) < 8 )
        {
            return false;
        }
        return $raw_array;
    }

    public function make_token( )
    {
        return md5( "56d9ce577e79bd83d8f98e2fec53e04a".time( ) );
    }

    public function phpaudit_exec_curl( $array )
    {
        if ( !function_exists( "curl_init" ) )
        {
            return false;
        }
        $array = @explode( "?", $array );
        $link = curl_init( );
        curl_setopt( $link, CURLOPT_URL, $array[0] );
        curl_setopt( $link, CURLOPT_POSTFIELDS, $array[1] );
        curl_setopt( $link, CURLOPT_VERBOSE, 0 );
        curl_setopt( $link, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $link, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $link, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $link, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $link, CURLOPT_MAXREDIRS, 6 );
        curl_setopt( $link, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $link, CURLOPT_TIMEOUT, 15 );
        $results = curl_exec( $link );
        if ( 0 < curl_errno( $link ) )
        {
            curl_close( $link );
            return false;
        }
        curl_close( $link );
        if ( strpos( $results, "verify" ) === false )
        {
        }
        return $results;
    }

    public function phpaudit_exec_socket( $http_host, $http_dir, $http_file, $querystring )
    {
        $fp = @fsockopen( $http_host, 80, $errno, $errstr, 10 );
        if ( !$fp )
        {
            return false;
        }
        $header = "POST {$http_dir}{$http_file} HTTP/1.0\r\n";
        $header .= "Host: {$http_host}\r\n";
        $header .= "Content-type: application/x-www-form-urlencoded\r\n";
        $header .= "User-Agent: SolidPHP Business Automation Software (SPBAS) (http://www.spbas.com)\r\n";
        $header .= "Content-length: ".@strlen( $querystring )."\r\n";
        $header .= "Connection: close\r\n\r\n";
        $header .= $querystring;
        $data = false;
        if ( @function_exists( "stream_set_timeout" ) )
        {
            stream_set_timeout( $fp, 20 );
        }
        @fputs( $fp, $header );
        if ( @function_exists( "socket_get_status" ) )
        {
            $status = @socket_get_status( $fp );
        }
        else
        {
            $status = true;
        }
        while ( !feof( $fp ) && $status )
        {
            $data .= @fgets( $fp, 1024 );
            if ( @function_exists( "socket_get_status" ) )
            {
                $status = @socket_get_status( $fp );
            }
            else if ( feof( $fp ) == true )
            {
                $status = false;
            }
            else
            {
                $status = true;
            }
        }
        @fclose( $fp );
        if ( !strpos( $data, "200" ) )
        {
            return false;
        }
        if ( !$data )
        {
            return false;
        }
        $data = @explode( "\r\n\r\n", $data, 2 );
        if ( !$data[1] )
        {
            return false;
        }
        if ( strpos( $data[1], "verify" ) === false )
        {
            return false;
        }
        return $data[1];
    }

    public function path_translated( )
    {
        $option = array( "PATH_TRANSLATED", "ORIG_PATH_TRANSLATED", "SCRIPT_FILENAME", "DOCUMENT_ROOT", "APPL_PHYSICAL_PATH" );
        foreach ( $option as $key )
        {
            if ( !isset( $_SERVER[$key] ) || strlen( trim( $_SERVER[$key] ) ) <= 0 )
            {
                continue;
            }
            if ( substr( @php_uname( ), 0, 7 ) == "Windows" && strpos( $Var_552[$Var_576], "\\" ) )
            {
                return substr( $_SERVER[$key], 0, @strrpos( $_SERVER[$key], "\\" ) );
            }
            return substr( $_SERVER[$key], 0, @strrpos( $_SERVER[$key], "/" ) );
        }
        return "Directory path could be determined.";
    }

    public function server_addr( )
    {
        $options = array( "SERVER_ADDR", "LOCAL_ADDR" );
        foreach ( $options as $key )
        {
            if ( !isset( $_SERVER[$key] ) )
            {
                continue;
            }
            return $_SERVER[$key];
        }
        return "IP could be determined.";
    }

    public function go_remote( $method, $server, $license )
    {
        $methods = $GLOBALS['methods'];
        if ( $method )
        {
            unset( $methods[$method] );
            $methods[] = $method;
            $methods = array_reverse( $methods );
        }
        $enable_dns_spoof = "yes";
        $query_string = "license={$license}";
        $query_string .= "&access_directory=".licensing::path_translated( );
        $query_string .= "&access_ip=".licensing::server_addr( );
        $query_string .= "&access_host={$_SERVER['HTTP_HOST']}";
        $query_string .= "&access_token=";
        $query_string .= $token = licensing::make_token( );
        $data = false;
        foreach ( array( "phpaudit_exec_socket", "phpaudit_exec_curl", "file_get_contents" ) as $license_method )
        {
            $sinfo = @parse_url( $server );
            if ( $license_method == "phpaudit_exec_socket" && !$data )
            {
                $data = @licensing::phpaudit_exec_socket( $sinfo['host'], $sinfo['path'], "/validate_internal.php", $query_string );
            }
            if ( $license_method == "phpaudit_exec_curl" && !$data )
            {
                $data = @licensing::phpaudit_exec_curl( "{$server}/validate_internal.php?{$query_string}" );
            }
            if ( $license_method == "file_get_contents" && !$data )
            {
                $data = @file_get_contents( "{$server}validate_internal.php?{$query_string}" );
            }
            if ( !$data )
            {
                continue;
            }
            licensing::write_best_method( $license_method );
            break;
            break;
        }
        return $data;
    }

    public function go_remote_api( $RPC, $api_fingerprint, $license )
    {
        $use = parse_url( $RPC );
        $fp = @fsockopen( $use['host'], 80, $errno, $errstr, 10 );
        if ( !$fp )
        {
            return false;
        }
        $header = "POST {$use['path']} HTTP/1.0\r\n";
        $header .= "Host: {$use['host']}\r\n";
        $header .= "Content-type: application/x-www-form-urlencoded\r\n";
        $header .= "User-Agent: SolidPHP Business Automation Software (SPBAS) (http://www.spbas.com)\r\n";
        $header .= "Content-length: ".@strlen( $querystring = "mod=license&task=get_local_key&api_key={$api_fingerprint}&license_key={$license}" )."\r\n";
        $header .= "Connection: close\r\n\r\n";
        $header .= $querystring;
        $local_key = "";
        if ( @function_exists( "stream_set_timeout" ) )
        {
            stream_set_timeout( $fp, 20 );
        }
        @fputs( $fp, $header );
        if ( @function_exists( "socket_get_status" ) )
        {
            $status = @socket_get_status( $fp );
        }
        else
        {
            $status = true;
        }
        while ( !feof( $fp ) && $status )
        {
            $local_key .= @fgets( $fp, 1024 );
            if ( @function_exists( "socket_get_status" ) )
            {
                $status = @socket_get_status( $fp );
            }
            else if ( feof( $fp ) == true )
            {
                $status = false;
            }
            else
            {
                $status = true;
            }
        }
        @fclose( $fp );
        $local_key = @explode( "\r\n\r\n", $local_key, 2 );
        licensing::store_local_key( $local_key[1] );
        return $local_key[1];
    }

}

function obfRunBeforeFilter( &$_obfuscate_11 )
{
    if ( !empty( $_obfuscate_11->Auth ) )
    {
        $_obfuscate_11->Auth->allow( "register", "reset", "activate", "tracking", "admin_user", "index" );
    }
}

function obfRunGetEndTime( )
{
    return date( "Y-m-d H:i:s", strtotime( "-1 minute" ) );
}

function obfRunAdminAdd( &$_obfuscate_3 )
{
    if ( !empty( $_obfuscate_3->data ) )
    {
        $_obfuscate_3->data['User']['before_password'] = $_obfuscate_3->User->generateRandomPassword( );
        if ( $_obfuscate_4 = $_obfuscate_3->User->register( $_obfuscate_3->data, true ) )
        {
            $_obfuscate_4['User']['password'] = $_obfuscate_3->data['User']['before_password'];
            if ( $_obfuscate_3->_sendEmail( $_obfuscate_4 ) )
            {
                $_obfuscate_3->Session->setFlash( __( "The user has been added and their username and password details have been sent to them.", true ) );
                $_obfuscate_3->redirect( array( "action" => "index" ) );
            }
            else
            {
                ( $_obfuscate_3->Session->setFlash(  ) );
            }
        }
        else
        {
            $_obfuscate_3->Session->setFlash( __( "There was a problem adding the user please review the errors below and try again.", true ) );
        }
    }
    $_obfuscate_3->set( "genders", $_obfuscate_3->Gender->find( "list" ) );
}

function obfRunIndex( &$_obfuscate_3 )
{
    $_obfuscate_3->set( "user", $_obfuscate_3->User->read( null, $_obfuscate_3->Auth->user( "id" ) ) );
    $_obfuscate_5 = array( );
    foreach ( $_obfuscate_3->User->Address->addressTypes( ) as $_obfuscate_7 => $_obfuscate_6 )
    {
        $_obfuscate_5[$_obfuscate_6] = $_obfuscate_3->User->Address->find( "first", array( "conditions" => array( "Address.user_id" => $_obfuscate_3->Auth->user( "id" ), "Address.user_address_type_id" => $_obfuscate_6 ) ) );
    }
    $_obfuscate_3->set( "userAddress", $_obfuscate_5 );
    $_obfuscate_3->set( "unpaidAuctions", $_obfuscate_3->User->Auction->find( "count", array( "conditions" => array( "Auction.winner_id" => $_obfuscate_3->Auth->user( "id" ), "Status.id" => 1 ) ) ) );
    $_obfuscate_3->pageTitle = __( "Dashboard", true );
}

?>
