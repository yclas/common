<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
    <h1><?=__('Custom CSS')?></h1>
    <p><?=__('Please insert here your custom CSS.')?>. <?=__('Current CSS file')?>  <?=HTML::anchor(Theme::get_custom_css())?> </p>
    
</div>

<div class="well">
<form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post"> 
    <fieldset>
            
        <div class="form-group">
            <?= FORM::label('css_active', __('Switch off custom CSS'), array('class'=>'control-label col-sm-3', 'for'=>'css_active'))?>
            <div class="col-sm-4">
                <div class="onoffswitch">
                    <?= FORM::hidden('css_active', 0);?>
                    <?= FORM::checkbox('css_active', 1, (bool) $css_active, array(
                    'placeholder' => "", 
                    'class' => 'onoffswitch-checkbox', 
                    'id' => 'css_active', 
                    'data-original-title'=> __("Switch off custom CSS"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-content'=>__("Require only the logged in users to post."),
                    ))?>
                    <?= FORM::label('css_active', "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>'css_active'))?>
                </div>
            </div>
        </div>     

        <div class="form-group">
            <label class="control-label"><?=__('CSS')?></label>
            <div class="col-sm-12">
                <textarea rows="30" class="col-sm-9" name="css"><?=$css_content?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
        </div>
        
    </fieldset> 
</form>
</div><!--end col-md-10-->