<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 65px;">
                <div class="view-header">
                    <?php $this->load->view('common/selector_v');?>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-glasses"></i>
                    </div>
                    <div class="header-title">
                        <h1>
                            Market Explorer
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> Here you can browse Eve Online's market in real-time.<br/>
                        <i class="fa fa-info yellow"></i> Many more exciting features will follow this application in the near future.
                    </div>
                </div>
            </div>
        </div>


        <?php include('marketexplorer/index.html') ?>
    </div>
</section>