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
            <?=__('System Logs')?>
        </h1>
        <hr>
        <p><?=__('Reading log file')?><code> <?=$file?></code></p>
                <form id="" class="form-inline" method="get" action="">
                    <fieldset>
                        <div class="form-group">
                            <div class="input-group">
                                <input  type="text" class="form-control" size="16" id="date" name="date"  value="<?=$date?>" data-date-format="yyyy-mm-dd">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-primary"><?=__('Log')?></button>
                    </fieldset>
                </form>
                <br>
                <textarea class="col-md-9 form-control" rows="20">
                    <?=$log?>
                </textarea>
    </div>
</div>