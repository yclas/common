<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('System Logs')?></h1>
    <p><?=__('Reading log file')?><code> <?=$file?></code></p>
</div>
<div class="panel panel-default">
    <div class="panel-body">
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
        <textarea class="col-md-9 form-control" rows="20">
            <?=$log?>
        </textarea>
    </div>
</div>