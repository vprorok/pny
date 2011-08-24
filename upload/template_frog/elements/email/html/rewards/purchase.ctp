<p><?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?>,</p>

<p><?php __('A user has just redeemed their rewards. Below are the details:');?></p>

<p>
    <h1><?php __('User Details');?></h1>
    <table class="results" cellpadding="0" cellspacing="0">
    <tr>
        <td><strong><?php __('Email Address');?></strong>:</td>
        <td><?php echo $data['User']['email']; ?></td>
    </tr>
    
    <tr>
        <td><strong><?php __('Username');?></strong>:</td>
        <td><?php echo $data['User']['username']; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Name');?></strong>:</td>
        <td><?php echo $data['Address']['name']; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Address');?></strong>:</td>
        <td><?php echo $data['Address']['address_1']; ?><?php if(!empty($data['Address']['address_2'])) : ?>, <?php echo $data['Address']['address_2']; ?><?php endif; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Suburb / Town');?></strong>:</td>
        <td><?php if(!empty($data['Address']['suburb'])) : ?><?php echo $data['Address']['suburb']; ?><?php else: ?>n/a<?php endif; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('City / State / County');?></strong>:</td>
        <td><?php echo $data['Address']['city']; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Postcode');?></strong>:</td>
        <td><?php echo $data['Address']['postcode']; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Country');?></strong>:</td>
        <td><?php echo $data['Country']['name']; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Phone Number');?></strong>:</td>
        <td><?php if(!empty($data['Address']['phone'])) : ?><?php echo $data['Address']['phone']; ?><?php else: ?>n/a<?php endif; ?></td>
    </tr>
    </table>
</p>

<p>
    <h1><?php __('Reward');?></h1>
    <table class="results" cellpadding="0" cellspacing="0">
    <tr>
        <td><strong><?php __('Title');?></strong>:</td>
        <td><?php echo $data['Reward']['title']; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Description');?></strong>:</td>
        <td><?php echo $data['Reward']['description']; ?></td>
    </tr>
    <tr>
        <td><strong><?php __('Points');?></strong>:</td>
        <td><?php echo $data['Reward']['points']; ?></td>
    </tr>
    </table>
</p>