<?php if($session->check('Auth.User')):?>
<div class="user-status"><span class="side_5px bold" style="margin-right:8px;"><img src="<?php echo $this->webroot; ?>img/parts/guide_head_icon.png" width="21" height="21" border="0" alt="Get Started" style="margin-right:4px; margin-top:-2px;" align="middle"><a href="<?php echo $appConfigurations['nml_url']; ?>/page/start" style="color:#FFF;">Get Started</a></span><label>Hey,<span class="side_2px bold" style="margin-left:5px;"><?php echo $session->read('Auth.User.username'); ?></span></label></div>
<?php else:?>
<?php endif;?>