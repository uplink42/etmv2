<section class="content">
    <div class="back-link">
    <a href="<?=base_url('index.php/Main')?>" class="btn btn-accent">Back to main page</a>
</div>

    <div class="container lg animated slideInDown">
	<div class="view-header">
	    <div class="header-icon">
		<i class="pe page-header-icon pe-7s-smile"></i>
	    </div>
	    <div class="header-title">
		<h3>Character selection</h3>
		<small>
		    Select one of the following character portraits to continue
		</small>
	    </div>
	</div>

	<div class="panel panel-filled">
	    <div class="panel-body">
		<p></p>
		<div class="table-responsive">
		    <?php
		    if(isset($table)) {
		?>
		    <table class="table table-responsive table-bordered table-stripped table-hover">
			<thead>
			    <tr>
				<th></th>
				<th>Name</th>
				<th>Wallet Balance</th>
				<th>Assets Value</th>
				<th>Escrow</th>
				<th>Sell Orders</th>
			    </tr>
			</thead>
			<tbody>    
		    <?php
			    foreach($table[0]['character'] as $char) {
				
				$id = $char['id'];
				$name = $char['name'];
				$balance = $char['balance'];
				$networth = $char['networth'];
				$escrow = $char['escrow'];
				$sell = $char['sell'];
				?>
				<tr>
				    <td><a href='<?= base_url('Dashboard/index/'.$id)?>'><img src='https://image.eveonline.com/Character/<?=$id?>_32.jpg' alt='character portrait'></a></td>
				    <td><?=$name?></td>
				    <td class="text-right"><?=number_format($balance,2)?></td>
				    <td class="text-right"><?=number_format($networth,2)?></td>
				    <td class="text-right"><?=number_format($escrow,2)?></td>
				    <td class="text-right"><?=number_format($sell,2)?></td>
				</tr>
				<?php
			    }
			    
			    $balance_total = $table[0]['total']['balance_total'];
			    $networth_total = $table[0]['total']['networth_total'];
			    $escrow_total = $table[0]['total']['escrow_total'];
			    $sell_total = $table[0]['total']['sell_total'];
			    ?>
				<tr>
				    <td></td>
				    <td><strong>Total</strong></td>
				    <td class="text-right"><strong><?=number_format($balance_total)?></strong></td>
				    <td class="text-right"><strong><?=number_format($networth_total,2)?></strong></td>
				    <td class="text-right"><strong><?=number_format($escrow_total,2)?></strong></td>
				    <td class="text-right"><strong><?=number_format($sell_total,2)?></strong></td>
				</tr>
				
				<tr>
				    <td></td>
				    <td></td>
				    <td></td>
				    <td></td>
				    <td class="text-right yellow"><strong>GRAND TOTAL</strong></td>
				    <td class="text-right yellow"><strong><?=number_format($table[0]['grand_total'])?></strong></td>
				</tr>
				<?php
			}
		    ?>	
			</tbody>
		    </table>
		</div>        
	    </div>
	</div>
    </div>
</section>

