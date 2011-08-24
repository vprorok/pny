<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php __('Newsletter');?></title>
    <style type="text/css">
        a {
            color: #9e4a2e;
        }

        #header{
            background: #fff url(<?php echo $appConfigurations['url'];?>/img/newsletter-header.gif) no-repeat;
            padding: 4px 10px;
        }
    </style>
</head>

<body>
    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #e2dccf;">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td id="header"><span style="font: bold 18px Arial, Helvetica, sans-serif; color: #8a7440;"><?php echo $appConfigurations['name'];?> Newsletter</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $content_for_layout;?>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #666; padding: 16px 10px 6px 10px;">
                            <?php echo $appConfigurations['name'];?>. &copy; 2008 All right reserved. <br />
                            <a href="<?php echo $appConfigurations['url'];?>/newsletters/unsubscribe/<?php echo $recipient;?>"><?php __('Unsubscribe');?></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
