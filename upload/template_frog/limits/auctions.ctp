<div class="box clearfix">
        <div class="f-top clearfix"><h2><?php __('My Auction Limits'); ?></h2></div>
        <div class="f-repeat clearfix">
                <div class="content">
                        <div id="leftcol">
                                <?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
                        </div>
                        <div id="rightcol">
                                <?php
                                $html->addCrumb(__('My Auction Limits', true), '/limits/auctions');
                                echo $this->element('crumb_user');
                                ?>
                        
                                <h1><?php __('My Auction Limits');?></h1>
                        
                                <table class="results" cellpadding="0" cellspacing="0">
                                        <tr>
                                                <th>&nbsp;</th>
                                                <th><?php __('Status');?></th>
                                                <th><?php __('Auction Title');?></th>
                                                <th><?php __('Information');?></th>
                                                <th><?php __('Group');?></th>
                                        </tr>
                                <?php $i = 0; ?>
                                <?php if(!empty($auctions)): ?>
                                        <?php
                                        foreach ($auctions as $auction):
                                                $class = null;
                                                if ($i++ % 2 == 0) {
                                                        $class = ' class="altrow"';
                                                }
                                        ?>
                                                <tr<?php echo $class;?>>
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php if($auction['Auction']['winner_id'] > 0) : ?><?php __('You Won!');?><?php else : ?><?php __('You Lead!');?><?php endif; ?></td>
                                                        <td><?php echo $html->link($auction['Product']['title'], array('controller' => 'auctions', 'action' => 'view', $auction['Auction']['id']));?></td>
                                                        <td>
                                                        <?php if($auction['Auction']['winner_id'] > 0) : ?>
                                                                <strong><?php __('You have won this auction!');?></strong>
                                                                <br />
                                                                <?php __('This auction slot will become available after the auction has been closed for');?> <?php echo $appConfigurations['limits']['expiry']; ?> <?php __('days.');?>
                                                        <?php else : ?>
                                                                <strong><?php __('You are leading the bidding!');?></strong>
                                                                <br />
                                                                <?php __('This auction slot will become available when your last bid has been outbid by another bidder.');?>
                                                        <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $auction['Product']['Limit']['name']; ?></td>
                                                </tr>
                                        <?php endforeach; ?>
                                <?php endif;?>
                        
                                <?php
                                $n = $total + 1;
                                        while ($n <= $appConfigurations['limits']['limit']) { ?>
                                                <?php
                                                $class = null;
                                                if ($i++ % 2 == 0) {
                                                        $class = ' class="altrow"';
                                                }
                                                ?>
                        
                                                <tr<?php echo $class;?>>
                                                        <td><?php echo $i; ?></td>
                                                        <td>&nbsp;</td>
                                                        <td><?php __('Available');?></td>
                                                        <td>-</td>
                                                        <td>&nbsp;</td>
                                                </tr>
                                                <?php $n ++; ?>
                                        <?php } ?>
                        
                                </table>
                        
                        </div>
                </div>
        </div>
        <div class="f-bottom clearfix"> &nbsp; </div>
</div>