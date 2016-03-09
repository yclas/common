<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Create')?> <?=Controller_Panel_Content::translate_type($type)?></h1>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'create')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
            <fieldset>
                <div class="form-group">
                    <?= FORM::label('title', __('Title'), array('class'=>'control-label col-md-2', 'for'=>'title'))?>
                    <div class="col-sm-4">
                        <?= FORM::input('title', '', array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
                    </div>
                </div>
                <div class="form-group">
                    <?= FORM::label('locale', __('Locale'), array('class'=>'control-label col-md-2', 'for'=>'locale'))?>
                    <div class="col-sm-4">
                        <?= FORM::select('locale', $locale, core::config('i18n.locale'),array('placeholder' => __('locale'), 'class' => 'form-control', 'id' => 'locale', 'required'))?>
                    </div>
                </div>
                <div class="form-group">
                    <?= FORM::label('description', __('Description'), array('class'=>'control-label col-md-2', 'for'=>'description'))?>
                    <div class="col-sm-9">
                        <?= FORM::textarea('description', '', array('class'=>'form-control','id' => 'description','data-editor'=>'html', 'placeholder'=>__('Description')))?>
                    </div>
                </div>
                
                <?if(core::request('type') == 'email'):?>
                <div class="form-group">
                    <?= FORM::label('from_email', __('From email'), array('class'=>'control-label col-md-2', 'for'=>'from_email'))?>
                    <div class="col-sm-4">
                        <?= FORM::input('from_email', '', array('placeholder' => __('from_email'), 'class' => 'form-control', 'id' => 'from_email'))?>
                    </div>
                </div>
                <?endif?>
            
                <div class="form-group">
                    <?= FORM::label('seotitle', __('Seotitle'), array('class'=>'control-label col-md-2', 'for'=>'seotitle'))?>
                    <div class="col-sm-4">
                        <?= FORM::input('seotitle', '', array('placeholder' => __('seotitle'), 'class' => 'form-control', 'id' => 'seotitle'))?>
                    </div>
                </div>
            
                <div class="form-group">
                    <div class="col-sm-4">
                        <?= FORM::hidden('type', $type, array('placeholder' => __('Type'), 'class' => 'form-control', 'id' => 'type'))?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label class="status">
                            	<input type="checkbox" name="status"> <?=__('Active')?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?= FORM::button('submit', __('Create'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'content','action'=>'create'))))?>
                    </div>
                </div>
            </fieldset>
        <?= FORM::close()?>
    </div>
</div>