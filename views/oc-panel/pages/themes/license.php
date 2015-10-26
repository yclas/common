<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
    <h1><?=__('Theme License')?> <?=(Request::current()->param('id')!==NULL)?Request::current()->param('id'):Theme::$theme?></h1>
    <p><?=__('Please insert here the license for your theme.')?></p>
</div>

<div class="row">
    <div class="col-md-8">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post"> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label"><?=__('License')?></label>
                            <div class="col-sm-4">
                                <input class="form-control" type="text" name="license" value="" placeholder="<?=__('License')?>">
                            </div>
                          </div>
                        <div class="form-actions">
                            <button 
                                type="button" 
                                class="btn btn-primary submit" 
                                title="<?=__('Are you sure?')?>" 
                                data-text="<?=sprintf(__('License will be activated in %s domain. Once activated, your license cannot be changed to another domain.'), parse_url(URL::base(), PHP_URL_HOST))?>"
                                data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                data-btnCancelLabel="<?=__('No way!')?>">
                                <?=__('Check')?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
