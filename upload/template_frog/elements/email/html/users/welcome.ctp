<p><?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,</p>

<p>Thank you very much for registering with us, we hope you have lots of fun,
and find some great deals at <?php echo $appConfigurations['name'];?>.

<p>Here you'll find a quick overview of what we have to offer at
<?php echo $appConfigurations['name'];?>:</p>


<p>Want to see all the latest auctions? What's going to be your next bargain?
Start with our homepage:</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>"><?php echo $appConfigurations['url'];?></a>
</p>

<p>Want to charge up your bid account and get started bidding immediately?
Follow this link:</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/packages"><?php echo $appConfigurations['url'];?>/packages/</a>
</p>

<p>Want some Free Bids for your account? Invite your friends to join <?php echo $appConfigurations['name'];?>!</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/invites"><?php echo $appConfigurations['url'];?>/invites/</a>
</p>

<p>Questions? Here you can find answers to any questions you may have about <?php echo $appConfigurations['name'];?>:</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/contact-us"><?php echo $appConfigurations['url'];?>/contact-us/</a>
</p>


<p>Finally, you can complete your user profile by going to My <?php echo $appConfigurations['name'];?> here:</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/users/edit"><?php echo $appConfigurations['url'];?>/users-edit/</a>
</p>

<p>Here's your new login information:</p>

<p><?php __('Your login details are');?>:<br />
<?php __('Username');?>: <?php echo $data['User']['username'];?>
</p>

<p>Happy bidding at <?php echo $appConfigurations['name'];?>.com!</p>

<p>From everyone here in the <?php echo $appConfigurations['name'];?> team.</p>