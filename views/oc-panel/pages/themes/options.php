<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<h1 class="page-header page-title">
    <?=__('Theme Options')?> <?=(Request::current()->param('id')!==NULL)?Request::current()->param('id'):Theme::$theme?>
</h1>

<?if(Core::config('appearance.theme_mobile')!=''):?>
    <p>
        <?=__('Using mobile theme')?> <code><?=Core::config('appearance.theme_mobile')?></code>
        <a class="btn btn-sm btn-primary" 
            title="<?=__('Options')?>" 
            href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>Core::config('appearance.theme_mobile')))?>">
            <i class="fa fa-wrench"></i> <?=__('Options')?>
        </a>
        <a class="btn btn-sm btn-warning" 
            title="<?=__('Disable')?>" 
            href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>'disable'))?>">
            <i class="fa fa-minus"></i> <?=__('Disable')?>
        </a>
    </p>
<?endif?>

<hr>

<p><?=__('Here are listed specific theme configuration values. Replace input fields with new desired values for the theme.')?></p>

<div class="row">
    <div class="col-md-12">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post" enctype="multipart/form-data"> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <?foreach ($options as $field => $attributes):?>
                        <div class="form-group">
                            <?=FORM::form_tag($field, $attributes, (isset($data[$field]))?$data[$field]:NULL)?>
                        </div>
                    <?endforeach?>
                    
                    <hr>
                    <?=FORM::button('submit', __('Update'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
                </div>
            </div>
        </form>
    </div>
</div>
