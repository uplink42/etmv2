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
for ($i = 0; $i < count($character_list['chars']); $i++) {
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
                    <div class="panel-body">
                        <i class="fa fa-info"></i> Here you can manage several items in bulk to simultaneaously check their prices with the Trade Simulator <br />
                        <i class="fa fa-info"></i> Stock Lists are accessible to every character in your account <br />
                        <i class="fa fa-info"></i> You can store up to 100 items per list
                    </div>
                </div> 
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body stocklist-creation-panel">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h4><i class="fa fa-plane"></i> Create new Stock List</h4>
                            </div>
                        </div>
                        <form class="form-horizontal" data-url=<?=base_url()?>>
                            <div class="form-group"><label for="list-name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10"><input type="text" class="form-control list-name" id="list-name" name="list-name" placeholder="Type your new list name here" autofocus></div>
                            </div>
                            <button type="submit" class="btn btn-default submit-list" name="submit-list">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body traderoute-list-panel">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h4><i class="fa fa-list-ol"></i> Select Stock List</h4>
                            </div>
                        </div>
                        <form>
                            <select class="form-control dropdown-list">
                                <option class="default" value="0">Select a list</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row add-list-item">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body stocklist-panel">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h4><i class="fa fa-arrow-up"></i> Add Item to Stock List <span class="yellow contents"></span></h4>
                            </div>
                        </div>
                        <form class="add-item">
                            <div class="form-group">
                                <input type="text" class="form-control" id="item-name" name="item-name" placeholder="Start typing an item name here and select an option below">
                                <input type="hidden" name="list-id" id="list-id">
                            </div>
                            <button type="submit" class="btn btn-submit btn-success btn-add-item">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row stocklist-content">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body stocklist-panel">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h4><i class="fa fa-th-list"></i> <span class="yellow contents"></span> Contents:</h4>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-stripped table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Volume</th>
                                        <th>Avg Est. Price (ISK)</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody class="table-items">
                                    
                                </tbody>
                            </table>
                            <button class="btn btn-danger btn-delete-list pull-right" data-toggle='modal' data-target='#delete'>Delete Stock List</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" data-url="<?=base_url('StockLists/deleteList')?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title">Delete Stock List</h4>
                    </div>
                    <div class="modal-body">
                        <h4 class="m-t-none"><i class="fa fa-warning"></i> Please make sure you know what you're doing!</h4>
                        <p>This operation will remove the current stock List and all belonging items in it.</p>
                        <p>Deleting this list is <span class="yellow">irreversible</span>. Are you sure you want to continue?</p>
                        <div class="text-center">
                            <button type="button" class="btn btn-default modal-close" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-accent btn-delete-list-confirm" data-url="0">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
