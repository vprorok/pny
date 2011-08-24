<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" type="text/javascript">
//<![CDATA[
function clearText(thefield){
if (thefield.defaultValue==thefield.value)
thefield.value = ""
} 
//}}>
</script>
<?php echo $html->charset(); ?>
<title><?php echo $title_for_layout; ?> / Admin Panel [powered by phpPennyAuction]</title>
<?php
                echo $html->meta('icon');
                echo $html->css('admin/style');
                echo $html->css('tabmenu2');
        ?><!--[if lt IE 7]>
        <?php echo $html->css('admin-ie'); ?>
    <![endif]-->
<?php
        echo $javascript->link('jquery/jquery');
        echo $javascript->link('jquery/ui');
        echo $javascript->link('admin');
                echo $scripts_for_layout;
        ?>
<script type="text/javascript">
//<![CDATA[
        sfHover = function() {
            var sfEls = document.getElementById("nav").getElementsByTagName("LI");
            for (var i=0; i<sfEls.length; i++) {
                sfEls[i].onmouseover=function() {
                    this.className+=" sfhover";
                }
                sfEls[i].onmouseout=function() {
                    this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
                }
            }
        }
        if (window.attachEvent) {
            window.attachEvent("onload", sfHover);
        }
//]]>
</script>
<style type="text/css">
/*<![CDATA[*/
 div.c5 {clear:both}
 iframe.c4 {border:none; overflow:hidden; width:100px; height:21px;}
 select.c3 {visibility: hidden !important;}
 option.c2 {visibility: hidden;}
 input.c1 {max-width:90px;}
/*]]>*/
</style>
</head>
<body>
<div id="wrapper"><?php echo $this->element('admin/menu_top');?>
<div id="container">
<div id="header" class="clearfix container">
<div class="logo"><?php echo $html->link($html->image('admin/logo.png'), array('controller' => 'dashboards', 'action' => 'index', 'admin' => 'admin'), null, null , false);?></div>
  <div id="search">
                                                <form name="search" action="https://members.phppennyauction.com/index.php" method="POST" target="_blank">
                                                        <input name="searchquery" class="searchtext" type="text" style="max-width:90px;" value="[Enter keyword]" onFocus="clearText(this)">
                                                        <input type="submit" name="Submit" value="Search" class="searchbuttonc">
                                                        <select name="searchtype" class="searchselect" style="visibility: hidden !important;">
                                                                <option value="all" selected style="visibility: hidden;">-- Support Site --</option>                                                                            
                                                        </select>
                                                        <input type="hidden" name="_m" value="core"><input type="hidden" name="_a" value="searchclient">
                                                </form>
                                        </div><?php echo $this->element('admin/menu');?></div>
<?php
                                if($session->check('Message.flash')){
                                        $session->flash();
                                }

                                if($session->check('Message.auth')){
                                        $session->flash('auth');
                                }
                        ?>
<div id="content_container">
<div id="left_side"><span class="header"><img src="../../admin/img/home.png" class="absmiddle" width="16" height="16" alt="** PLEASE DESCRIBE THIS IMAGE **" />  Quick Links</span>
<ul class="menu">
<li><a href="/admin/products/add">Add a New Product</a></li>
<li><a href="/admin/auctions/add">Start a New Auction</a></li>
<li><a href="/admin/users/add">Create a New User</a></li>
<li><a href="/admin/newsletters/add">Send a Newsletter</a></li>
<li><a href="/admin/pages/index">View List Of Pages</a></li>
<li><a href="/admin/settings/index">Adjust Your Main Settings</a></li>
</ul>
<br />
<span class="new_header">License Information</span>
<div class="smallfont">Type: Full License<br />
Expires: Never<br />
Version: <?= PHPPA_VERSION ?>
<br />
<br />
[<a href="https://members.phppennyauction.com" target="_blank">Check for updates</a>]<br />
<br />
<br />
<span class="new_header">Social Networking</span> <a href="http://twitter.com/phppennyauction" target="_blank"><img src="../../admin/img/admin_twitter.png" alt="" class="absmiddle" /></a>  <a href="http://twitter.com/phppennyauction" target="_blank">Follow us on Twitter</a><br />
<a href="http://www.facebook.com/scriptmatixltd" target="_blank"><img src="../../admin/img/admin_facebook.png" alt="" class="absmiddle" /></a>  <a href="http://www.facebook.com/scriptmatixltd" target="_blank">Follow us on Facebook</a><br />
<a href="http://www.phppennyauction.com/blog/" target="_blank"><img src="../../admin/img/admin_blog.png" alt="" class="absmiddle" /></a>  <a href="http://www.phppennyauction.com/blog/" target="_blank">Check out the Blog</a><br />
<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.phppennyauction.com&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" class="c4"></iframe></div>
<br />
<br /></div>
<div id="right_side"><?php echo $content_for_layout; ?></div>
<div class="c5"></div>
<!-- footer Starts -->
<br />
<br />
<br />
<br />
<div class="col-left" id="footer"><a href="/" target="_blank">View Your Website</a> | <a href="https://members.phppennyauction.com" target="_blank">Support Center</a> | <a href="https://members.phppennyauction.com" target="_blank">Member Forums</a><br />
<?php include 'footer_copyright.php'; ?>
<div class="fix"></div>
</div>
<!-- footer Ends -->
</div>
</div>
<?php echo $cakeDebug; ?></div>
</body>
</html>