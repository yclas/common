<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($controller->allowed_crud_action('create')):?>
    <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>" title="<?=__('New')?>">
        <i class="glyphicon   glyphicon-pencil"></i>
        <?=__('New')?>
    </a>                
<?endif?>

<div class="page-header">
    <h1><?=Text::ucfirst(__($name))?></h1>
</div>

<div class="page-header">
    <p><?=__('Set up your cron at your hosting / cPanel, every minute')?> (*/5 * * * *)</p>
    <input type="text" value="/usr/bin/php -f <?=DOCROOT?>oc/modules/common/modules/cron/cron.php" />
    <p><?=__('Or')?></p>
    <input type="text" value="wget -O <?=Route::url('default', array('controller'=>'cron','action'=>'run','id'=>'now'))?>" />
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <?foreach($fields as $field):?>
                        <th><?=Text::ucfirst((method_exists($orm = ORM::Factory($name), 'formo') ? Arr::path($orm->formo(), $field.'.label', __($field)) : __($field)))?></th>
                    <?endforeach?>
                    <?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
                    <th><?=__('Actions') ?></th>
                    <?endif?>
                </tr>
            </thead>
            <tbody>
                <?foreach($elements as $element):?>
                    <tr id="tr<?=$element->pk()?>">
                        <?foreach($fields as $field):?>
                            <td><?=HTML::chars($element->$field)?></td>
                        <?endforeach?>
                        <?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
                        <td width="80" style="width:80px;">
                            <?if ($controller->allowed_crud_action('update')):?>
                            <a title="<?=__('Edit')?>" class="btn btn-primary ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$element->pk()))?>">
                                <i class="glyphicon   glyphicon-edit"></i>
                            </a>
                            <?endif?>
                            <?if ($controller->allowed_crud_action('delete')):?>
                            <a 
                                href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete','id'=>$element->pk()))?>" 
                                class="btn btn-danger index-delete" 
                                title="<?=__('Are you sure you want to delete?')?>" 
                                data-id="tr<?=$element->pk()?>" 
                                data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                data-btnCancelLabel="<?=__('No way!')?>">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                            <?endif?>
                        </td>
                        <?endif?>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>

<div class="text-center"><?=$pagination?></div>