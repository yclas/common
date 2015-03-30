<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header" id="crud-<?=__($name)?>">
    <?if($form['status']['value']==0):?>
        <a class="btn btn-primary pull-right" target="_blank" href="<?=Route::url('blog', array('seotitle'=>$form['seotitle']['value']))?>" title="<?=__('Preview')?>">
            <i class="glyphicon glyphicon-eye-open"></i> 
            <?=__('Preview')?>
        </a>
	<?endif?>
    <h1><?=__('Edit Blog Post')?></h1>
</div>
<?//var_dump($form)?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'blog','action'=>'update')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
            <fieldset>
                <?= FORM::hidden($form['id_post']['name'], $form['id_post']['value'])?>
                <?= FORM::hidden($form['id_user']['name'], $form['id_user']['value'])?>
                <?= FORM::hidden($form['seotitle']['name'], $form['seotitle']['value'])?>
                <div class="form-group">
                    <?= FORM::label($form['title']['id'], __('Title'), array('class'=>'control-label col-md-2', 'for'=>$form['title']['id']))?>
                    <div class="col-sm-4">
                        <?= FORM::input($form['title']['name'], $form['title']['value'], array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => $form['title']['id'], 'required'))?>
                    </div>
                </div>
                <div class="form-group">
                    <?= FORM::label($form['description']['id'], __('Description'), array('class'=>'control-label col-md-2', 'for'=>$form['description']['id']))?>
                    <div class="col-sm-9">
                        <?= FORM::textarea($form['description']['name'], $form['description']['value'], array('class'=>'form-control','id' => $form['description']['id'],'data-editor'=>'html'))?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label class="status">
                                <?= FORM::checkbox($form['status']['name'], 1, (bool) $form['status']['value'])?> <?=__('Active')?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?= FORM::button('submit', __('Edit'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'blog','action'=>'update'))))?>
                    </div>
                </div>
            </fieldset>
        <?= FORM::close()?>
    </div>
</div>