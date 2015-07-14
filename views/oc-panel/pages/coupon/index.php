<?php defined('SYSPATH') or die('No direct script access.');?>
	<a class="btn btn-primary pull-right" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>">
		<i class="glyphicon glyphicon-pencil"></i>
		<?=__('New')?>
	</a>		
    <a class="btn btn-success pull-right" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'bulk')) ?>">
        <i class="glyphicon glyphicon-list-alt"></i>
        <?=__('Bulk')?>
    </a>		


<?if (Theme::get('premium')!=1):?>
    <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
        <?=__('only available with premium themes!').'<br/>'.__('Upgrade your Open Classifieds site to activate this feature.')?>
        <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
    </p>
<?endif?>

<form class="form-inline pull-right" method="get" action="<?=URL::current();?>">
  	<div class="form-group">
  		<input type="text" class="form-control" name="name" placeholder="<?=__('Coupon name')?>" value="<?=core::get('name')?>">
  	</div>
  	<button type="submit" class="btn"><?=__('Search')?></button>
</form>
<div class="page-header">
	<h1><?=ucfirst(__($name))?></h1>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<?foreach($fields as $field):?>
							<th><?=ucfirst((method_exists($orm = ORM::Factory($name), 'formo') ? Arr::path($orm->formo(), $field.'.label', __($field)) : __($field)))?></th>
						<?endforeach?>
						<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
						<th><?=__('Actions') ?></th>
						<?endif?>
					</tr>
				</thead>
				<tbody>
					<?foreach($elements as $element):?>
						<tr id="tr<?=$element->pk()?>">
							<?foreach($fields as $field):?>
								<td><?=HTML::chars($element->$field)?></td>
							<?endforeach?>
							<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
							<td width="80" style="width:80px;">
								<?if ($controller->allowed_crud_action('update')):?>
								<a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$element->pk()))?>">
									<i class="glyphicon glyphicon-edit"></i>
								</a>
								<?endif?>
								<?if ($controller->allowed_crud_action('delete')):?>
									<a 
										href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete','id'=>$element->pk()))?>" 
										class="btn btn-danger index-delete" 
										data-title="<?=__('Are you sure you want to delete?')?>" 
										data-id="tr<?=$element->pk()?>" 
										data-btnOkLabel="<?=__('Yes, definitely!')?>" 
										data-btnCancelLabel="<?=__('No way!')?>">
										<i class="glyphicon glyphicon-trash"></i>
									</a>
								<?endif?>
							</td>
							<?endif?>
						</tr>
					<?endforeach?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="text-center"><?=$pagination?></div>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <?if ($controller->allowed_crud_action('export')):?>
            <a class="btn btn-sm btn-success pull-right " href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
                <i class="glyphicon glyphicon-download"></i>
                <?=__('Export all')?>
            </a>                
            <?endif?>
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Upload CSV file')?></h3>
            </div>
            <div class="panel-body">
                <p>
                    <?=__('Please use the correct CSV format')?>
                    <br>
                    <?=__('Coupons')?>: <a href="https://mega.nz/#!V1RSSIoS!QBD0IlfKqcAuswEv18SXQ1vkbp4eUeCxpIH5sXQVskY"><?=__('download example')?>.</a>
                </p>
                <hr>
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'coupon','action'=>'import')), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
                    <div class="form-group">
                        <label for=""><?=__('import coupons')?></label>
                        <input type="file" name="csv_file_coupons" id="csv_file_coupons" class="form-control"/>
                    </div>
                        <?= FORM::button('submit', __('Upload'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'coupon','action'=>'import'))))?>
                <?= FORM::close()?>
            </div>
        </div>
    </div>
</div>