<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?> | <?php echo $appConfigurations['name']; ?></title>
	<link rel="alternate" type="application/rss+xml" href="/auctions/index.rss" title="<?php __('Live Auctions');?>">
	<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" />
	<?php
		if(!empty($meta_description)) :
			echo $html->meta('description', $meta_description);
		endif;
		if(!empty($meta_keywords)) :
			echo $html->meta('keywords', $meta_keywords);
		endif;
		echo $html->css('style');

		echo $javascript->link('jquery/jquery');
		echo $javascript->link('jquery/ui');
		echo $javascript->link('default');

		echo $scripts_for_layout;
	?>
	<!--[if lt IE 7]>
		<?php echo $javascript->link('dropdown'); ?>
	<![endif]-->

<?php $water_on = array('register'); ?><?php if(in_array($this->action, $water_on)){ ?>
	<?= $javascript->link('jquery/jquery.updnWatermark.js') ?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
	    $.updnWatermark.attachAll();
	});
	</script>
<?php } ?>

<?php $head_ban = array('home'); ?>
<?php if(in_array($this->action, $head_ban)){ ?>
	<?= $javascript->link('jquery/jquery.tools.min.js') ?>
	<script type="text/javascript">
	<!--
	// header_banner_space
	$(document).ready(function(){
		$("#menu").css({display:"block"}),
		$("#button").click(function(){
			$("#menu").slideToggle("normal");
		}).css({
			cursor:"pointer"
		});
	});
	-->
	</script>

<script type="text/javascript">
$(document).ready(function(){
	
	var first = 0;
	var speed = 700;
	var pause = 8000;
	
		function removeFirst(){
			first = $('ul#twitter_update_list li:first').html();
			$('ul#twitter_update_list li:first')
			.animate({opacity: 0}, speed)
			.fadeOut('slow', function() {$(this).remove();});
			addLast(first);
		}
		
		function addLast(first){
			last = '<li style="display:none">'+first+'</li>';
			$('ul#twitter_update_list').append(last)
			$('ul#twitter_update_list li:last')
			.animate({opacity: 1}, speed)
			.fadeIn('slow')
		}
	
	interval = setInterval(removeFirst, pause);
});
</script>

<?php } ?>

<?php
$right_cond = $this->params['controller']."->".$this->action;
$right_ary = array('auctions->view');
?> 
<?php if(in_array($right_cond, $right_ary)){ ?>
	<?= $javascript->link('jquery/jquery.tools.min') ?>
	<?= $javascript->link('retweet') ?>
	
	<script type="text/javascript">
	$(document).ready(function(){
		
		var first = 0;
		var speed = 700;
		var pause = 8000;
		
			function removeFirst(){
				first = $('ul#twitter_update_list li:first').html();
				$('ul#twitter_update_list li:first')
				.animate({opacity: 0}, speed)
				.fadeOut('slow', function() {$(this).remove();});
				addLast(first);
			}
			
			function addLast(first){
				last = '<li style="display:none">'+first+'</li>';
				$('ul#twitter_update_list').append(last)
				$('ul#twitter_update_list li:last')
				.animate({opacity: 1}, speed)
				.fadeIn('slow')
			}
		
		interval = setInterval(removeFirst, pause);
	});
	</script>

<?php } ?>

	<script type="text/javascript">
	<!--
	// page_top_scroll
	$(function () {
        $('#link_to_top').click(function () {
            $(this).blur();

            $('html,body').animate({ scrollTop: 0 }, 'normal');
            return false;
        });
	});
	-->
	</script>

</head>
<body>
<!--[if lte IE 6]>
<?= $javascript->link('ie6/warning'); ?><script>window.onload=function(){e("<?= $this->webroot ?>/js/ie6/")}</script>
<![endif]-->

<div id="header">
	<div align="center">
<div style="width:950px; text-align:right;">
		<div class="logo">
			<ul><li><a href="<?php echo $this->webroot; ?>" title="" class="logo"><span>&nbsp;</span></a></li></ul>
<?php if ($_SERVER['SERVER_PORT'] != '443') { ?>
<br class="clear_br">
<?php } ?>

		</div>
        
		<div class="top-menu">
        
			<?php echo $this->element('menu_top');?>
			<?php if($session->check('Auth.User')):?>
			<?php echo $this->element('status');?>
			<?php endif;?>
		</div>
		</div>
		</div>

		<div id="sub-header" class="clearfix">
			<div  align="center">
				<div style="width:950px; text-align:left;">
						<?php echo $this->element('menu_categories');?>
				</div>
			</div>
		</div>
	</div>

