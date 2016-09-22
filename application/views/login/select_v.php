<section class="content">
    <div class="back-link">
    <a href="<?=base_url('index.php/Main')?>" class="btn btn-accent">Back to main page</a>
</div>

    <div class="container lg animated slideInDown tabs-container">
        
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
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> News</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">FAQ</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false">Changelog</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <strong class="c-white">Latest News</strong>
                        <?php $this->load->view('login/changelog_recent_v');?>
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <strong class="c-white">Frequently Asked Questions</strong>
                        <p><b>Q: Is this service free?</b></p>
                                A: Yes, and there are no plans for charging players or displaying ads. If you would like to support this work or server costs and encourage further development feel free to Donate (ISK or $) trough the link in the footer. Disabling your ad-block is also a great help!
                        <br><br>
                        <p><b>Q: Help! The website doesn't load!</b></p>
                        A: This is a rare occurence but the most likely reason for this is because your supplied API key has been either revoked or expired. 
                        Go <a href='https://community.eveonline.com/support/api-key/' target='blank'>here</a>  and make sure your API key is up-to date or contains the correct characters/permissions and change it accordingly. 
                        The accepted permissions are displayed <a href='permissions.png' target='blank'>here</a>
                        Then try logging in after a few minutes. To avoid issues like this in future I recommend you to just set the key as "no expiry". If nothing works just delete the key and wait a few minutes before trying to login again.
                        If you still can't login drop me a mail at etmdevelopment42 at gmail.com. I'm usually quick at fixing things like this.
                        <br><br>
                        <p><b>Q: Your website isn't pulling my data!</b></p>
                        A: As long as you're able to login and select a character it should be. Remember that Eve Online's API only updates data after certain intervals. These are:
                        <ul>
                            <li>Account Balance: every 15 minutes</li>
                            <li>API key data: every 5 minutes</li>
                            <li>Asset list: every 2 hours</li>
                            <li>Contract list: every 60 minutes</li>
                            <li>Character skills: every 60 minutes</li>
                            <li>Market Orders: every 60 minutes</li>
                            <li>Character standings: every 3 hours</li>
                            <li>Wallet transactions: every 30 minutes</li>
                            <li>CREST Market data: every 5 minutes</li>
                        </ul>
                        If you're absolutely sure your data isn't being pulled drop me a mail at etmdevelopment42 at gmail.com
                        <br><br>
                        <p><b>Q: How do you calculate profits?</b></p>
                        A: Profits are calculated using a <a href='http://www.accountingtools.com/fifo-method' target='blank'>First-in-First-Out </a>method. This means the first items you buy are assumed to be the first items you sell.
                        It's currently impossible to keep a track of each individual item with the current Eve API so this might lead to some inconsistencies in results if you buy items for other purposes on your trading characters.
                        If you want clean results I recommend you to not purchase items you don't intend to re-sell with the characters you listed in ETM.
                        <br><br>
                        <p><b>Q: Can I have the source code?</b></p>
                        A: ETM is not currently open source. Things might change in future, however.
                        <br><br>
                        <p><b>Q: Will there be new features after the Beta is over?</b></p>
                        A: Yes. Things like corp API key support are definetly in the list. However I don't have as much time to devote to side projects and can't guarantee any timelines.
                    </div>
                </div>
                <div id="tab-3" class="tab-pane">
                    <div class="panel-body">
                        <strong class="c-white">Changelog</strong>
                        <?php $this->load->view('login/changelog_v');?>
                    </div>
                </div>
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
            				    <td class="text-right"><?=number_format($balance)?></td>
            				    <td class="text-right"><?=number_format($networth)?></td>
            				    <td class="text-right"><?=number_format($escrow)?></td>
            				    <td class="text-right"><?=number_format($sell)?></td>
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
            				    <td class="text-right"><strong><?=number_format($networth_total)?></strong></td>
            				    <td class="text-right"><strong><?=number_format($escrow_total)?></strong></td>
            				    <td class="text-right"><strong><?=number_format($sell_total)?></strong></td>
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

