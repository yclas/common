<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=Controller_Panel_Content::translate_type($type)?></h1>
    <?if($type == 'page'):?><p><a href="http://open-classifieds.com/2013/08/13/how_to_add_pages/" target="_blank"><?=__('Read more')?></a></p><?endif;?>
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
<table class="table table-bordered">
    <tr>
        <th><?=__('Title')?></th>
        <th><?=__('Seo Title')?></th>
        <th><?=__('Active')?></th>
        <th></th>
    </tr>
<?foreach ($contents as $content):?>
    <?if(isset($content->title)):?>
        <tr id="tr<?=$content->id_content?>">
            <td><?=$content->title?></td>
            <td><?=$content->seotitle?></td>
            <td><?=($content->status==1)?__('Yes'):__('No')?></td>
            <td width="5%">
                
                <a class="btn btn-primary ajax-load" 
                    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'edit','id'=>$content))?>" 
                    rel="tooltip" title="<?=__('Edit')?>">
                    <i class="glyphicon   glyphicon-edit"></i>
                </a>
                <a class="btn btn-danger index-delete"  data-text="<?=__('Are you sure you want to delete?')?>" 
                        data-id="tr<?=$content->id_content?>"
                    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'delete','id'=>$content))?>" 
                    rel="tooltip" title="<?=__('Delete')?>">
                    <i class="glyphicon   glyphicon-trash"></i>
                </a>

            </td>
        </tr>
    <?endif?>
<?endforeach?>
<?else:?>
    <a class="btn btn-warning btn-lg pull-right" href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'copy'))?>?to_locale=<?=$locale?>&type=<?=$type?>"  >
         <?=sprintf(__('Create all new %s from original'),$type)?>
    </a>
<?endif?>
</table>