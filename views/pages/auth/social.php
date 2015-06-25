<?if (Theme::get('premium')==1):?>
    <fieldset>
        <?$providers = Social::get_providers(); $i = 0; foreach ($providers as $key => $value):?>
            <?if($value['enabled']):?>
                <?if($i==0):?>
                    <legend><?=__('Social Login')?></legend>
                <?endif?>
                <?if(strtolower($key) == 'live')$key='windows'?>
                    <a class="zocial <?=strtolower($key)?> social-btn" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>"><?=$key?></a>
            <?$i++; endif?>
        <?endforeach?>
    </fieldset>
<?endif?>