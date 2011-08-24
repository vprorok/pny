<?php if(!empty($histories)):?>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" >
        <tr>
            <th><?php __('TIME');?></th>
            <th><?php __('BIDDER');?></th>
            <th><?php __('TYPE');?></th>
        </tr>
        <?php foreach($histories as $bid):?>
        <tr>
            <td><?php echo $time->niceShort($bid['Bid']['created']);?></td>
            <td><?php echo $bid['User']['username'];?></td>
            <td><?php echo $bid['Bid']['description'];?></td>
        </tr>
        <?php endforeach;?>
    </table>
<?php else:?>
    <p><?php __('No bids have been placed yet.');?></p>
<?php endif;?>
