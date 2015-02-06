<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=__($name)?>">
	<h1><?=__('Update')?> <?=ucfirst(__($name))?></h1>
    <?$controllers = Model_Access::list_controllers()?>
    <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'order','action'=>'index'))?>?email=<?=$form->object->email?>">
      <?=__('Orders')?>
    </a>
    <?if (array_key_exists('ticket', $controllers)) :?>
      - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index','id'=>'admin'))?>?search=<?=$form->object->email?>">
          <?=__('Tickets')?></a>
      </a>
    <?endif?>
    <?if (array_key_exists('ad', $controllers)) :?>
      - <a target="_blank" href="<?=Route::url('profile',array('seoname'=>$form->object->seoname))?>">
          <?=__('Ads')?>
      </a>
    <?endif?>
    <?if (core::config('advertisement.reviews')==1 OR core::config('product.reviews')==1):?>
      - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'review','action'=>'index'))?>?email=<?=$form->object->email?>">
          <?=__('Reviews')?>
      </a>
    <?endif?>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
    </div>
</div>
