<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
    <?if($cont->type != 'email' AND $cont->status == 0):?>
        <a class="btn btn-primary pull-right" target="_blank" href="<?=Route::url(($cont->type == 'help') ? 'faq' : 'page', array('seotitle'=>$cont->seotitle))?>" title="<?=__('Preview')?>">
            <i class="glyphicon glyphicon-eye-open"></i>
            <?=__('Preview')?>
        </a>
    <?endif?>	
    <h1><?=__('Edit')?> <?=Controller_Panel_Content::translate_type($cont->type)?></h1>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'edit','id'=>$cont->id_content)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
            <fieldset>
                <div class="form-group">
                    <?= FORM::label('title', __('Title'), array('class'=>'control-label col-md-2', 'for'=>'title'))?>
                    <div class="col-sm-4">
                        <?= FORM::input('title', $cont->title, array('placeholder' => __('title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
                    </div>
                </div>
                <div class="form-group">
                    <?= FORM::label('locale', __('Locale'), array('class'=>'control-label col-md-2', 'for'=>'locale'))?>
                    <div class="col-sm-4">
                        <?= FORM::select('locale', $locale, $cont->locale,array('placeholder' => __('locale'), 'class' => 'form-control', 'id' => 'locale', 'required'))?>
                    </div>
                </div>
                <div class="form-group">
                    <?= FORM::label('description', __('Description'), array('class'=>'control-label col-md-2', 'for'=>'description'))?>
                    <div class="col-sm-8">
                        <?= FORM::textarea('description', $cont->description, array('id' => 'description','class' => 'form-control', 'data-editor'=>'html'))?>
                    </div>
                </div>
            
                <?if($cont->type == 'email'):?>
                <div class="form-group">
                    <?= FORM::label('from_email', __('From email'), array('class'=>'control-label col-md-2', 'for'=>'from_email'))?>
                    <div class="col-sm-4">
                        <?= FORM::input('from_email', $cont->from_email, array('placeholder' => __('from_email'), 'class' => 'form-control', 'id' => 'from_email'))?>
                    </div>
                </div>
                <?endif?>
            
                <?if($cont->type != 'email'):?>
                <div class="form-group">
                    <?= FORM::label('seotitle', __('Seotitle'), array('class'=>'control-label col-md-2', 'for'=>'seotitle'))?>
                    <div class="col-sm-4">
                        <?= FORM::input('seotitle', $cont->seotitle, array('placeholder' => __('seotitle'), 'class' => 'form-control', 'id' => 'seotitle'))?>
                    </div>
                </div>
                <?endif?>
            
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label class="status">
                                <input type="checkbox" value="<?=$cont->status?>" name="status" <?=($cont->status == TRUE)?'checked':''?> > <?=__('Active')?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?= FORM::button('submit', __('Edit'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'content','action'=>'edit','id'=>$cont->id_content))))?>
                    </div>
                </div>
            </fieldset>
        <?= FORM::close()?>
    </div>
</div>
