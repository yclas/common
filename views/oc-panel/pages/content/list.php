<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-primary" 
            href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'create')).'?type='.$type ?>" 
            rel="tooltip" title="<?=__('New')?>">
            <i class="fa fa-plus-circle"></i>&nbsp; <?=__('New')?>
        </a>
    </li>
</ul>

<h1 class="page-header page-title">
    <?=Controller_Panel_Content::translate_type($type)?>
    <?if($type == 'page'):?>
        <a href="https://docs.yclas.com/how_to_add_pages/" target="_blank"><i class="fa fa-question-circle"></i></a>
    <?elseif($type == 'email'):?>
        <a href="https://docs.yclas.com/automatic-emails-sent-to-users/" target="_blank"><i class="fa fa-question-circle"></i></a>
    <?endif?>
</h1>

<hr>

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
    <?if (count($contents)>0):?>
        <table class="table">
            <thead>
                <tr>
                    <th><?=__('Title')?></th>
                    <th class="hidden-sm hidden-xs"><?=__('Seo Title')?></th>
                    <th class="hidden-xs"><?=__('Active')?></th>
                    <th><?=__('Actions')?></th>
                </tr>
            </thead>
            <tbody>
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
                            <td class="hidden-sm hidden-xs"><?=$content->seotitle?></td>
                            <td class="hidden-xs"><?=($content->status==1)?__('Yes'):__('No')?></td>
                            <td width="5%" class="nowrap">
                                
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
            </tbody>
        </table>
    <?else:?>
        <div class="panel-body">
            <a class="btn btn-warning btn-lg pull-right" href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'copy'))?>?to_locale=<?=$locale?>&type=<?=$type?>"  >
                <?=sprintf(__('Create all new %s from original'),$type)?>
            </a>
        </div>
    <?endif?>
</div>