<!-- Head Ban Area Start -->

<h1 <?php if(in_array($this->action, $head_ban)){ ?>class="head_h1_home"<?php }else{ ?> class="head_h1"<?php } ?>>
<?php if(in_array($right_cond, $right_ary)){ ?>
<?php echo $auction['Product']['title']; ?>The world's best penny auction system. Place your bids now and win anything from one cent!
<?php }else{ ?>
Win anything from PlayStation 3s to iPhones on our website, it's easy just place a bid and watch the timer reach zero!
<?php } ?>
</h1>

<?php if(in_array($this->action, $head_ban)){ ?>
    <?php if(!$session->check('Auth.User')){ ?>
<div id="menu" style="background: #718145 url(<?= $this->webroot ?>img/ban/guest_ban/head_ban_guest_bg.png) repeat-x scroll left top;">
	<!-- Default user page start -->
	<!-- Head Ban start -->
	<div align="center">	
	<div id="menu_guest_body" align="center">
	<!-- Left Ban -->
	<div class="guest_l1">
	<img src="<?php echo $this->webroot; ?>img/ban/guest_ban/head_ban_guest_copy.png" width="465" height="189" alt="The ultimate new shopping experience" title="The ultimate new shopping experience">
	<div class="guest_l2"><a href="/page/start" style="margin-right:15px;" class="guide"><img src="<?php echo $this->webroot; ?>img/spacer.gif" width="204" height="48" alt="" title=""></a><a href="<?php echo $appConfigurations['ref_url']; ?>/users/register" class="regist"><img src="<?php echo $this->webroot; ?>img/spacer.gif" width="204" height="48" alt="" title=""></a></div>
	<div class="guest_l3"><img src="<?php echo $this->webroot; ?>img/ban/guest_ban/head_ban_guest_bottom_txt.png" width="420" height="29" alt="" title=""></div>
	</div>
	<!-- Right Ban -->
	<div class="guest_r1">
	<a href="<?php echo $appConfigurations['ref_url']; ?>/users/register"><img src="<?php echo $this->webroot; ?>img/ban/guest_ban/head_ban_guest_image.png" width="450" height="266" alt="Register" title="Register"></a></div>
	<br class="clear_br">
	</div>
	</div>
	<!-- Head Ban End -->
	<!-- Default user page end -->
</div>
    <?php }else{ ?>

    <?php } ?>


<?php } ?>
<!-- Head Ban Area End -->

<div id="container">
	<div id="maincontent" class="clearfix">

		<?php
			if($session->check('Message.flash')){
				$session->flash();
			} elseif(isset($_COOKIE['reg_complete']) && $_COOKIE['reg_complete']) {
                echo "<div id=\"flashMessage\" class=\"success\">Thank you for registering. An email has been sent with a link to complete your registration. Please check your email inbox and click the confirmation link inside. Please check your SPAM folders if the email does not arrive within five minutes.</div>";
                setcookie ("reg_complete", "", time() - 3600);
            }

			if($session->check('Message.auth')){
				$session->flash('auth');
			}
		?>
		<?php echo $content_for_layout; ?>

	</div>

	<?php echo $this->element('footer');?>
</div>

<?php echo $cakeDebug; ?>

<?php
$disp_cond = $this->params['controller']."->".$this->action;
if($disp_cond == 'pages->view'){
    if(isset($this->passedArgs[0]))
	$disp_cond .= '->'.$this->passedArgs[0];
}
$cont_ary = array('news->index','auctions->view','auctions->home',
    'pages->view->guide');
?> 
<?php if(in_array($disp_cond, $cont_ary)){ ?>
	<?php if($disp_cond=='news->index' || $disp_cond=='pages->view->guide'){ ?>
		<!-- Twitter JS start -->
		<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
		<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo Configure::read('Twitter.username'); ?>.json?callback=twitterCallback2&amp;count=5"></script>
		<!-- Twitter JS end -->
	<?php }else{ ?>
		<!-- Twitter JS start -->
		<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
		<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo Configure::read('Twitter.username'); ?>.json?callback=twitterCallback2&amp;count=10"></script>
	 
		<!-- Twitter JS end -->
	<?php } ?>
<?php } ?>



</body>
</html>