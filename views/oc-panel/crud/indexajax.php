<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">   
    <?if ($controller->allowed_crud_action('create')):?>
    <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>" title="<?=__('New')?>">
        <i class="glyphicon glyphicon-pencil"></i>
        <?=__('New')?>
    </a>                
    <?endif?>

    <h1><?=Text::ucfirst(__($name))?></h1>
    <?if($name == 'product'):?><p><a href="https://docs.open-eshop.com/add-product/" target="_blank"><?=__('Read more')?></a></p>
    <?elseif($name == 'license'):?><p><a href="https://docs.open-eshop.com/manage-licenses/" target="_blank"><?=__('Read more')?></a></p>
    <?elseif($name == 'user'):?><p><a href="https://docs.yclas.com/manage-users/" target="_blank"><?=__('Read more')?></a></p>
    <?elseif($name == 'role'):?><p><a href="https://docs.yclas.com/roles-work-classified-ads-script/" target="_blank"><?=__('Read more')?></a></p>
    <?elseif($name == 'crontab'):?><p><a href="https://docs.yclas.com/how-to-set-crons/" target="_blank"><?=__('Read more')?></a></p>
    <?elseif($name == 'plan'):?><p><a href="https://docs.yclas.com/membership-plans/" target="_blank"><?=__('Read more')?></a></p><?endif;?>
    <?=$extra_info_view?>
</div>



<div class="panel panel-default">
    <div class="panel-body">

        <div id="filter_buttons" class="pull-right" data-url="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'ajax')).'?'.str_replace('rel=ajax','',$_SERVER['QUERY_STRING']) ?>">
        <?if (count($filters)>0):?>
        <form class="form-inline" id="form-ajax-load" method="get" action="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'index')) ?>">
            <?foreach($filters as $field_name=>$values)
            {
                if (is_array($values))
                {   
                    ?>
                    <select name="filter__<?=$field_name?>" id="filter__<?=$field_name?>" class="form-control disable-chosen" >
                    <option value=""><?=__('Select')?> <?=$field_name?></option>
                    <?foreach ($values as $key=>$value):?>
                        <option value="<?=$key?>" <?=(core::request('filter__'.$field_name)==$key AND core::request('filter__'.$field_name)!==NULL)?'SELECTED':''?> ><?=$field_name?> = <?=$value?></option>
                    <?endforeach?>
                    </select>
                    <?
                }
                elseif($values=='DATE')
                {
                    ?>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('From')?> <?=$field_name?></div>
                            <input type="text" class="form-control datepicker_boot" id="filter__from__<?=$field_name?>" name="filter__from__<?=$field_name?>" value="<?=core::request('filter__from__'.$field_name)?>" data-date="<?=core::request('filter__from__'.$field_name)?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <span>-</span>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('To')?> <?=$field_name?></div>
                            <input type="text" class="form-control datepicker_boot" id="filter__to__<?=$field_name?>" name="filter__to__<?=$field_name?>" value="<?=core::request('filter__to__'.$field_name)?>" data-date="<?=core::request('filter__to__'.$field_name)?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <?
                }
                elseif($values=='RANGE')
                {
                    ?>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="filter__from__<?=$field_name?>" name="filter__from__<?=$field_name?>" placeholder="<?=__('From')?> <?=$field_name?>" value="<?=core::request('filter__from__'.$field_name)?>" >
                        </div>
                    </div>
                    <span>-</span>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="filter__to__<?=$field_name?>" name="filter__to__<?=$field_name?>" placeholder="<?=__('To')?> <?=$field_name?>" value="<?=core::request('filter__to__'.$field_name)?>" >
                        </div>
                    </div>
                    <?
                }
                elseif($values=='INPUT')
                {
                    ?>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="filter__<?=$field_name?>" name="filter__<?=$field_name?>" placeholder="<?=(isset($captions[$field_name])?$captions[$field_name]['model'].' '.$captions[$field_name]['caption']:$field_name)?>" value="<?=core::request('filter__'.$field_name)?>" >
                        </div>
                    </div>
                    <?
                }
            }
            ?>
            <button type="submit" class="btn btn-primary"><span class="icon glyphicon glyphicon-search"></span> <?=__('Search')?></button>
            <a class="btn btn-xs btn-warning ajax-load" title="<?=Text::ucfirst(__($name))?>" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'index')) ?>">
                <?=__('Reset')?>
            </a>
        </form>
        <?endif?>
        </div>

        <div class="table-responsive">
            <table id="grid-data-api" class="table table-condensed table-hover table-striped" >
                <thead>
                    <tr>
                        <?foreach($fields as $field):?>
                        <th data-column-id="<?=$field?>" <?=($elements->primary_key() == $field)?'data-identifier="true"':''?> >
                            <?if(isset($captions[$field]) AND !exec::is_callable($captions[$field])):?>
                                <?=Text::ucfirst($captions[$field]['model'].' '.$captions[$field]['caption'])?>
                            <?else:?>
                                <?=Text::ucfirst($field)?>
                            <?endif?>
                        </th>
                        <?endforeach?>
                        <th data-column-id="commands" data-formatter="commands" data-sortable="false"><?=__('Actions')?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<?if ($controller->allowed_crud_action('export')):?>
<a class="btn btn-sm btn-success pull-right " href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
    <i class="glyphicon glyphicon-download"></i>
    <?=__('Export all')?>
</a>                
<?endif?>