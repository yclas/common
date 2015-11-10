<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">	
	<?if ($controller->allowed_crud_action('create')):?>
	<a class="btn btn-primary pull-right ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>" title="<?=__('New')?>">
		<i class="glyphicon glyphicon-pencil"></i>
		<?=__('New')?>
	</a>				
	<?endif?>

	<h1><?=Text::ucfirst(__($name))?></h1>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
            <table id="grid-data-api" class="table table-condensed table-hover table-striped" >
                <thead>
                    <tr>
                        <?foreach($fields as $field):?>
                        <th data-column-id="<?=$field?>" <?=($elements->primary_key() == $field)?'data-identifier="true"':''?> ><?=Text::ucfirst(__($field))?></th>
                        <?endforeach?>
                        <th data-column-id="commands" data-formatter="commands" data-sortable="false">Commands</th>
                    </tr>
                </thead>
            </table>
		</div>
	</div>
</div>

<?if ($controller->allowed_crud_action('export')):?>
<a class="btn btn-sm btn-success pull-right " href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
    <i class="glyphicon glyphicon-download"></i>
    <?=__('Export all')?>
</a>                
<?endif?>