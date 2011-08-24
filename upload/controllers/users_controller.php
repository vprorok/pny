<?php
require_once( "..".DS."controllers".DS."users_module1.php" );
class UsersController extends AppController
{
	
    public $name = "Users";
    public $restricted_users_delete = array
    (
        0 => 1
    );
    public $uses = array
    (
        0 => "User",
        1 => "Setting",
        2 => "Gender"
    );
    public $helpers = array
    (
        0 => "Recaptcha"
    );
    public $components = array
    (
        0 => "Recaptcha",
        1 => "PhpBB3"
    );

    public function beforeFilter( )
    {
        parent::beforefilter( );
        obfGetBeforeFilter( $this );
    }

    public function login( )
    {
        $returned = true; //obfGetLicensing( $this );
        if ( $returned === FALSE )
        {
            if ( $this->Cookie->read( "User.id" ) )
            {
                $this->Cookie->del( "User.id" );
            }
            if ( $this->Cookie->read( "user_id" ) )
            {
                $this->Cookie->del( "user_id" );
            }
            $this->Session->delete( "Auth.User" );
            unset( $this->User['password'] );
            $this->render( );
            $this->Auth->logout( );
        }
        else
        {

            if ( !empty( $this->data ) )
            {
                if ( $this->Auth->login( ) )
                {
                    if ( $this->Auth->user( "active" ) == 1 )
                    {
                        if ( !empty( $this->data['User']['remember_me'] ) )
                        {
                            $this->Cookie->write( "User.id", $this->Auth->user( "id" ), true, $this->appConfigurations['remember_me'] );
                            unset( $this->User['remember_me'] );
                        }
                        else
                        {
                            $this->Cookie->write( "User.id", $this->Auth->user( "id" ), true, "+1 hour" );
                        }
                        if ( Configure::read( "App.forum" ) )
                        {
                            $this->PhpBB3->login( $this->Auth->user( "username" ), $this->Auth->user( "key" ), $this->Auth->user( "email" ) );
                        }
                        $this->Session->setFlash( __( "You have successfully logged in.", true ), "default", array( "class" => "success" ) );
                        if ( $this->Session->read( "justActivated" ) )
                        {
                            $this->Session->delete( "justActivated" );
                            if ( !empty( $this->appConfigurations['sslUrl'] ) )
                            {
                                $this->redirect( $this->appConfigurations['url']."/packages" );
                            }
                            else
                            {
                                $this->redirect( array( "controller" => "packages", "action" => "index" ) );
                            }
                        }
                        else if ( !empty( $this->data['User']['url'] ) )
                        {
                            if ( !empty( $this->appConfigurations['sslUrl'] ) )
                            {
                                $this->redirect( $this->appConfigurations['url'].$this->data['User']['url'] );
                            }
                            else
                            {
                                $this->redirect( $this->data['User']['url'] );
                            }
                        }
                        else if ( !empty( $this->appConfigurations['loginRedirect'] ) )
                        {
                            if ( !empty( $this->appConfigurations['sslUrl'] ) )
                            {
                                $this->redirect( $this->appConfigurations['url'].$this->redirect( $this->appConfigurations['loginRedirect'] ) );
                            }
                            else
                            {
                                $this->redirect( $this->appConfigurations['loginRedirect'] );
                            }
                        }
                        else
                        {
                            $this->redirect( array( "action" => "login" ) );
                        }
                    }
                    else if ( !$this->Auth->user( "email" ) && $this->Auth->user( "mobile" ) )
                    {
                        $this->Session->write( "Sms.id", $this->Auth->user( "id" ) );
                        $this->Auth->logout( );
                        $this->Session->setFlash( __( "You have an SMS account only.  Please create an online account in order to access the features on the website.", true ) );
                        $this->redirect( array( "action" => "register" ) );
                    }
                    else
                    {
                        $this->Auth->logout( );
                        $this->Session->setFlash( __( "Your account has not been actived yet or your account has been suspended.", true ) );
                        if ( !empty( $this->data['User']['url'] ) )
                        {
                            $this->redirect( $this->data['User']['url'] );
                        }
                        else if ( !empty( $this->appConfigurations['loginRedirect'] ) )
                        {
                            if ( !empty( $this->appConfigurations['sslUrl'] ) )
                            {
                                $this->redirect( $this->appConfigurations['url'].$this->redirect( $this->appConfigurations['loginRedirect'] ) );
                            }
                            else
                            {
                                $this->redirect( $this->appConfigurations['loginRedirect'] );
                            }
                        }
                        else
                        {
                            $this->redirect( array( "action" => "login" ) );
                        }
                    }
                }
            }
            else
            {
                if ( !empty( $this->appConfigurations['sslUrl'] ) && empty( $_SERVER['HTTPS'] ) )
                {
                    $this->redirect( $this->appConfigurations['sslUrl']."/users/login" );
                }
                $id = $this->Auth->user( "id" );
                if ( !empty( $id ) )
                {
                    if ( !empty( $this->appConfigurations['sslUrl'] ) )
                    {
                        $this->redirect( $this->appConfigurations['url']."/users" );
                    }
                    else
                    {
                        $this->redirect( array( "action" => "index" ) );
                    }
                }
            }
		
            $this->pageTitle = __( "Login", true );
           // unset( $this->User['password'] );
        }
    }

