<script src="<?=base_url('assets/js/stocklists-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                                <a href="<?=base_url('StockLists/index/' . $character_list['chars'][$i]) . '?aggr=0'?>">
                                    <?=$character_list['char_names'][$i]?>
                                </a>
                            </li>
                            <?php
}
?>
                            <li class="divider" role="separator">
                            </li>
                            <?php $url = "StockLists/index/" . $character_id . "?aggr=1" ;?>
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
                        <i class="pe page-header-icon pe-7s-note2">
                        </i>
                    </div>
                    <div class="header-title">
                        <h1>
                            Stock Lists
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
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        Stock Lists
                    </div>
                    <div class="panel-body">
                        <ul class="info-panel-main">
                            <li><i class="fa fa-info yellow"></i> Here you can manage several items in bulk to simultaneaously check their prices with the Trade Simulator</li>
                            <li><i class="fa fa-info yellow"></i> Stock lists are accessible to every character in your account</li>
                            <li><i class="fa fa-info yellow"></i> You can store up to 100 items per list</li>
                        </ul>
                    </div>
                </div> 
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body traderoute-creation-panel">
                        <h3 class="yellow">Create new Stock List</h3>
                        <form class="form-horizontal" data-url=<?=base_url()?>>
                            <div class="form-group"><label for="list-name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10"><input type="text" class="form-control list-name" id="list-name" placeholder="Type your new list name here"></div>
                            </div>
                            <button type="submit" class="btn btn-default submit-lis" name="submit-list">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body traderoute-list-panel">
                        <h3 class="yellow">Select Stock List</h3>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
