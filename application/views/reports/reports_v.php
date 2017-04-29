<head>
    <style>
		 * { 
			margin: 0; 
			padding: 0; 
		}
		body { 
			font: 14px/1.4 Georgia, Serif;
            max-width: 1000px;
            margin: 0 auto; 
		}
		#page-wrap {
			margin: 50px;
		}
		p {
			margin: 20px 0; 
		}

		table { 
			width: 100%; 
			border-collapse: collapse; 
		}
		/* Zebra striping */
		tr:nth-of-type(odd) { 
			background: #eee; 
		}
		th { 
			background: #333; 
			color: white; 
			font-weight: bold; 
		}
		td, th { 
			padding: 6px; 
			border: 1px solid #ccc; 
			text-align: left; 
		}
    </style>        
</head>
<body>
    <center><h2><?=$period?> earnings report for <?=$username?></h2></center><br>
    
    <center><h2>From: <?=$date_prev?> GMT</h2></center>
    <center><h2>To: <?=$date_now?> GMT</h2><br></center>
    <center><p align='center'><h3>Snapshot: </h3></p></center>
    
    <table>
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
    
    <br><br>
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
        <br>  
        <?php $this->load->view('reports/info_v');?>

        <br><br>
            <h4><strong class="c-white">Latest News</strong></h4>
            <?php foreach($cl_recent as $row) {
                echo "<b>" . $row->date . "</b> - ";
                echo $row->content . "<br>";
            }?>
</body>