    public function logout( )
    {
        if ( $this->Cookie->read( "User.id" ) )
        {
            $this->Cookie->del( "User.id" );
        }
        if ( $this->Cookie->read( "user_id" ) )
        {
            $this->Cookie->del( "user_id" );
        }
        if ( Configure::read( "App.forum" ) )
        {
            $this->PhpBB3->logout( );
        }
        $this->Session->setFlash( __( "You have been successfully logged out.", true ), "default", array( "class" => "success" ) );
        if ( !empty( $this->appConfigurations['logoutRedirect'] ) )
        {
            $this->Auth->logout( );
            $this->redirect( $this->appConfigurations['logoutRedirect'] );
        }
        else
        {
            $this->redirect( $this->Auth->logout( ) );
        }
    }

    public function index( )
    {
        obfGetIndex( $this );
    }

    public function reset( )
    {
        if ( Configure::read( "SCD" ) && Configure::read( "SCD.isSCD" ) === true )
        {
            return false;
        }
        if ( !empty( $this->data ) )
        {
            if ( $this->_sendEmail( $data ) )
            {
                $this->Session->setFlash( __( "An email containing your account details has been sent. Please check your email.", true ), "default", array( "class" => "success" ) );
                $this->redirect( array( "action" => "login" ) );
            }
            else
            {
                $this->Session->setFlash( __( "Email sending failed. Please try again or contact administrator.", true ) );
                $this->Session->setFlash( __( "The email address you entered is not assigned to any member.", true ) );
            }
        }
        $this->pageTitle = __( "Reset Your Password", true );
    }

    public function activate( $key )
    {
        if ( !empty( $key ) )
        {
            $user = $this->User->activate( $key );
            if ( !empty( $user ) )
            {
                $data['template'] = "users/welcome";
                $data['layout'] = "default";
                $data['to'] = $user['User']['email'];
                $data['subject'] = sprintf( __( "Thank you for joining %s", true ), $this->appConfigurations['name'] );
                $data['User'] = $user['User'];
                $this->set( "data", $Var_888 );
                $this->_sendEmail( $data );
                $this->Session->write( "justActivated", 1 );
                $setting = Configure::read( "Settings.free_registeration_bids" );
                if ( is_numeric( $setting ) && 0 < $setting )
                {
                    if ( $this->appConfigurations['simpleBids'] == true )
                    {
                        $user['User']['bid_balance'] += $setting;
                        $this->User->save( $user );
                    }
                    else
                    {
                        $bidData['Bid']['user_id'] = $user['User']['id'];
                        $bidData['Bid']['description'] = __( "Free bids given for registering.", true );
                        $bidData['Bid']['credit'] = $setting;
                        $this->User->Bid->create( );
                        $this->User->Bid->save( $bidData );
                    }
                    $this->Session->setFlash( __( "Your account has been activated and some free bids have been added to your account. Please login using your username and password.", true ), "default", array( "class" => "success" ) );
                }
                else
                {
                    $this->Session->setFlash( __( "Your account has been activated. Please login using your username and password.", true ), "default", array( "class" => "success" ) );
                }
                $this->redirect( array( "action" => "login" ) );
            }
            else
            {
                $this->Session->setFlash( __( "Invalid activation key or you have already been activated. Please try again or contact the administrator.", true ) );
                $this->redirect( array( "action" => "login" ) );
            }
        }
        else
        {
            $this->redirect( array( "action" => "login" ) );
        }
    }

