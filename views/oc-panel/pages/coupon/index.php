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
	<h1><?=Text::ucfirst(__($name))?></h1>
	<p><a href="https://docs.yclas.com/how-to-use-coupon-system/" target="_blank"><?=__('Read more')?></a></p>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>

                        <th><?=__('Name')?></th>
                        <th><?=__('Product')?></th>
                        <th><?=__('Discount')?></th>
                        <th><?=__('Number Coupons')?></th>
                        <th><?=__('Valid until')?></th>
                        <th><?=__('Created')?></th>
						<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
						<th><?=__('Actions') ?></th>
						<?endif?>
					</tr>
				</thead>
				<tbody>
					<?foreach($elements as $element):?>
						<tr id="tr<?=$element->pk()?>">
                            <td><?=$element->name?></td>
                            <td>
                                <?if (isset($element->produt)):?>
                                    <?=$element->product->title?>
                                <?elseif(method_exists('Model_Order','product_desc')):?>
                                    <?=(($product_desc = Model_Order::product_desc($element->id_product)) == '') ? __('Any') : $product_desc?>
                                <?else:?>
                                    <?=$element->id_product?>
                                <?endif?>
                            </td>
                            <td>
                                <?=($element->discount_amount==0)?round($element->discount_percentage,0).'%':i18n::money_format($element->discount_amount)?>
                            </td>
                            <td><?=$element->number_coupons?></td>
                            <td><?=Date::format($element->valid_date, core::config('general.date_format'))?></td>
                            <td><?=Date::format($element->created, core::config('general.date_format'))?></td>

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
                    <?=__('Please use the correct CSV format')?>, <?=__('limited to 10.000 at a time')?>, <?=__('1 MB file')?>.
                    <br>
                    <?=__('Coupons')?>: <a href="https://mega.nz/#!V1RSSIoS!QBD0IlfKqcAuswEv18SXQ1vkbp4eUeCxpIH5sXQVskY"><?=__('download example')?>.</a>
                </p>
                <hr>
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'coupon','action'=>'import')), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
                    <div class="form-group">
                        <label for=""><?=__('import coupons')?></label>
                        <input type="file" name="csv_file_coupons" id="csv_file_coupons" class="form-control"/>
                    </div>
                        <?= FORM::button('submit', __('Upload'), array('type'=>'submit','id'=>'csv_upload', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'coupon','action'=>'import'))))?>
                <?= FORM::close()?>
            </div>
        </div>
    </div>
</div>