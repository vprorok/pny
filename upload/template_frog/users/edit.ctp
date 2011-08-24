<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Edit Account');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				$html->addCrumb(__('Edit Profile', true), '/users/edit');
				echo $this->element('crumb_user');
				?>
				
				<?php echo $form->create('User', array('url'=>'/users/edit'));?>
				<fieldset>
					<legend><?php __('Edit Profile');?></legend>
				
					<?php
						echo $form->input('username', array('label' => __('Username *', true)));
						echo $form->input('first_name', array('label' => __('First Name *', true)));
						echo $form->input('last_name', array('label' => __('Last Name *', true)));
						echo $form->input('email', array('label' => __('Email *', true)));
						echo $form->input('date_of_birth', array('label' => __('Date of Birth', true),  'minYear' => $appConfigurations['Dob']['year_min'], 'maxYear' => $appConfigurations['Dob']['year_max']));
						echo $form->input('gender_id', array('label' => __('Gender *', true)));
						echo $form->input('newsletter', array('label' => __('Sign up for the newsletter?', true)));
						echo $form->end(__('Save Changes', true));
					?>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>