    public function register( $referrer = null )
    {
        if ( !empty( $this->appConfigurations['registerOff'] ) )
        {
            $this->Session->setFlash( __( "Registration has been turned off.", true ), "default", array( "class" => "message" ) );
            $this->redirect( array( "controller" => "auctions", "action" => "home" ) );
        }
        if ( !empty( $this->data ) )
        {
            if ( $this->Recaptcha->isValid( ) || Configure::read( "Recaptcha.enabled" ) == false )
            {
                if ( $this->appConfigurations['demoMode'] )
                {
                    $this->data['User']['admin'] = 1;
                }
                else
                {
                    $this->data['User']['admin'] = 0;
                }
                if ( isset( $this->data['User']['terms'] ) && $this->data['User']['terms'] == 0 )
                {
                    $this->data['User']['terms'] = null;
                }
                if ( $data = $this->User->register( $this->data, false, $this->Session->read( "Sms.id" ) ) )
                {
                    $this->Session->del( "Sms.id" );
                    if ( Configure::read( "App.coupons" ) && $this->Session->check( "Coupon" ) )
                    {
                        $coupon = $this->Session->read( "Coupon" );
                        if ( $coupon['Coupon']['coupon_type_id'] == 6 )
                        {
                            $bid['Bid']['user_id'] = $data['User']['id'];
                            $bid['Bid']['credit'] = $coupon['Coupon']['saving'];
                            $bid['Bid']['description'] = __( "Free registration bids", true );
                            $this->User->Bid->create( );
                            $this->User->Bid->save( $bid );
                        }
                    }
                    if ( $this->_sendEmail( $data ) )
                    {
                        $this->Session->setFlash( __( "Thank you for registering.  An email has been sent to your email address, please check your email in order to activate your account.  If you fail to receive an email please check your SPAM box and add as an accepted sender.", true ), "default", array( "class" => "success" ) );
                        if ( Configure::read( "GoogleTracking.registration.active" ) )
                        {
                            if ( !empty( $this->appConfigurations['sslUrl'] ) )
                            {
                                $this->redirect( $this->appConfigurations['url']."/users/tracking" );
                            }
                            else
                            {
                                $this->redirect( array( "action" => "tracking" ) );
                            }
                        }
                        else if ( !empty( $this->appConfigurations['sslUrl'] ) )
                        {
                            $this->redirect( $this->appConfigurations['url']."/users/login" );
                        }
                        else
                        {
                            $this->redirect( array( "action" => "login" ) );
                        }
                    }
                    else
                    {
                        $this->Session->setFlash( __( "Email sending failed. Please try again or contact the administrator.", true ) );
                    }
                }
                else
                {
                    $this->Session->setFlash( __( "There was a problem creating your account please review the errors below and try again.", true ), "default", array( "class" => "message" ) );
                }
            }
            else
            {
                $this->Session->setFlash( __( "The captcha form was not valid, please try again.", true ), "default", array( "class" => "message" ) );
                $this->set( "recaptchaError", $this->Recaptcha->error );
            }
        }
        else
        {
            if ( !empty( $this->appConfigurations['sslUrl'] ) && empty( $_SERVER['HTTPS'] ) )
            {
                $this->redirect( $this->appConfigurations['sslUrl']."/users/register/".$referrer );
            }
            $id = $id = $this->Auth->user( "id" );
            if ( !empty( $id ) )
            {
                if ( !empty( $this->appConfigurations['sslUrl'] ) )
                {
                    $this->redirect( $this->appConfigurations['url']."/users" );
                }
                else
                {
                    $this->redirect( array( "action" => "index" ) );
                }
            }
            $this->data['User']['referrer'] = $referrer;
            if ( !empty( $this->appConfigurations['newsletterSelected'] ) )
            {
                $this->data['User']['newsletter'] = 1;
            }
            if ( !empty( $this->appConfigurations['ipBlock'] ) && !empty( $_SERVER['REMOTE_ADDR'] ) )
            {
                $totalIps = $this->User->find( "count", array(
                    "conditions" => array(
                        "User.ip" => $_SERVER['REMOTE_ADDR']
                    )
                ) );
                if ( $this->appConfigurations['ipBlock'] < $totalIps )
                {
                    $this->Session->setFlash( __( "Your IP address has been used too many times for creating an account. You cannot create any more accounts.", true ), "default", array( "class" => "message" ) );
                    if ( !empty( $this->appConfigurations['sslUrl'] ) )
                    {
                        $this->redirect( $this->appConfigurations['url']."/auctions" );
                    }
                    else
                    {
                        $this->redirect( array( "controller" => "auctions", "action" => "index" ) );
                    }
                }
            }
            if ( $this->Session->check( "Sms.id" ) )
            {
                $this->data = $this->Session->read( "Sms.id", $Var_5856 );
                $this->data['User']['username'] = "";
            }
        }
        $this->set( "genders", $this->Gender->find( "list" ) );
        if ( ( $sources = Cache::read( "sources", "week" ) ) === FALSE )
        {
            $sources = $this->User->Source->find( "all", array(
                "order" => "Source.order ASC",
                "recursive" => 0 - 1
            ) );
            Cache::write( "sources", $sources, "week" );
        }
        $this->set( "sources", $sources );
        $this->pageTitle = __( "Register", true );
    }

