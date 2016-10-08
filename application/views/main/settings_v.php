<script src="<?=base_url('dist/js/apps/settings-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <?php $this->load->view('common/selector_v');?>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-tools">
                        </i>
                    </div>
                    <div class="header-title">
                        <h1>
                            Account Settings
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-heading">
                        Account Settings
                    </div>
                    <div class="panel-body">
                        <ul class="info-panel-main">
                            <li>
                                <i class="fa fa-info yellow">
                                </i>
                                Here you can configure several aspects in your ETM account.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h5><i class="fa fa-envelope"></i> Change E-mail</h5>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body email">
                        <form class="form-horizontal change-email" method="POST">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ch-email-pw">
                                    Current e-mail:
                                </label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="ch-email-current" type="text" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ch-email-pw">
                                    Password:
                                </label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="ch-email-pw" placeholder="type your current password here" type="password" name="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ch-email-email">
                                    New e-mail:
                                </label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="ch-email-new" placeholder="type a new valid e-mail here" type="text" name="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default btn-change-email" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                   
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h5><i class="fa fa-key"></i> Change Password</h5>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body password">
                        <form class="form-horizontal change-password" method="POST" autocomplete="off">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ch-pw-old">
                                    Current Password:
                                </label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="ch-pw-old" placeholder="type your current password here" type="password" name="password-old">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ch-pw-new1">
                                    New Password (minimum 6 characters):
                                </label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="ch-pw-new1" placeholder="type your new password here" type="password" name="password-new1">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ch-pw-new2">
                                    New Password (repeat):
                                </label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="ch-pw-new2" placeholder="type your new password here" type="password" name="password-new2">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default btn-change-pw" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h5><i class="fa fa-file-excel-o"></i> Automated Reports</h5>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body reports">
                        Automated Reports include
                            <ul>
                                <li>last day, week or month trading snapshots</li>
                                <li>a list of best items, customers and stations for the given period</li>
                            </ul>
                        <form class="form-horizontal change-reports" method="POST">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ch-email-pw">
                                    Reports:
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control report-options" name="reports">
                                        <option value="none">None</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default btn-change-reports" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
        
                </div>
            </div>
        </div>
    </div>
</section>
