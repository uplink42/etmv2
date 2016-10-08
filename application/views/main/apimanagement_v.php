<script src="<?=base_url('dist/js/apps/apikeymanagement-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <?php $this->load->view('common/selector_v');?>
                    <div class="header-icon">
                        <i class="pe page-header-icon fa fa-users">
                        </i>
                    </div>
                    <div class="header-title">
                        <h1>API Key Management</h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> Here you can add or remove characters to your ETM account<br/>
                        <i class="fa fa-info yellow"></i> Adding more characters means longer update times<br/>
                        <i class="fa fa-info yellow"></i> You can have up to 20 characters per account
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
                                <h5><i class="fa fa-plus"></i> Add API Key/character</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-body stocklist-panel">
                            <div class="panel panel-filled panel-c-success panel-collapse">
                                <div class="panel-heading">
                                    <h5><i class="fa fa-minus"></i> Remove API Key/character</h5>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-stripped table-hover">
                                <thead>
                                    <tr>
                                        <th>Character</th>
                                        <th>Key</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody class="table-chars">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" data-url="<?=base_url('ApiKeyManagement/removeCharacter')?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title">Remove character</h4>
                        </div>
                        <div class="modal-body">
                            <h4 class="m-t-none"><i class="fa fa-warning"></i> Please make sure you know what you're doing!</h4>
                            <p>This operation will remove this character from your Eve Trade Master account. You will no longer have access to any of its data and profits made in future will no longer take this character into account, however this action is not retroactive (i.e profits already made with this character won't be re-calculated). You can always add this character again in future.</p>
                            <p>Are you sure you want to continue? You will be prompted to login again to continue.</p>
                            <div class="text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-accent btn-delete-confirm" data-url="0">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