    public function edit( )
    {
        if ( !empty( $this->data ) )
        {
            $this->data['User']['id'] = $this->Auth->user( "id" );
            if ( $this->Auth->user( "admin" ) == 0 )
            {
                $this->data['User']['admin'] = 0;
            }
            if ( $this->User->save( $this->data ) )
            {
                $this->Session->setFlash( __( "Your account has been updated successfully.", true ), "default", array( "class" => "success" ) );
                if ( !empty( $this->appConfigurations['sslUrl'] ) )
                {
                    $this->redirect( $this->appConfigurations['url']."/users" );
                }
                else
                {
                    $this->redirect( array( "action" => "index" ) );
                }
            }
            else
            {
                $this->Session->setFlash( __( "There was a problem updating your account please review the errors below and try again.", true ) );
            }
        }
        else
        {
            if ( !empty( $this->appConfigurations['sslUrl'] ) && empty( $_SERVER['HTTPS'] ) )
            {
                $this->redirect( $this->appConfigurations['sslUrl']."/users/edit" );
            }
            $this->data = $this->User->read( null, $this->Auth->user( "id" ) );
        }
        $this->set( "genders", $this->Gender->find( "list" ) );
        $this->pageTitle = __( "Edit Profile", true );
    }

    public function changepassword( )
    {
        if ( Configure::read( "SCD" ) && Configure::read( "SCD.isSCD" ) === true )
        {
            $this->demoDisabled( );
        }
        if ( !empty( $this->data ) )
        {
            $this->data['User']['id'] = $this->Auth->user( "id" );
            if ( $this->Auth->user( "admin" ) == 0 )
            {
                $this->data['User']['admin'] = 0;
            }
            if ( !empty( $this->data['User']['before_password'] ) )
            {
                $this->data['User']['password'] = Security::hash( Configure::read( "Security.salt" ).$this->data['User']['before_password'] );
            }
            $this->User->set( $this->data );
            if ( $this->User->validates( ) )
            {
                if ( $this->data['User']['before_password'] == $this->data['User']['retype_password'] )
                {
                    if ( $this->User->save( $this->data, false ) )
                    {
                        if ( Configure::read( "App.forum" ) )
                        {
                            $this->PhpBB3->changePassword( $this->Auth->user( "username" ), $this->data['User']['retype_password'] );
                        }
                        $this->Session->setFlash( __( "Your password has been successfully changed.", true ), "default", array( "class" => "success" ) );
                        if ( !empty( $this->appConfigurations['sslUrl'] ) )
                        {
                            $this->redirect( $this->appConfigurations['url']."/users" );
                        }
                        else
                        {
                            $this->redirect( array( "action" => "index" ) );
                        }
                    }
                    else
                    {
                        $this->Session->setFlash( __( "There was a problem changing your password.  Please review the errors below and try again.", true ) );
                    }
                }
                else
                {
                    $this->Session->setFlash( __( "The new password does not match.", true ) );
                }
            }
        }
        $this->pageTitle = __( "Change Password", true );
    }

