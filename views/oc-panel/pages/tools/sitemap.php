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
                <a class="btn btn-primary ajax-load" title="<?=__('Sitemap')?>" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>?force=1">
                    <?=__('Generate')?>
                </a>
            </li>
        </ul>
        <h1 class="page-header page-title">
            <?=__('Sitemap')?>
            <a target="_blank" href="https://docs.yclas.com/sitemap-classifieds-website/">
                <i class="fa fa-question-circle"></i>
            </a>
        </h1>
        <hr>
        <ul class="list-unstyled">
            <li><?=__('Last time generated')?> <?=Date::unix2mysql(Sitemap::last_generated_time())?></li>
            <li><?=__('Your sitemap XML to submit to engines')?></li>
            <li><input type="text" value="<?=core::config('general.base_url')?><?=(file_exists(DOCROOT.'sitemap-index.xml'))? 'sitemap-index.xml':'sitemap.xml.gz'?>" /></li>
        </ul>
    </div>
</div>