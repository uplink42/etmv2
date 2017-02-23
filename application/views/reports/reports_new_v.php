<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
        <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What is does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: A work-around for iOS meddling in triggered links. */
        *[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
        }

        /* What it does: A work-around for Gmail meddling in triggered links. */
        .x-gmail-data-detectors,
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
        }

        /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display:none !important;
        }

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* Thanks to Eric Lepetit @ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }

    </style>

    <!-- Progressive Enhancements -->
    <style>

        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

    </style>

</head>
<body width="100%" bgcolor="#222222" style="margin: 0; mso-line-height-rule: exactly;">
    <center style="width: 100%; background: #222222; text-align: left;">
        <!--
            Set the email width. Defined in two places:
            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
            2. MSO tags for Desktop Windows Outlook enforce a 600px width.
        -->
        <div style="max-width: 600px; margin: auto;" class="email-container">
            <!--[if mso]>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
            <tr>
            <td>
            <![endif]-->

           <!-- Email Header : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                <tr>
                    <td style="padding: 20px 0; text-align: center">
                        <img src="http://placehold.it/200x50" width="200" height="50" alt="alt_text" border="0" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->

            <!-- Email Body : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">

                <!-- Hero Image, Flush : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <center><h2>Daily earnings report for <?=$username?></h2></center><br>
                        <center><h2>From: <?=$date_prev?> GMT</h2></center>
                        <center><h2>To: <?=$date_now?> GMT</h2><br></center>
                        <center><p align='center'><h3>Last 24 hours snapshot: </h3></p></center>
                    </td>
                </tr>
                <!-- Hero Image, Flush : END -->

                <!-- 1 Column Text + Button : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>      
                                <th>Character</th><th>Purchases (ISK)</th><th>Revenue (ISK)</th><th>Profit (ISK)</th>
                            </tr>
                            <?php foreach($totals[0] as $key => $row) {
                                $id = key($row); ?>
                            <tr>
                                <td><img src="https://image.eveonline.com/Character/<?=$id?>_32.jpg">
                                    <?=$char_names[$key]?>
                                </td>
                                <td><?=number_format($row[$id]['buy'],2)?></td>
                                <td><?=number_format($row[$id]['sell'],2)?></td>
                                <td><?=number_format($row[$id]['profit'],2)?></td>
                            </tr>
                        <?php }?>
                            <tr>
                                <td><b>Totals:</b></td>
                                <td><?=number_format($totals[1][0]['total_buy'],2)?></td>
                                <td><?=number_format($totals[1][0]['total_sell'],2)?></td>
                                <td><?=number_format($totals[1][0]['total_profit'],2)?></td>
                            </tr>
                        </table>

                        <center><p align='center'><h3>Best Items (by profit) </h3></p></center>
                            <table>
                                <tr>
                                    <th>Item</th><th>Profit (ISK)</th><th>Quantity</th>
                                </tr>
                                    <?php if (count($best_raw) > 0) {
                                    foreach($best_raw as $row) {?>
                                        <tr>
                                            <td width="40%"><img src="<?=$row['url']?>"> <?=$row['item']?></td>
                                            <td width="30%"><?=number_format($row['profit'],2)?></td>
                                            <td><?=number_format($row['quantity'],0)?></td>
                                        </tr>      
                                    <?php }
                                    } else {
                                        echo "<tr><td colspan='3'>No results to display</td></tr>";
                                    } ?>
                            </table>
                        <br><br>
                        <center><p align='center'><h3>Best Items (by margin) </h3></p></center>
                            <table>
                                <tr>
                                    <th width="40%">Item</th><th width="30%">Margin (%)</th><th>Quantity</th>
                                </tr>
                                    <?php if (count($best_raw) > 0) {
                                    foreach($best_margin as $row) {?>
                                        <tr>
                                            <td><img src="<?=$row['url']?>"> <?=$row['item']?></td>
                                            <td><?=number_format($row['margin'],2)?></td>
                                            <td><?=number_format($row['quantity'],0)?></td>
                                        </tr>      
                                    <?php }
                                    } else {
                                        echo "<tr><td colspan='3'>No results to display</td></tr>";
                                    } ?>
                            </table>
                            <br><br>
                            <center><p align='center'><h3>Problematic Items </h3></p></center>
                                <table>
                                    <tr>
                                        <th width="40%">Item</th><th width="30%">Profit (ISK)</th><th>Quantity</th>
                                    </tr>
                                        <?php if (count($problematic) > 0) {
                                        foreach($problematic as $row) {?>
                                    <tr>
                                        <td><img src="<?=$row['url']?>"> <?=$row['item']?></td>
                                        <td><?=number_format($row['profit'],2)?></td>
                                        <td><?=number_format($row['quantity'],0)?></td>
                                    </tr>      
                                <?php }
                                } else {
                                    echo "<tr><td colspan='3'>No results to display</td></tr>";
                                } ?>

                                </table>
                                <br><br>
                                <center><p align='center'><h3>Best Customers </h3></p></center>
                                    <table>
                                        <tr>
                                            <th width="50%">Client</th><th width="50%">Profit (ISK)</th>
                                        </tr>
                                            <?php if (count($best_customers) > 0) {
                                            foreach($best_customers as $row) {?>
                                        <tr>
                                            <td><img src="<?=$row['url']?>"> <?=$row['soldTo']?></td>
                                            <td><?=number_format($row['profit'],2)?></td>
                                        </tr>      
                                    <?php }
                                    } else {
                                        echo "<tr><td colspan='3'>No results to display</td></tr>";
                                    } ?>

                                    </table>
                                    <br><br>
                                    <center><p align='center'><h3>Fastest Turnovers </h3></p></center>
                                        <table>
                                            <tr>
                                                <th width="40%">Item</th><th width="30%">Profit (ISK)</th><th>Time (H:m:s)</th>
                                            </tr>
                                                <?php if (count($fastest) > 0) {
                                                foreach($fastest as $row) {?>
                                            <tr>
                                                <td><img src="<?=$row['url']?>"> <?=$row['item']?></td>
                                                <td><?=number_format($row['total'],2)?></td>
                                                <td><?=$row['difference']?></td>
                                            </tr>      
                                        <?php }
                                        } else {
                                            echo "<tr><td colspan='3'>No results to display</td></tr>";
                                        } ?>

                                        </table>

                                    <br><br>
                                    <center><p align='center'><h3>Best ISK/h</h3></p></center>
                                        <table>
                                            <tr>
                                                <th width="40%">Item</th><th width="30%">Profit (ISK)</th><th>Quantity</th><th>ISK/h</th>
                                            </tr>
                                                <?php if (count($best_iph) > 0) {
                                                foreach($best_iph as $row) {?>
                                            <tr>
                                                <td><img src="<?=$row['url']?>"> <?=$row['item']?></td>
                                                <td><?=number_format($row['profit'],2)?></td>
                                                <td><?=number_format($row['quantity'],0)?></td>
                                                <td><?=number_format($row['iph'],0)?></td>
                                            </tr>      
                                        <?php }
                                        } else {
                                            echo "<tr><td colspan='4'>No results to display</td></tr>";
                                        } ?>

                                        </table>

                                    <br><br>
                                    <center><p align='center'><h3>Possible Blunders </h3></p></center>
                                        <table>
                                            <tr>
                                                <th width="40%">Item</th><th width="30%">Profit (ISK)</th><th>Margin (%)</th>
                                            </tr>
                                                <?php if (count($blunders) > 0) {
                                                foreach($blunders as $row) {?>
                                            <tr>
                                                <td><img src="<?=$row['url']?>"> <?=$row['item']?></td>
                                                <td><?=number_format($row['profit'],2)?></td>
                                                <td><?=number_format($row['margin'],0)?></td>
                                            </tr>      
                                        <?php }
                                        } else {
                                            echo "<tr><td colspan='3'>No results to display</td></tr>";
                                        } ?>

                                        </table>

                                    <br><br>
                                    <center><p align='center'><h3>Best Stations </h3></p></center>
                                        <table>
                                            <tr>
                                                <th width="40%">Station</th><th width="30%">Profit (ISK)</th>
                                            </tr>
                                                <?php if (count($best_stations) > 0) {
                                                foreach($best_stations as $row) {?>
                                            <tr>
                                                <td><?=$row['station']?></td>
                                                <td><?=number_format($row['profit'],2)?></td>
                                            </tr>      
                                        <?php }
                                        } else {
                                            echo "<tr><td colspan='3'>No results to display</td></tr>";
                                        } ?>

                                        </table>


                                    <br><br>
                                    <center><p align='center'><h3>Last <?=$recap_int?> days recap </h3></p></center>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Total Buy (ISK)</th>
                                                <th>Total Sell (ISK)</th>
                                                <th>Total Profit (ISK)</th>
                                                <th>Margin (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($recap['daily'] as $row) {
                                            $row['margin'] > 0 ? $class="success" : 
                                                ($row['margin'] < 0 ? $class="danger" : $class="");
                                            ?>
                                            <tr class= "<?=$class?>">
                                                <td><?=$row['date']?></td>
                                                <td><?=number_format($row['total_buy'],2)?></td>
                                                <td><?=number_format($row['total_sell'],2)?></td>
                                                <td><?=number_format($row['total_profit'],2)?></td>
                                                <td><?=number_format($row['margin'],2)?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php foreach($recap['total'] as $row) {?>
                                            <tr class="yellow">
                                                <td><b>GRAND TOTAL</b></td>
                                                <td><b><?=number_format($row['total_buy'],2)?></b></td>
                                                <td><b><?=number_format($row['total_sell'],2)?></b></td>
                                                <td><b><?=number_format($row['total_profit'],2)?></b></td>
                                                <td><b><?=number_format($row['margin'],2)?></b></td>
                                            </tr>    
                                        <?php }?>
                                        </tbody>
                                    </table>
                                    <!-- Button : Begin -->
                                   <!--  <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                                        <tr>
                                            <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                                                <a href="http://www.google.com" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                                    <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;A Button&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table> -->
                                    <!-- Button : END -->
                                </td>
                                </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text + Button : BEGIN -->

                <!-- 2 Even Columns : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%" style="padding-bottom: 40px">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:560px;">
                            <tr>
                                <td align="center" valign="top" width="50%">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size: 14px;text-align: left;">
                                        <tr>
                                            <td style="text-align: center; padding: 0 10px;">
                                                <img src="http://placehold.it/200" width="200" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 200px; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555; padding: 10px 10px 0;" class="stack-column-center">
                                                Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora per conubia nostra, per torquent inceptos&nbsp;himenaeos.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="center" valign="top" width="50%">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size: 14px;text-align: left;">
                                        <tr>
                                            <td style="text-align: center; padding: 0 10px;">
                                                <img src="http://placehold.it/200" width="200" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 200px; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555; padding: 10px 10px 0;" class="stack-column-center">
                                                Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora per conubia nostra, per torquent inceptos&nbsp;himenaeos.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Two Even Columns : END -->

                <!-- Clear Spacer : BEGIN -->
                <tr>
                    <td height="40" style="font-size: 0; line-height: 0;">
                        &nbsp;
                    </td>
                </tr>
                <!-- Clear Spacer : END -->

                <!-- 1 Column Text + Button : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                    Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent laoreet malesuada cursus. Maecenas scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut&nbsp;erat.
                                </td>
                                </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text + Button : BEGIN -->

            </table>
            <!-- Email Body : END -->

            <!-- Email Footer : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; line-height:18px; text-align: center; color: #888888;" class="x-gmail-data-detectors">
                        <webversion style="color:#cccccc; text-decoration:underline; font-weight: bold;">View as a Web Page</webversion>
                        <br><br>
                        Company Name<br>123 Fake Street, SpringField, OR, 97477 US<br>(123) 456-7890
                        <br><br>
                        <unsubscribe style="color:#888888; text-decoration:underline;">unsubscribe</unsubscribe>
                    </td>
                </tr>
            </table>
            <!-- Email Footer : END -->

            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </div>
    </center>
</body>
</html>