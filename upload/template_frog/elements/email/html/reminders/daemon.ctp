<p>Hi <?php echo $data['User']['first_name'];?> <?php echo $data['User']['last_name'];?>,</p>

<p>We just want to remind you that the auction "<?php echo $data['Auction']['title'];?>"
has been started. You can view the auction by visitting the link below:</p>

<p><?php echo Configure::read('App.url').'/auctions/view/'.$data['Auction']['id'];?></p>

<p>Thank You<br/>
<?php echo Configure::read('App.name');?></p>