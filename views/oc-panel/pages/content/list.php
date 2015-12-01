<?php defined('SYSPATH') or die('No direct script access.');?>

<a class="btn btn-primary pull-right ajax-load" 
    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'create')).'?type='.$type ?>" 
    rel="tooltip" title="<?=__('New')?>">
    <?=__('New')?>
</a>

<div class="page-header">
    <h1><?=Controller_Panel_Content::translate_type($type)?></h1>
    <?if($type == 'page'):?><p><a href="https://docs.yclas.com/how_to_add_pages/" target="_blank"><?=__('Read more')?></a></p><?endif;?>
</div>

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

<div class="panel panel-default">
    <div class="panel-body">
        <?if (count($contents)>0):?>
            <table class="table table-bordered">
                <tr>
                    <th><?=__('Title')?></th>
                    <th><?=__('Seo Title')?></th>
                    <th><?=__('Active')?></th>
                    <th><?=__('Actions')?></th>
                </tr>
                <?foreach ($contents as $content):?>
                    <?if(isset($content->title)):?>
                        <tr id="tr<?=$content->id_content?>">
                            <td>
                                <p><?=$content->title?></p>
                                <?if ($type=='page'): ?>
                                    <p>
                                        <?if ($content->status==1):?>
                                            <a title="<?=HTML::chars($content->title)?>" href="<?=Route::url('page', array('seotitle'=>$content->seotitle))?>">
                                                <?=Route::url('page', array('seotitle'=>$content->seotitle))?>
                                            </a>
                                        <?else:?>
                                            <?=Route::url('page', array('seotitle'=>$content->seotitle))?>
                                        <?endif?>
                                    </p>
                                <?endif?>
                            </td>
                            <td><?=$content->seotitle?></td>
                            <td><?=($content->status==1)?__('Yes'):__('No')?></td>
                            <td width="5%">
                                
                                <a class="btn btn-primary ajax-load" 
                                    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'edit','id'=>$content))?>" 
                                    rel="tooltip" title="<?=__('Edit')?>">
                                    <i class="glyphicon   glyphicon-edit"></i>
                                </a>
                                <?if ( ! ($type == 'email' AND $locale == i18n::$locale_default)):?>
                                <a 
                                    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'delete','id'=>$content->id_content))?>" 
                                    class="btn btn-danger index-delete" 
                                    title="<?=__('Are you sure you want to delete?')?>" 
                                    data-id="tr<?=$content->id_content?>" 
                                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                    data-btnCancelLabel="<?=__('No way!')?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                <?endif?>
                                
                            </td>
                        </tr>
                    <?endif?>
                <?endforeach?>
            </table>
        <?endif?>
        <a class="btn btn-warning btn-lg pull-right" href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'copy'))?>?to_locale=<?=$locale?>&type=<?=$type?>"  >
            <?=sprintf(__('Create missing %s from original'),$type)?>
        </a>
    </div>
</div>