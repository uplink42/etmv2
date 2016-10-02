
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
                        <div class="panel-tools">
                            <a class="panel-toggle">
                                <i class="fa fa-chevron-up">
                                </i>
                            </a>
                            <a class="panel-close">
                                <i class="fa fa-times">
                                </i>
                            </a>
                        </div>
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
                                <h4><i class="fa fa-envelope"></i> Change E-mail</h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body email">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ch-email-pw">
                                    Current e-mail:
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="ch-email-current" value="current" type="text" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ch-email-pw">
                                    Password:
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="ch-email-pw" placeholder="type your current password here" type="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ch-email-email">
                                    New e-mail
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="ch-email-email" placeholder="type a new valid e-mail here" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default" type="submit">
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
                        <div class="panel-tools">
                        </div>
                    </div>

                        <div class="panel-body">
                        </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                        </div>
                    </div>

                        <div class="panel-body">
                        </div>

                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                        </div>
                    </div>

                        <div class="panel-body">
                        </div>

                </div>
            </div>
        </div>
    </div>
</section>
