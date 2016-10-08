<script src="<?=base_url('dist/js/apps/traderoutes-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <?php $this->load->view('common/selector_v');?>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-plane">
                        </i>
                    </div>
                    <div class="header-title">
                        <h1>Trade Routes</h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> Here you can set predefined starting and destination stations you use often so you can later import them into the Trade Simulator<br/>
                        <i class="fa fa-info yellow"></i> Trade Routes are shared among all account characters
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
                                <h5><i class="fa fa-plane"></i> Create new Trade Route</h5>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body traderoute-creation-panel">
                        <form class="form-horizontal" data-url=<?=base_url()?>>
                            <div class="form-group"><label for="origin-station" class="col-sm-2 control-label">Origin</label>
                                <div class="col-sm-10"><input type="text" class="form-control origin-station" id="origin-station" placeholder="Begin typing and select one of the highlighted stations"></div>
                            </div>
                            <div class="form-group"><label for="destination-station" class="col-sm-2 control-label">Destination</label>
                                <div class="col-sm-10"><input type="text" class="form-control destination-station" id= "destination-station" placeholder="Begin typing and select one of the highlighted stations"></div>
                            </div>  
                            <input type="hidden" class="origin" name="origin">
                            <input type="hidden" class="destination" name="destination">
                            <button type="submit" class="btn btn-default submit-traderoute" name="submit-traderoute">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h5><i class="fa fa-server"></i> Existing Trade Routes</h5>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body traderoute-list-panel"> 
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-items">
                                <thead>
                                    <tr>
                                        <th>Origin</th>
                                        <th>Destination</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
