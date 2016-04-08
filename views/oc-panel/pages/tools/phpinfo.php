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
        <h1 class="page-header page-title">
            <?=__('PHP Info')?>
        </h1>
        <hr>
        <div class="panel panel-default">
            <div class="panel-body">
            	<?=$phpinfo?>
            </div>
        </div>
    </div>
</div>