<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header" id="crud-<?=__($name)?>">
    <h1><?=__('Create Blog Post')?></h1>
</div>
<?//var_dump($form)?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'blog','action'=>'create')), array('enctype'=>'multipart/form-data'))?>
        <fieldset>
            <?= FORM::hidden($form['id_post']['name'], $form['id_post']['value'])?>
            <?= FORM::hidden($form['id_user']['name'], $form['id_user']['value'])?>
            <?= FORM::hidden($form['seotitle']['name'], $form['seotitle']['value'])?>
            <div class="form-group">
                <?=FORM::label($form['title']['id'], __('Title'), array('class'=>'control-label', 'for'=>$form['title']['id']))?>
                <?=FORM::input($form['title']['name'], '', array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => $form['title']['id'], 'required'))?>
            </div>
            <div class="form-group">
                <?=FORM::label($form['description']['id'], __('Description'), array('class'=>'control-label', 'for'=>$form['description']['id']))?>
                <?=FORM::textarea($form['description']['name'], '', array('class'=>'form-control','id' => $form['description']['id'],'data-editor'=>'html', 'placeholder'=>__('Description')))?>
            </div>
            <div class="form-group">
                <div class="checkbox check-success">
                    <input type="checkbox" name="status" id="status">
                    <label for="status"><?=__('Active')?></label>
                </div>
            </div>

            <hr>
            <?=FORM::button('submit', __('Create'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'blog','action'=>'create'))))?>
        </fieldset>
        <?= FORM::close()?>
    </div>
</div>
