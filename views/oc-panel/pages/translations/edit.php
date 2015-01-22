<div class="page-header">
    <h1><?=__('Translations')?> <?=$edit_language?></h1>

    <a  class="btn btn-danger pull-right" href="<?=Request::current()->url()?>?translated=1" title="<?=__('Hide translated texts')?>" >
        <i class="glyphicon glyphicon-eye-close"></i> 
    </a>

    <a  class="btn btn-primary pull-right" href="<?=Request::current()->url()?>" title="<?=__('Show translated texts')?>">
        <i class="glyphicon glyphicon-eye-open"></i> 
    </a>
    

    <p>
    <?=__('Here you can modify any text you find in your web.')?><a href="http://open-classifieds.com/2013/08/16/how-to-change-texts/" target="_blank"><?=__('Read more')?></a>
    <?=sprintf("Total of %u strings. %u strings already translated", $total_items, $total_items-$cont_untranslated)?>. <span class="error"><?=sprintf("%u strings yet to translate",$cont_untranslated)?>.</span>
    </p>

</div>

<form enctype="multipart/form-data" class="form form-horizontal" accept-charset="utf-8" method="post" action="<?=str_replace('rel=ajax', '', URL::current())?>">

    <table class="table table-bordered">
    <tr>
        <th>#</th>
        <th><?=__('Original Translation')?></th>
        <th><button class="btn" id="button-copy-all" 
                data-text="<?=__('Copy all?, Be aware this will replace all your texts.')?>" >
                <i class="glyphicon glyphicon-arrow-right"></i></button>
            <?if (strlen(Core::config('general.translate'))>0):?>
                <button id="button-translate-all" class="btn" data-apikey="<?=Core::config('general.translate')?>"
                    data-text="<?=__('Translate all?, Be aware this will replace all your texts.')?>"
                    data-langsource="en" data-langtarget="<?=substr($edit_language,0,2)?>" ><i class="glyphicon glyphicon-globe"></i>
                </button>
            <?endif?>
        </th>
        <th><?=__('Translation')?> <?=$edit_language?></th>
    </tr>
    <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="glyphicon glyphicon-hdd"></i> <?=__('Save')?></button>

    <?foreach($translation_array as $key => $values):?>
        <?list($id,$original,$translated) = array_values($values);?>
        <tr id="tr_<?=$id?>" class="<?=(strlen($translated)>0)? 'success': 'error'?>">
            <td width="5%"><?=$id?></td>
            <td>
                <textarea id="orig_<?=$id?>" disabled style="width: 100%"><?=$original?></textarea>
            </td>
            <td width="5%">
                <button class="btn button-copy" data-orig="orig_<?=$id?>" data-dest="dest_<?=$id?>" data-tr="tr_<?=$id?>" ><i class="glyphicon glyphicon-arrow-right"></i></button>
                <br>
                <?if (strlen(Core::config('general.translate'))>0):?>
                    <button class="btn button-translate" data-orig="orig_<?=$id?>" data-dest="dest_<?=$id?>" data-tr="tr_<?=$id?>" ><i class="glyphicon glyphicon-globe"></i></button>
                <?else:?>
                    <a target="_blank" class="btn" 
                    href="http://translate.google.com/#en/<?=substr($edit_language,0,2)?>/<?=urlencode($original)?>">
                    <i class="glyphicon glyphicon-globe"></i></a>
                <?endif?>
            </td>
            <td>  
                <textarea id="dest_<?=$id?>" style="width: 100%" name="translations[<?=$id?>]"><?=$translated?></textarea>
            </td>
        </tr>
    <?endforeach;?>

    </table>
    <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="glyphicon glyphicon-hdd"></i> <?=__('Save')?></button>

    <?=$pagination?>

</form>
