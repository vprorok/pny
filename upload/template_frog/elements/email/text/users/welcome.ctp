<?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,

Thank you very much for registering with us, we hope you have lots of fun,
and find some great deals at <?php echo $appConfigurations['name'];?>.

Here you'll find a quick overview of what we have to offer at
<?php echo $appConfigurations['name'];?>:


Want to see all the latest auctions? What's going to be your next bargain?
Start with our homepage:


<?php echo $appConfigurations['url'];?>


Want to charge up your bid account and get started bidding immediately?
Follow this link:


<?php echo $appConfigurations['url'];?>/packages/


Want some Free Bids for your account? Invite your friends to join <?php echo $appConfigurations['name'];?>!


<?php echo $appConfigurations['url'];?>/invites/


Questions? Here you can find answers to any questions you may have about <?php echo $appConfigurations['name'];?>:


<?php echo $appConfigurations['url'];?>/contact-us/



Finally, you can complete your user profile by going to My <?php echo $appConfigurations['name'];?> here:


<?php echo $appConfigurations['url'];?>/users-edit/


Here's your new login information:

<?php __('Your login details are');?>:
<?php __('Username');?>: <?php echo $data['User']['username'];?>


Happy bidding at <?php echo $appConfigurations['name'];?>.com!

From everyone here in the <?php echo $appConfigurations['name'];?> team.