<section class="content">
    <div class="back-link">
	<a href="<?=base_url('index.php/Main')?>" class="btn btn-accent">Back to main page</a>
    </div>
    <div class="container-center animated slideInDown">
	<div class="view-header">
	    <div class="header-icon">
		<i class="pe page-header-icon pe-7s-unlock"></i>
	    </div>
	    <div class="header-title">
		<h3>Login</h3>
		<small>
		    Please enter your credentials to login.
		</small>
	    </div>
	</div>

	<div class="panel panel-filled">
	    <div class="panel-body">
		<form action="<?=base_url('Login/process')?>" method="POST" id="loginForm" name="login">
		    <div class="form-group">
			<label class="control-label" for="username">Username</label>
			<input type="text" title="Please enter you username" required="required" value="" name="username" id="username" class="form-control">
			<span class="help-block small">Your unique username to Eve Trade Master</span>
		    </div>
		    <div class="form-group">
			<label class="control-label" for="password">Password</label>
			<input type="password" title="Please enter your password"  required="required" value="" name="password" id="password" class="form-control">
			<span class="help-block small">Your strong password</span>
		    </div>
		    <div class="text-center">
			<input type="Submit" class="btn btn-default" name="Login" value="Login">
			<a class="btn btn-default" href="<?=base_url('main/register')?>">Register</a>
		    </div>
		    <span class="help-block small text-center help-forgot">Forgot username | Forgot password</span>
		</form>
	    </div>
	</div>

    </div>
</section>