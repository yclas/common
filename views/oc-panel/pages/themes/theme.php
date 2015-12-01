<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div id="page-themes" class="page-header">
	<h1><?=__('Themes')?></h1>
    <p><?=__('You can change the look and feel of your website here.')?><a href="https://docs.yclas.com/how-to-change-theme/" target="_blank"><?=__('Read more')?></a></p>
</div>

<div class="row">
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?if ($scr = Theme::get_theme_screenshot(Theme::$theme))?>
                        <img class="media-object pull-left" width="150px" height="100px" src="<?=$scr?>">
                        <div class="clearfix"></div>
                <div class="media-body">
                    <h4 class="media-heading"><?=$selected['Name']?></h4>
                    <p>
                        <span class="label label-info"><?=__('Current Theme')?></span>
                        <?if (Theme::has_options()):?>
                        <a class="btn btn-xs btn-primary ajax-load" title="<?=__('Theme Options')?>" 
                            href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>">
                            <i class="glyphicon  glyphicon-wrench glyphicon"></i> </a>
                        <?endif?>
                    </p>
                    <p><?=$selected['Description']?></p>
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
            </div>
        </div>
    </div>
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <span class="label label-info"><?=__('Install theme')?></span>
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'download')), array('class'=>'form-inline'))?>
                    <p><?=__('Install theme from license.')?></p>
                
                    <div class="form-group">
                        <input type="text" name="license" id="license" placeholder="<?=__('license')?>" class="form-control"/>
                    </div>
                    <button 
                        type="button" 
                        class="btn btn-primary submit" 
                        title="<?=__('Are you sure?')?>" 
                        data-text="<?=sprintf(__('License will be activated in %s domain. Once activated, your license cannot be changed to another domain.'), parse_url(URL::base(), PHP_URL_HOST))?>"
                        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                        data-btnCancelLabel="<?=__('No way!')?>">
                        <?=__('Download')?>
                    </button>
                
                <?= FORM::close()?>
                
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme')), array('class'=>'form-inline', 'enctype'=>'multipart/form-data'))?>
                
                    <p><?=__('To install new theme choose zip file.')?></p>
                    <div class="form-group">
                        <input type="file" name="theme_file" id="theme_file" class="form-control" />
                    </div>
                        <?= FORM::button('submit', __('Upload'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme'))))?>
                <?= FORM::close()?>
            </div>
        </div>
    </div>
</div>

<div class="page-header">
    <h1><?=__('Available Themes')?></h1>
</div>

<? if (count($themes)>1):?>
    <div class="row">
        <?$i=0;
        foreach ($themes as $theme=>$info):?>
            <?if(Theme::$theme!==$theme):?>
            <?if ($i%3==0):?><div class="clearfix"></div><?endif?>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="thumbnail ">
                    <?if ($scr = Theme::get_theme_screenshot($theme)):?>
                        <img class="img-rounded img-responsive" src="<?=$scr?>">
                    <?endif?>
                    
        
                    <div class="caption">
                        <h3><?=$info['Name']?></h3>
                        <p><?=$info['Description']?></p>
                        <p><?=$info['License']?> v<?=$info['Version']?></p>
                        <p>
                            <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>"><?=__('Activate')?></a>
                            <?if (Core::config('appearance.allow_query_theme')=='1'):?>
                            <a class="btn btn-default" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a> 
                            <?endif?>   
                        </p>
                    </div>
                </div>
            </div>
            <?$i++;
            endif?>
        <?endforeach?>
    </div>
<?endif?>

<div class="clearfix"></div>
<?
$a_m_themes = count($mobile_themes);
if(Core::config('appearance.theme_mobile')!='')
    $a_m_themes--;

if ($a_m_themes>0):?>
<h2><?=__('Available Mobile Themes')?></h2>


<?$i=0;
foreach ($mobile_themes as $theme=>$info):?>
    <?if(Core::config('appearance.theme_mobile')!==$theme):?>
    <?if ($i%3==0):?></ul><div class="row"><ul class="thumbnails"><?endif?>
    <div class="col-md-4">
    <div class="thumbnail">

        <?if ($scr = Theme::get_theme_screenshot($theme)):?>
            <img width="300px" height="200px" src="<?=$scr?>">
        <?endif?>

        <div class="caption">
            <h3><?=$info['Name']?></h3>
            <p><?=$info['Description']?></p>
            <p><?=$info['License']?> v<?=$info['Version']?></p>
            <p>
                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>"><?=__('Activate')?></a>
                <a class="btn btn-default" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a>    
            </p>
        </div>
    </div>
    </div>
    <?$i++;
    endif?>
<?endforeach?>
<?endif?>
<div class="clearfix"></div>

<?if (count($market)>0):?>
<h2><?=__('Themes Market')?></h2>
<p><?=__('Here you can find a selection of our premium themes.')?></p>
<p class="text-success"><?=__('All themes include support, updates and 1 site license.')?></p> <?=__('Also white labeled and free of ads')?>!

<?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>    
<?endif?>
