<div class="dropdown pull-right">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownmenu-characters" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        Character
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
    <?php  for($i=0; $i<count($character_list['chars']); $i++) { 
        $selector['hasInterval'] ? $intervalUri = "/".$interval : $intervalUri = "";
        $selector['hasRegion'] ? $regionUri = "/".$region_id : $regionUri = "";
        $selector['gets'] ? $get = "&sig=" . $sig : $get = "";
    ?>
        <li><a href="<?=base_url($selector['page'] . '/index/'.$character_list['chars'][$i] . $intervalUri . $regionUri . '?aggr=0' . $get)?>"><?=$character_list['char_names'][$i]?></a></li>    
    <?php } ?>
        <li role="separator" class="divider"></li>
        <?php $url = $selector['page'] . '/index/'.$character_id . $intervalUri . $regionUri . '?aggr=1' . $get;?>
        <li><a href="<?=base_url($url)?>"><b>All</b></a></li>
    </ul>
</div>