    public function points( )
    {
        $points = $this->User->Point->balance( $this->Auth->user( "id" ) );
        return $points;
    }

    public function tracking( )
    {
    }

    public function cancel( )
    {
        if ( !empty( $this->data ) )
        {
            $security = $this->Session->read( "CancelAccountSec" );
            $passSecurity = false;
            if ( !empty( $security ) && $this->data['User']['security'] == $security )
            {
                $passSecurity = true;
                $this->Session->delete( "CancelAccountSec" );
            }
            if ( !$passSecurity )
            {
                $this->Session->setFlash( __( "Please use button in Cancel Account page to cancel your account.", true ) );
                $this->redirect( array( "index" ) );
            }
            $this->User->id = $this->Auth->user( "id" );
            if ( $this->User->saveField( "active", 0 ) )
            {
                $this->Session->setFlash( __( "Your account has been cancelled and you have been automatically logged out.", true ), "default", array( "class" => "success" ) );
                $this->redirect( array( "action" => "logout" ) );
            }
            else
            {
                $this->Session->setFlash( __( "Your account cannot be cancelled. Please try again.", true ) );
                $this->redirect( array( "action" => "index" ) );
            }
        }
        else
        {
            $security = Security::hash( time( ) + mt_rand( 100, 999 ) );
            $this->Session->write( "CancelAccountSec", $security );
            $this->set( "security", $security );
        }
    }

    public function admin_login( )
    {
        $this->redirect( "/users/login" );
    }

    public function admin_index( )
    {
        $this->paginate = array(
            "conditions" => array( "User.autobidder" => 0 ),
            "limit" => $this->appConfigurations['adminPageLimit'],
            "order" => array( "User.created" => "desc" )
        );
        $this->set( "users", $this->paginate( ) );
    }

