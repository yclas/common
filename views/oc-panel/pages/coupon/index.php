<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="list-inline pull-right">
    <li>
        <form class="form-inline" method="get" action="<?=URL::current();?>">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="<?=__('Coupon name')?>" value="<?=core::get('name')?>">
            </div>
            <button type="submit" class="btn"><?=__('Search')?></button>
        </form>
    </li>
    <li>
        <a class="btn btn-success" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'bulk')) ?>">
            <i class="glyphicon glyphicon-list-alt"></i>&nbsp; <?=__('Bulk')?>
        </a>
    </li>
    <li>
        <a class="btn btn-primary ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create'))?>">
            <i class="fa fa-plus-circle"></i>&nbsp; <?=__('New')?>
        </a>
    </li>
</ul>

<h1 class="page-header page-title">
    <?=Text::ucfirst(__($name))?>
    <a target="_blank" href="https://docs.yclas.com/how-to-use-coupon-system/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>
<hr>

<?if (Theme::get('premium')!=1):?>
    <div class="alert alert-info fade in">
        <p>
            <strong><?=__('Heads Up!')?></strong> 
            <?=__('only available with premium themes!').'<br/>'.__('Upgrade your Open Classifieds site to activate this feature.')?>
        </p>
        <p>
            <a class="btn btn-info" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>">
                <?=__('Browse Themes')?>
            </a>
        </p>
    </div>
<?endif?>

<div class="panel panel-default">
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
                    <th><?=__('Name')?></th>
                    <th><?=__('Product')?></th>
                    <th><?=__('Discount')?></th>
                    <th><?=__('Number Coupons')?></th>
                    <th><?=__('Valid until')?></th>
                    <th class="coupon_created_label"><?=__('Created')?></th>
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
                        <td class="coupon_created"><?=Date::format($element->created, core::config('general.date_format'))?></td>

						<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
						<td class="nowrap">
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
    <div class="panel-footer text-right">
        <?if ($controller->allowed_crud_action('export')):?>
            <a class="btn btn-success" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
                <i class="glyphicon glyphicon-download"></i>
                &nbsp;<?=__('Export all')?>
            </a>
        <?endif?>
    </div>
</div>

<div class="text-center"><?=$pagination?></div>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
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