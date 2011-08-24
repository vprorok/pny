<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Invite My Friends');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				$html->addCrumb('Referrals', '/referrals');
				echo $this->element('crumb_user');
				?>

				<div class="invites form">
					<h3><?php __('Fill your friends email addresses, separate email by comma (,)');?></h3>
					<div>example: friend1@mail.com, friend2@mail.com, friend3@mail.com</div>
					<?php echo $form->create('Invite', array('action' => 'index')); ?>
					<?php echo $form->textarea('friends_email', array('id'=>'recipient_list','div' => false, 'label' => false,'cols'=> 50,'rows'=>10)); ?>
					<div>
						<?php echo $form->label(__('Invite Message', true));?><br/>
						<?php echo $form->textarea('message', array('div' => false, 'label' => false,'cols'=> 50,'rows'=>10)); ?>
					</div>
					<?php echo $form->end(__('Invite Now', true)); ?>

					<div id="importer">
						<p>Import your contacts from webmail services</p>
						<?php echo $html->link($html->image('aol.gif', array('border' => 0)), array('action' => 'import', 'aol'), array('class' => 'importAction', 'title' => 'aol.com'), null, false);?>
						<?php echo $html->link($html->image('gmail.gif', array('border' => 0)), array('action' => 'import', 'gmail'), array('class' => 'importAction', 'title' => 'gmail.com'), null, false);?>
						<?php echo $html->link($html->image('hotmail.gif', array('border' => 0)), array('action' => 'import', 'hotmail'), array('class' => 'importAction', 'title' => 'hotmail.com'), null, false);?>
						<?php echo $html->link($html->image('msn_mail.gif', array('border' => 0)), array('action' => 'import', 'msn_mail'), array('class' => 'importAction', 'title' => 'msn.com'), null, false);?>
						<?php echo $html->link($html->image('yahoo.gif', array('border' => 0)), array('action' => 'import', 'yahoo'), array('class' => 'importAction', 'title' => 'yahoo.com'), null, false);?>
					</div>
					<div id="importer_form" style="display: none">
						<fieldset>
						<?php echo $form->create('User', array('action' => 'import'));?>
							<?php echo $form->input('login', array('class' => 'importerLogin', 'after' => '@<span id="importer_service">&nbsp;</span>'));?>
							<?php echo $form->input('password', array('class' => 'importerPassword'));?>
							<?php echo $form->submit('Import', array('class' => 'importerSubmit'));?>
						<?php echo $form->end();?>
						</fieldset>
					</div>
					<div id="importer_inprogress" style="display: none">
						<?php echo $html->image('spinner2.gif');?> Please wait while we import your contacts...
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>
<?php echo $javascript->link('importer');?>