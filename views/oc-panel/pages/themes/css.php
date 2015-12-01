<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
    <h1><?=__('Custom CSS')?></h1>
    <p><a href="https://docs.yclas.com/how-to-use-custom-css/" target="_blank"><?=__('Read more')?></a></p>
    <p><?=__('Please insert here your custom CSS.')?>. <?=__('Current CSS file')?>  <?=HTML::anchor(Theme::get_custom_css())?> </p>
    
</div>

<div class="row">
    <div class="col-md-8">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post"> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <?= FORM::label('css_active', __('Switch off custom CSS'), array('class'=>'control-label col-sm-4', 'for'=>'css_active'))?>
                            <div class="col-sm-8">
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
                            <label class="control-label col-sm-4"><?=__('CSS')?></label>
                            <div class="col-sm-8">
                                <textarea rows="30" class="col-sm-9" name="css"><?=$css_content?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
