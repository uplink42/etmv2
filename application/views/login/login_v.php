<script src="<?=base_url('assets/js/login.js')?>">
</script>
<section class="content">
    <div class="back-link">
        <a class="btn btn-accent" href="<?=base_url('index.php/Main')?>">
            Back to main page
        </a>
    </div>
    <div class="container-center animated slideInDown">
        <div class="view-header">
            <div class="header-icon">
                <i class="pe page-header-icon pe-7s-unlock">
                </i>
            </div>
            <div class="header-title">
                <h3>
                    Login
                </h3>
                <small>
                    Please enter your credentials to login.
                </small>
            </div>
        </div>
        <div class="panel panel-filled">
            <div class="panel-body panel-login">
                <form action="<?=base_url('Login/process')?>" id="loginForm" method="POST" name="login">
                    <div class="form-group">
                        <label class="control-label" for="username">
                            Username
                        </label>
                        <input class="form-control" id="username" name="username" required="" title="Please enter you username" type="text">
                            <span class="help-block small">
                                Your unique username to Eve Trade Master
                            </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="password">
                            Password
                        </label>
                        <input class="form-control" id="password" name="password" required="" title="Please enter your password" type="password">
                            <span class="help-block small">
                                Your strong password
                            </span>
                    </div>
                    <div class="text-center">
                        <input class="btn btn-default" id="login-btn" name="Login" type="Submit" value="Login">
                            <a class="btn btn-default" href="<?=base_url('main/register')?>">
                                Register
                            </a>
                    </div>
                    <span class="help-block small text-center help-forgot">
                        Forgot username | Forgot password
                    </span>
                </form>
            </div>
            <div class="panel-body panel-loading">
                Logging in... updating data
                <div class="windows8">
                    <br>
                    <div class="wBall" id="wBall_1">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_2">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_3">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_4">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_5">
                        <div class="wInnerBall">
                        </div>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
    </div>
</section>
