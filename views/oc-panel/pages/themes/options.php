<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Theme Options')?> <?=(Request::current()->param('id')!==NULL)?Request::current()->param('id'):Theme::$theme?></h1>
    <p><?=__('Here are listed specific theme configuration values. Replace input fields with new desired values for the theme.')?></p>
    <?if(Core::config('appearance.theme_mobile')!=''):?>
            <p>
                <?=__('Using mobile theme')?> <code><?=Core::config('appearance.theme_mobile')?></code>
                <a class="btn btn-xs btn-warning" title="<?=__('Disable')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>'disable'))?>">
                    <i class="glyphicon   glyphicon-remove"></i>
                </a>
                <a class="btn btn-xs btn-primary" title="<?=__('Options')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>Core::config('appearance.theme_mobile')))?>">
                <i class="glyphicon  glyphicon-wrench glyphicon"></i></a>
            </p>
        <?endif?>
</div>

<div class="row">
    <div class="col-md-8">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post" class="form-horizontal" enctype="multipart/form-data"> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <?foreach ($options as $field => $attributes):?>
                            <div class="form-group">
                                <?=FORM::form_tag($field, $attributes, (isset($data[$field]))?$data[$field]:NULL)?>
                            </div>
                        <?endforeach?>
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-7">
                                <?= FORM::button('submit', __('Update'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
