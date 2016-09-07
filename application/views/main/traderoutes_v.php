<script src="<?=base_url('assets/js/traderoutes-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="dropdown pull-right">
                        <button aria-expanded="true" aria-haspopup="true" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="dropdownmenu-characters" type="button">
                            Character
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php
                            for ($i = 0; $i < count($character_list); $i++) {
                                ?>
                            <li>
                                <a href="<?=base_url('TradeRoutes/index/' . $character_list['chars'][$i]) . '?aggr=0'?>">
                                    <?=$character_list['char_names'][$i]?>
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                            <li class="divider" role="separator">
                            </li>
                            <?php $url = "TradeRoutes/index/" . $character_id . "?aggr=1" ;?>
                            <li>
                                <a href="<?=base_url($url)?>">
                                    <b>
                                        All
                                    </b>
                                </a>
                            </li>
                        </ul>
                    </div>
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
            <div class="col-md-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        Trade Routes
                    </div>
                    <div class="panel-body">
                        <i class="fa fa-info"></i> Here you can set predefined starting and destination stations you use often so you can later import them into the Trade Simulator<br/>
                        <i class="fa fa-info"></i> Trade Routes are shared among all account characters
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body traderoute-creation-panel">
                        <h3 class="yellow">Create new Trade Route</h3>
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
                    <div class="panel-body traderoute-list-panel">
                        <h3 class="yellow">Existing Trade Routes</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
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
