<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <a class="btn btn-warning pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=1" title="<?=__('Delete all')?>">
        <?=__('Delete all')?></a>
    <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=2" title="<?=__('Delete expired')?>">
        <?=__('Delete expired')?></a>
    <h1><?=__('Cache')?></h1>
	<p><?=__('Cache configuration information')?>. <a href="https://docs.yclas.com/modify-cache-time/" target="_blank"><?=__('Read more')?></a></p>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <td><?=__('Config file')?></td>
                <td><?=APPPATH?>config/cache.php</td>
            </tr>
        <?foreach ($cache_config as $key => $value):?>
            <tr>
                <td><?=$key?></td>
                <td><?=print_r($value,1)?></td>
            </tr>
        <?endforeach?>
        </table>
    </div>
</div>