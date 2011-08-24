<div id="topheaderframe">
  <div class="inline">
        <ul id="tender_nav" class="headernavblue">
    <li><a href="/admin">Admin Home</a></li>
      <li class="home"><?php echo $html->link(__('View Site', true), '/');?></li>
      <li><a href="https://members.phppennyauction.com" target="_blank">Get Help</a></li>
    </ul>
    <ul id="user_nav" class="headernavblue first">
      <li class="user_nav-myprofile">Logged in as <?php echo $session->read('Auth.User.username'); ?></li>
      <li class="user_nav-logout"><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout', 'admin' => false));?></li>
    </ul> 


  </div>
  
</div><!-- /#superheader -->
