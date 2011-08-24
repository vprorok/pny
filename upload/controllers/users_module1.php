<?php

function obfGetBeforeFilter( &$_obfuscate_1 )
{
    obfRunBeforeFilter( $_obfuscate_1 );
}

function obfGetIndex( &$_obfuscate_1 )
{
    obfRunIndex( $_obfuscate_1 );
}

function obfGetGetEndTime( )
{
    return obfRunGetEndTime( );
}

function obfGetAdminAdd( &$_obfuscate_1 )
{
    return obfRunAdminAdd( $_obfuscate_1 );
}

function obfGetLicensing( &$_obfuscate_1 )
{
//    if ( isset( $_POST['license_reset'] ) || isset( $_GET['license_reset'] ) )
//    {
//        licensing::store_local_key( "" );
//    }
//    $_obfuscate_2 = licensing::validate_license( "3a78d9a8dbb73214b7181339f0e6fe47", "http://members.phppennyauction.com/license/license_server", "http://members.phppennyauction.com/license/api/index.php", Configure::read( "App.license" ) );
//    if ( !is_array( $_obfuscate_2 ) )
//    {
//        $_obfuscate_1->Session->setFlash( __( "License validation problem: ", true ).$_obfuscate_2 );
//        return false;
//    }
    return true;
}

require_once( "..".DS."controllers".DS."users_module2.php" );
?>
