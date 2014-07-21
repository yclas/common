<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Sitemap')?></h1>
<p><?=__('Last time generated')?> <?=Date::unix2mysql(Sitemap::last_generated_time())?></p>
<a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>?force=1">
  <?=__('Generate')?></a>
</div>