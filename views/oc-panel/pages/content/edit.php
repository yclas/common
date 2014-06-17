<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
    <?if($cont->type == 'page'):?>
        <h1><?=__('Edit').' '.__('Page')?></h1>
    <?elseif($cont->type == 'email'):?>
        <h1><?=__('Edit').' '.__('Email')?></h1>
    <?elseif($cont->type == 'help'):?>
        <h1><?=__('Edit').' '.__('FAQ')?></h1>
    <?endif?>
</div>

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
            <?= FORM::select('locale', $locale, core::config('i18n.locale'),array('placeholder' => __('locale'), 'class' => 'form-control', 'id' => 'locale', 'required'))?>
        </div>
    </div>
    <div class="form-group">
        <?= FORM::label('description', __('Description'), array('class'=>'control-label col-md-2', 'for'=>'description'))?>
        <div class="col-sm-8">
            <?= FORM::textarea('description', $cont->description, array('id' => 'description','class' => 'form-control', 'data-editor'=>($cont->type != 'email')?'html':'bbcode'))?>
        </div>
    </div>
    <div class="form-group">
    <?= FORM::label('status', __('Status'), array('class'=>'control-label col-md-2', 'for'=>'status'))?>
        <div class="col-sm-4">
            <label class="status">
                <input type="checkbox" value="<?=$cont->status?>" name="status" <?=($cont->status == TRUE)?'checked':''?> >
            </label>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::button('submit', __('Edit'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'content','action'=>'edit','id'=>$cont->id_content))))?>
    </div>
</fieldset>
<?= FORM::close()?>
   

