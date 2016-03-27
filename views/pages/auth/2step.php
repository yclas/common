<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="well">
	
	<div class="page-header">
		<h1><?=__('2 Step Authentication')?></h1>
	</div>

    <form class="well form-horizontal auth"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'2step'))?>">         
      <?=Form::errors()?>
      <div class="form-group">
        <label class="col-sm-2 control-label"><?=__('Code')?></label>
        <div class="col-md-5 col-sm-6">
          <input class="form-control" type="text" name="code" placeholder="<?=__('Code')?>">
        </div>
      </div>
      <div class="page-header"></div>
      <div class="col-sm-offset-2">
        <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
      </div>
      <?=Form::redirect()?>
      <?=Form::CSRF('2step')?>
    </form>         
	
</div><!--/well--> 
