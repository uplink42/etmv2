<section class="content">
    <div class="back-link">
    <a href="<?=base_url()?>" class="btn btn-accent">Back to main page</a>
</div>

    <div class="container lg animated slideInDown">
	<div class="view-header">
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

	<div class="panel panel-filled">
	    <div class="panel-body">
		<p></p>
		<div class="table-responsive">
		    <form name="import" method="POST" action="<?=base_url('Register/processCharacters')?>">
			<table class="table table-bordered table-stripped table-hover">
			    <thead>
				<th></th>
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
				<span>You can add up to 20 characters to your account after registering</span><br>
			    <input type="Submit" class="btn btn-accent pull-right" name="register" value="Continue">
			</div>
		    </form>
		</div>        
	    </div>
	</div>
    </div>
</section>