    public function admin_search( )
    {
        if ( !empty( $this->data['User']['name'] ) || !empty( $this->data['User']['email'] ) || !empty( $this->data['User']['username'] ) )
        {
            $email = $this->data['User']['email'];
            $username = $this->data['User']['username'];
            $conditions = array( );
            if ( !empty( $this->data['User']['name'] ) )
            {
                $conditions[] = "User.first_name LIKE '%".$this->data['User']['name']."%' OR User.last_name LIKE '%".$this->data['User']['name']."%'";
            }
            if ( !empty( $this->data['User']['email'] ) )
            {
                $conditions[] = array(
                    "User.email" => $this->data['User']['email']
                );
            }
            if ( !empty( $this->data['User']['username'] ) )
            {
                $conditions[] = "User.username LIKE '%".$this->data['User']['username']."%'";
            }
            $conditions[] = array( "User.autobidder" => 0 );
            $this->paginate = array(
                "limit" => $this->appConfigurations['adminPageLimit'],
                "order" => array( "User.created" => "desc" )
            );
            $this->set( "users", $this->paginate( "User", $conditions ) );
        }
        else
        {
            $this->Session->setFlash( __( "Please enter at least one search term", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
    }

    public function admin_view( $id = null )
    {
        if ( empty( $id ) )
        {
            $this->Session->setFlash( __( "Invalid User.", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
        $user = $this->User->read( null, $id );
        if ( empty( $user ) )
        {
            $this->Session->setFlash( __( "Invalid User.", true ) );
            $this->redirect( $Tmp_24 );
        }
        $this->set( "user", $user );
        $address_types = $this->User->Address->addressTypes( );
        $userAddress = array( );
        $addressRequired = 0;
        foreach ( $address_types as $key => $type )
        {
            $userAddress[$type] = $this->User->Address->find( "first", array(
                "conditions" => array(
                    "Address.user_id" => $id,
                    "Address.user_address_type_id" => $key
                )
            ) );
        }
        $this->set( "address", $userAddress );
        if ( !empty( $user['Referral'] ) )
        {
            $this->set( "referral", $this->User->Referral->find( "first", array(
                "conditions" => array(
                    "Referral.user_id" => $user['User']['id']
                )
            ) ) );
        }
        $this->set( "genders", $this->Gender->find( "list" ) );
    }

    public function admin_resend( $id = null )
    {
        if ( empty( $id ) )
        {
            $this->Session->setFlash( __( "Invalid User.", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
        $user = $this->User->read( null, $id );
        $user['User']['activate_link'] = $this->appConfigurations['url']."/users/activate/".$user['User']['key'];
        $user['to'] = $user['User']['email'];
        $Var_816['subject'] = sprintf( __( "Account Activation - %s", true ), $this->appConfigurations['name'] );
        $user['template'] = "users/activate";
        if ( $this->_sendEmail( $user ) )
        {
            $this->Session->setFlash( __( "Activation email has been sent to user email address.", true ) );
        }
        else
        {
            $this->Session->setFlash( __( "Activation email sending failed. Please try again.", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
    }

    public function admin_add( )
    {
        obfGetAdminAdd( $this );
    }

    public function admin_edit( $id = null )
    {
        if ( !$id && empty( $this->data ) )
        {
            $this->Session->setFlash( __( "Invalid User", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
        if ( !empty( $this->data ) )
        {
            if ( $this->User->save( $this->data ) )
            {
                $this->Session->setFlash( __( "There user has been updated successfully.", true ) );
                $this->redirect( array( "action" => "index" ) );
            }
            else
            {
                $this->Session->setFlash( __( "There was a problem updating the users details please review the errors below and try again.", true ) );
            }
        }
        if ( empty( $this->data ) )
        {
            $this->data = $this->User->read( null, $id );
            if ( empty( $this->data ) )
            {
                $this->Session->setFlash( __( "Invalid User", true ) );
                $this->redirect( array( "action" => "index" ) );
            }
        }
        $this->set( "genders", $this->Gender->find( "list" ) );
    }

    public function admin_delete( $id = null, $autobid = null )
    {
        if ( !$id )
        {
            $this->Session->setFlash( __( "Invalid id for User", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
        if ( $this->User->del( $id ) )
        {
            $this->Session->setFlash( __( "The user was successfully deleted.", true ) );
        }
        else
        {
            $this->Session->setFlash( __( "There was a problem deleting the user.", true ) );
        }
        if ( !empty( $autobid ) )
        {
            $this->redirect( array( "action" => "autobidders" ) );
        }
        else
        {
            $this->redirect( array( "action" => "index" ) );
        }
    }

    public function admin_suspend( $id = null )
    {
        if ( !$id )
        {
            $this->Session->setFlash( __( "Invalid id for User", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
        $user = $this->User->read( null, $id );
        $user['User']['active'] = 0;
        $this->User->save( $user );
        $this->Session->setFlash( __( "The user was successfully suspended.", true ) );
        $this->redirect( array( "action" => "index" ) );
    }

    public function admin_autobidders( )
    {
        $this->paginate = array(
            "contain" => array( "Auction", "Bid" ),
            "conditions" => array( "User.autobidder" => 1 ),
            "limit" => $this->appConfigurations['adminPageLimit'],
            "order" => array( "User.created" => "desc" )
        );
        $this->set( "users", $this->paginate( ) );
    }

    public function admin_autobidder_add( )
    {
        if ( !empty( $this->data ) )
        {
            $this->data['User']['autobidder'] = 1;
            $this->User->create( );
            if ( $this->User->save( $this->data ) )
            {
                $this->Session->setFlash( __( "The auto bidder was added successfully.", true ) );
                $this->redirect( array( "action" => "autobidders" ) );
            }
            else
            {
                $this->Session->setFlash( __( "There was a problem adding the user please review the errors below and try again.", true ) );
            }
        }
    }

    public function admin_autobidder_edit( $id = null )
    {
        if ( !$id && empty( $this->data ) )
        {
            $this->Session->setFlash( __( "Invalid User", true ) );
            $this->redirect( array( "action" => "index" ) );
        }
        if ( !empty( $this->data ) )
        {
            $this->User->id = $id;
            if ( $this->User->save( $this->data ) )
            {
                $this->Session->setFlash( __( "The autobidder has been updated successfully.", true ) );
                $this->redirect( array( "action" => "autobidders" ) );
            }
            else
            {
                $this->Session->setFlash( __( "There was a problem updating the autobidder please review the errors below and try again.", true ) );
            }
        }
        if ( empty( $this->data ) )
        {
            $this->data = $this->User->read( null, $id );
            if ( empty( $this->data ) )
            {
                $this->Session->setFlash( __( "Invalid User", true ) );
                $this->redirect( array( "action" => "index" ) );
            }
        }
    }

    public function admin_changepassword( $id )
    {
        $this->User->id = $id;
        $this->User->recursive = 0 - 1;
        $user = $this->User->read( );
        $this->set( "user", $user );
        if ( !empty( $this->data ) )
        {
            if ( !empty( $this->data['User']['before_password'] ) )
            {
                $this->data['User']['id'] = $user['User']['id'];
                $this->data['User']['password'] = Security::hash( Configure::read( "Security.salt" ).$this->data['User']['before_password'] );
            }
            $this->User->set( $this->data );
            if ( $this->User->validates( ) )
            {
                if ( $this->data['User']['before_password'] == $this->data['User']['retype_password'] )
                {
                    if ( $this->User->save( $this->data, false ) )
                    {
                        $this->Session->setFlash( __( "Your password has been successfully changed.", true ), "default", array( "class" => "success" ) );
                        $this->redirect( array( "action" => "index" ) );
                    }
                    else
                    {
                        $this->Session->setFlash( __( "There was a problem changing your password.  Please review the errors below and try again.", true ) );
                    }
                }
                else
                {
                    $this->Session->setFlash( __( "The new password does not match.", true ) );
                }
            }
        }
    }

    public function admin_online( )
    {
        $dir = TMP.DS."cache".DS;
        $files = scandir( $dir );
        $users = array( );
        foreach ( $files as $filename )
        {
            if ( is_dir( $dir.$filename ) )
            {
                continue;
            }
            if ( substr( $filename, 0, 16 ) == "cake_user_count_" )
            {
                $data = explode( "_", $filename );
                $users[] = $this->User->find( "first", array(
                    "conditions" => array(
                        "User.id" => $data[3]
                    ),
                    "contain" => ""
                ) );
            }
        }
        $this->set( "users", $users );
    }

    public function getEndTime( )
    {
        return obfGetGetEndTime( );
    }

}

?>
