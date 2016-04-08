<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="nav nav-tabs nav-tabs-simple">
    <?=Theme::admin_link(__('Optimize'), 'tools','optimize','oc-panel')?>
    <?=Theme::admin_link(__('Sitemap'), 'tools','sitemap','oc-panel')?>
    <?=Theme::admin_link(__('Migration'), 'tools','migration','oc-panel')?>
    <?=Theme::admin_link(__('Cache'), 'tools','cache','oc-panel')?>
    <?=Theme::admin_link(__('Logs'), 'tools','logs','oc-panel')?>
    <?=Theme::admin_link(__('PHP Info'), 'tools','phpinfo','oc-panel')?>
</ul>

<div class="panel panel-default">
    <div class="panel-body">
        <ul class="list-inline pull-right">
            <li>
                <a class="btn btn-warning pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=1" title="<?=__('Delete all')?>">
                    <?=__('Delete all')?>
                </a>
            </li>
            <li>
                <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=2" title="<?=__('Delete expired')?>">
                    <?=__('Delete expired')?>
                </a>
            </li>
        </ul>
        <h1 class="page-header page-title">
            <?=__('Cache')?>
            <a target="_blank" href="https://docs.yclas.com/modify-cache-time/">
                <i class="fa fa-question-circle"></i>
            </a>
        </h1>
        <hr>
        <div class="panel panel-default">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=__('Config file')?></th>
                        <th><?=APPPATH?>config/cache.php</th>
                    </tr>
                </thead>
                <tbody>
                    <?foreach ($cache_config as $key => $value):?>
                        <tr>
                            <td><?=$key?></td>
                            <td><?=print_r($value,1)?></td>
                        </tr>
                    <?endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>