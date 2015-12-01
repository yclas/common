<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <div class="btn-group pull-right">
        <div class="btn-group dropdown">
            <?if (class_exists('IntlCalendar')) :?>
            <button class="btn btn-primary" data-toggle="dropdown" type="button"><?=__('New translation')?></button>
            <div class="dropdown-menu dropdown-menu-right">
                <form class="col-sm-12" role="form" method="post" action="<?=Request::current()->url()?>">
                    <div class="form-group">
                        <label class="sr-only" for="locale"><?=__('New translation')?></label>
                        <select class="form-control" id="locale" name="locale">
                                <?foreach (IntlCalendar::getAvailableLocales() as $locale):?>
                                    <option value="<?=$locale?>"><?=$locale?></option>
                                <?endforeach?>
                        </select>
                        <p class="help-block"><?=__('If your locale is not listed, be sure your hosting has your locale installed.')?></p>
                    </div>
                    <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
                </form>
            </div>
            <?endif?>
        </div>
        <a class="btn btn-warning" href="<?=Route::url('oc-panel',array('controller'=>'translations','action'=>'index'))?>?parse=1" >
            <?=__('Scan')?>
        </a>
    </div>
    <h1><?=__('Translations')?></h1>
	<p><?=__('Translations files available in the system.')?> <a href="https://docs.yclas.com/how-to-change-language/" target="_blank"><?=__('Read more')?></a></p>
    <div class="clearfix"></div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-bordered">
            <tr>
                <th><?=__('Language')?></th>
                <th></th>
                <th></th>
            </tr>
            <?foreach ($languages as $language):?>
                <tr class="<?=($language==$current_language)?'success':''?>">
                    <td><?=$language?></td>
                    <td width="5%">
                        
                        <a class="btn btn-warning ajax-load" 
                            href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'edit','id'=>$language))?>" 
                            rel"tooltip" title="<?=__('Edit')?>">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </a>
            
                    </td>
                    <td width="10%">
                        <?if ($language!=$current_language):?>
                        <a class="btn btn-default" 
                            href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'index','id'=>$language))?>" 
                            rel"tooltip" title="<?=__('Activate')?>">
                            <?=__('Activate')?>
                        </a>
                        <?else:?>
                            <span class="badge badge-info"><?=__('Active')?></span>
                        <?endif?>
                    </td>
                </tr>
            <?endforeach?>
        </table>
    </div>
</div>