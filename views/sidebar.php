<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="col-md-3 col-sm-12 col-xs-12"> 
<?foreach ( Widgets::render('sidebar') as $widget):?>
    <div class="category_box_title custom_box"></div>
    <div class="well custom_box_content" >
        <?=$widget?>
    </div>
<?endforeach?>
</div>