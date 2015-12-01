<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <p><?=__('Set up your cron at your hosting / cPanel, every 5 minutes')?> (*/5 * * * *)</p>
    <input type="text" value="/usr/bin/php -f <?=DOCROOT?>oc/common/modules/cron/cron.php" />
    <p><?=__('Or')?></p>
    <input type="text" value="wget -O <?=Route::url('default', array('controller'=>'cron','action'=>'run','id'=>'now'))?>" />
</div>