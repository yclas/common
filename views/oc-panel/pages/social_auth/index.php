<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header ">
    <h1><?=__('Social Authentication Settings')?></h1>
    <a target='_blank' href='http://open-classifieds.com/2013/12/26/how-to-login-using-social-auth-facebook-google-twitter'><?=__('Read more')?></a>
    </div>
    <?if (Theme::get('premium')!=1):?>
        <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
            <?=__('Social authentication is only available with premium themes!').'<br/>'.__('Upgrade your Open Classifieds site to activate this feature.')?>
            <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
        </p>
    <?endif?>
    <div class="well">
    <?= FORM::open(Route::url('oc-panel',array('controller'=>'social', 'action'=>'index')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
        <fieldset>
            <div class="form-group">
            <?= FORM::label('debug_mode', __('Debug Mode'), array('class'=>'control-label col-sm-2', 'for'=>'debug_mode'))?>
                <div class="col-sm-4">
                    <div class="onoffswitch">
                        <?= FORM::hidden('debug_mode', 0);?>
                        <?= FORM::checkbox('debug_mode', 1, (bool) $config['debug_mode'], array(
                        'placeholder' => "", 
                        'class' => 'onoffswitch-checkbox', 
                        'id' => 'debug_mode', 
                        ))?>
                        <?= FORM::label('debug_mode', "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>'debug_mode'))?>
                    </div>
                </div>
            </div>
            <hr>
            <?foreach ($config['providers'] as $api => $options):?>
                <div class="form-group">
                <?= FORM::label($api, $api, array('class'=>'control-label col-sm-2', 'for'=>$api))?>
                    <div class="col-sm-4">
                        <div class="onoffswitch">
                            <?= FORM::hidden($api, 0);?>
                            <?= FORM::checkbox($api, 1, (bool) $options['enabled'], array(
                            'placeholder' => "", 
                            'class' => 'onoffswitch-checkbox', 
                            'id' => $api, 
                            ))?>
                            <?= FORM::label($api, "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$api))?>
                        </div>
                    </div>
                </div>
                <?if(isset($options['keys']['id'])):?>
                    <div class="form-group">
                    <?= FORM::label($api.'_id_label', __('Id'), array('class'=>'control-label col-sm-2', 'for'=>$api))?>
                        <div class="col-sm-4">
                            <?=FORM::input($api.'_id', $options['keys']['id']);?>
                        </div>
                    </div>
                <?endif?>
                <?if(isset($options['keys']['key'])):?>
                    <div class="form-group">
                    <?= FORM::label($api.'_key_label', __('Key'), array('class'=>'control-label col-sm-2', 'for'=>$api))?>
                        <div class="col-sm-4">
                            <?=FORM::input($api.'_key', $options['keys']['key']);?>
                        </div>
                    </div>
                <?endif?>
                <?if(isset($options['keys']['secret'])):?>
                    <div class="form-group">
                    <?= FORM::label($api.'_secret_label', __('secret'), array('class'=>'control-label col-sm-2', 'for'=>$api))?>
                        <div class="col-sm-4">
                            <?=FORM::input($api.'_secret', $options['keys']['secret']);?>
                        </div>
                    </div>
                <?endif?>
                <hr>
            <?endforeach?>
            <div class="form-actions">
                <?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'social', 'action'=>'index'))))?>
            </div>
        </fieldset>
    <?FORM::close()?>
    </div>
