<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
    <h1><?=__('Theme License')?> <?=(Request::current()->param('id')!==NULL)?Request::current()->param('id'):Theme::$theme?></h1>
    <p><?=__('Please insert here the license for your theme.')?></p>
    
</div>

<div class="well">
<form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post" class="form-horizontal"> 
    <fieldset>
        <div class="form-group">
            <label class="control-label"><?=__('License')?></label>
            <div class="col-sm-4">
              <input class="form-control" type="text" name="license" value="" placeholder="<?=__('License')?>">
            </div>
          </div>
        <div class="form-actions">
            <?= FORM::button('submit', __('Check'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
        </div>
    </fieldset> 
</form>
</div><!--end col-md-10-->