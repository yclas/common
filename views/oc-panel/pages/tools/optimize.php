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
                <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'optimize'))?>?force=1" title="<?=__('Optimize')?>">
                    <?=__('Optimize')?>
                </a>
            </li>
        </ul>
        <h1 class="page-header page-title">
            <?=__('Optimize Database')?>
        </h1>
        <hr>
        <ul class="list-unstyled">
            <li><?=__('Database space')?> <?=round($total_space,2)?> KB</li>
            <li><?=__('Space to optimize')?> <?=round($total_gain,2)?> KB</li>
        </ul>
        <div class="panel panel-default">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=__('Table')?></th>
                        <th><?=__('Rows')?></th>
                        <th><?=__('Size')?> KB</th>
                        <th><?=__('Save size')?> KB</th>
                    </tr>
                </thead>
        
                <tbody>
                    <?foreach ($tables as $table):?>
                        <tr class="<?=($table['gain']>0)?'warning':''?>">
                            <td><?=$table['name']?></td>
                            <td><?=$table['rows']?></td>
                            <td><?=$table['space']?></td>
                            <td><?=$table['gain']?></td>
                        </tr>
                    <?endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>