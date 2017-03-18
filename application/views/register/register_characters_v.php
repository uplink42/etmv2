<section class="content">
    <div class="back-link">
    <a href="<?=base_url()?>" class="btn btn-accent">Back to main page</a>
</div>

    <div class="container lg animated slideInDown">
		<div class="view-header col-sm-12 col-xs-12">
		    <div class="header-icon">
			<i class="pe page-header-icon pe-7s-add-user"></i>
		    </div>
		    <div class="header-title">
    			<h3>Register</h3>
    			<small>
    			    Select the characters you wish to import to your Eve Trade Master account.
    			</small>
		    </div>
		</div>

        <form name="import" method="POST" action="<?=base_url('Register/processCharacters')?>">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
            		<div class="panel panel-filled">
            		    <div class="panel-body">
            				<h4>Characters to import</h4>
            				<div class="table-responsive">
        						<table class="table table-bordered table-stripped table-hover">
        						    <thead>
        								<th class="portrait"></th>
        								<th>ID</th>
        								<th>Name</th>
        								<th>Import</th>
        						    </thead>
        						    <tbody>
        						    <?php 
        							$count = 0;
        							foreach ($characters as $row) {
        							    $id = $row[1]['id'];
        							    $name = $row[0]['name'];
        							    $count++;
        						    ?>
        							<tr><td><img src='https://image.eveonline.com/Character/<?=$id?>_32.jpg'></td>
        							    <td> <?=$id ?></td>
        							    <td> <?=$name ?></td>
        							    <td><input type = 'checkbox' name = 'char<?=$count?>' value = '<?=$id?>'></td></tr>
        							<?php
        							}
        						    ?>
        						    </tbody>
        						</table>
        						<input type="hidden" name="username" value="<?=set_value('username')?>">
        						<input type="hidden" name="password" value="<?=set_value('password')?>">
        						<input type="hidden" name="apikey" value="<?=set_value('apikey')?>">
        						<input type="hidden" name="vcode" value="<?=set_value('vcode')?>">
        						<input type="hidden" name="email" value="<?=set_value('email')?>">
        						<input type="hidden" name="reports" value="<?=set_value('reports')?>">

        						<div class="text-center submit-register">
        							<i class="fa fa-info"></i> <span>You can add up to 20 characters to your account after registering</span><br>
        						</div>
            				</div>        
            		    </div>
            		</div>
                </div>

                <div class="col-sm-6 col-xs-12">
            		<div class="panel panel-filled">
            		    <div class="panel-body">
            		    	<h4>General Settings</h4>
                            <div class="row form-group">
                                <div class="col-md-6 col-xs-6">
                                    Default buy transactions behaviour:
                                </div>
                                <div class="col-md-6 col-xs-6 text-right">
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="default-buy" value="1" checked> Buy order
                                    </div>
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="default-buy" value="0"> Sell order
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12 help-block">
                                    <i class="fa fa-info"></i> By default, ETM will assume your purchases come from buy orders and calculates taxes accordingly.
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-xs-6">
                                    Default sell transactions behaviour:
                                </div>
                                <div class="col-md-6 col-xs-6 text-right">
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="default-sell" value="0"> Buy order
                                    </div>
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="default-sell" value="1" checked> Sell order
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12 help-block">
                                    <i class="fa fa-info"></i> By default, ETM will assume your sales go to sell orders and calculates taxes accordingly.
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-xs-6">
                                    Cross-character profit tracking:
                                </div>
                                <div class="col-md-6 col-xs-6 text-right">
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="x-character" value="1" checked> Enabled
                                    </div>
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="x-character" value="0"> Disabled
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12 help-block">
                                    <i class="fa fa-info"></i> By default, ETM will calculate profits even when purchases and sales come from different characters. You can disable this behaviour and never match transactions between different characters.
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-xs-6">
                                    Set all Citadel taxes to zero:
                                </div>
                                <div class="col-md-6 col-xs-6 text-right">
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="null-citadel-tax" value="1"> Yes
                                    </div>
                                    <div class="col-md-6" style="float:right;">
                                        <input type="radio" name="null-citadel-tax" value="0" checked> No
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12 help-block">
                                    <i class="fa fa-info"></i> When enabled, ETM will set your broker fees to be 0% when dealing from citadels.
                                </div>
                            </div>
                            <input type="Submit" class="btn btn-accent pull-right" name="register" value="Continue">	
            		    </div>
            		</div>
                </div>
            </div>
        </form>
    </div>
</section>