<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">

    <h1><?=__('Update')?> <?=$latest_version?></h1>
    <p>
        <?=__('Your installation version is')?> <span class="label label-info"><?=core::VERSION?></span>
    </p>
    
</div>


<div class="alert alert-danger" role="alert">
<?if ($can_update===FALSE):?>
    <h4 class="alert-heading"><?=__('Not possible to auto update')?></h4>
    <p>
        <?=__('You have an old version and automatic update is not possible. Please read the release notes and the manual update instructions.')?>
        <br>
        <a target="_blank"  class="btn btn-default" href="<?=$version['blog']?>"><?=__('Release Notes')?> <?=$latest_version?></a>
    </p>
<?else:?>
    <h2 class="alert-heading"><?=__('Read carefully')?>!</h2>
    <p>
        <ul>
            <li><?=__('Backup all your files and database')?>. <a target="_blank" href="http://open-classifieds.com/2014/07/23/backup-classifieds-site/"><?=__('Read more')?></a></li>
            <li><?=__('This process can take few minutes DO NOT interrupt it')?></li>
            <li><?=__('If you have doubts check the release notes for this version')?>. <a target="_blank" href="<?=$version['blog']?>"><?=__('Release Notes')?> <?=$latest_version?></a></li>
            <li><?=__('You are responsible for any damages or down time at your site')?></li>
        </ul>
    </p>
    <br>
    <a class="btn btn-warning" onclick="return confirm('<?=__('Update?')?>');" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'latest'))?>" title="<?=__('Update')?>">
    <span class="glyphicon  glyphicon-refresh"></span> <?=__('Proceed with Update')?>
    </a>
<?endif?>
</div>