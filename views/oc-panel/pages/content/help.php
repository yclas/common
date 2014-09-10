<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=Controller_Panel_Content::translate_type($type)?></h1>
</div>

<a class="btn btn-primary pull-right ajax-load" 
    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'create')).'?type='.$type ?>" 
    rel="tooltip" title="<?=__('New')?>">
    <?=__('New')?>
</a>

<?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'list')), array('method'=>'GET','class'=>'form-horizontal', 'id'=>'locale_form','enctype'=>'multipart/form-data'))?>
    <div class="form-group">

        <div class="col-sm-4">
            <?= FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
            <?= FORM::select('locale_select', $locale_list, $locale )?> 
        </div>
        <div class="col-sm-4">
            <?= FORM::hidden('type', $type )?> 
        </div>
    </div>
<?= FORM::close()?>

<?if (count($contents)>0):?>
<div class="row">
    <ol class='plholder col-md-9' id="ol_1" data-id="1">
    <?foreach($contents as $content):?>
        <li data-id="<?=$content->id_content?>" id="<?=$content->id_content?>"><i class="glyphicon glyphicon-move"></i>
            <?=$content->title?>
            <?if ($content->status==1) : ?>
            <span class="label label-info "><?=__('Active')?></span>
            <?endif?>
            <a data-text="<?=__('Are you sure you want to delete? All data contained in this field will be deleted.')?>" 
               data-id="<?=$content->id_content?>" 
               class="btn btn-xs btn-danger pull-right index-delete index-delete-inline"  
               href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'delete','id'=>$content->id_content))?>">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
    
            <a class="btn btn-xs btn-primary pull-right ajax-load" 
                href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'edit','id'=>$content->id_content))?>">
                <?=__('Edit')?>
            </a>
        </li>
    <?endforeach?>
    </ol><!--ol_1-->
    
    <span id='ajax_result' data-url='<?=Route::url('oc-panel', array('controller'=>'content','action'=>'saveorder'))?>?to_locale=<?=$locale?>&type=<?=$type?>'></span>
</div>

<hr>
<?else:?>
<a class="btn btn-warning btn-lg pull-right" href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'copy'))?>?to_locale=<?=$locale?>&type=<?=$type?>"  >
     <?=sprintf(__('Create all new %s from original'),$type)?>
</a>
<?endif?>