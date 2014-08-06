<?if (Theme::get('premium')==1 AND count($providers = Social::get_providers())>0):?>
    <?$first_provider = TRUE?>
    <?foreach ($providers as $key => $value):?>
        <?if($value['enabled']):?>
            <?if($first_provider):?>
                <fieldset>
                    <legend><?=__('Social Login')?></legend>
            <?endif?>
        	<?if(strtolower($key) == 'live')$key='windows'?>
            <a  class=" zocial <?=strtolower($key)?> social-btn" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>"><?=$key?></a>
            <?if($first_provider): $first_provider = FALSE?>
                </fieldset>
            <?endif?>
        <?endif?>
    <?endforeach?>
<?endif?>