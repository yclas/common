<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">	
	<h1><?=__('CMS Images')?></h1>
	<p><a href="https://docs.yclas.com/how-to-manage-uploaded-images/" target="_blank"><?=__('Read more')?></a></p>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<th></th>
					<th><?=__('Image')?></th>
					<th><?=__('URL')?></th>
					<th></th>
				</thead>
				<tbody>
					<?foreach ($images as $key => $image):?>
						<tr id="tr<?=$key?>">
							<td width="5%">
								<div style="max-width:200px;">
									<a class="thumbnail" href="<?=$image['url']?>" target="_blank"><img src="<?=$image['url']?>"></a>
								</div>
							</td>
							<td><?=$image['name']?></td>
							<td><?=$image['url']?></td>
							<td width="5%">
								<a 
									href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'delete'))?>?name=<?=$image['name']?>" 
									class="btn btn-danger index-delete" 
									title="<?=__('Are you sure you want to delete?')?>" 
									data-id="tr<?=$key?>" 
									data-btnOkLabel="<?=__('Yes, definitely!')?>" 
									data-btnCancelLabel="<?=__('No way!')?>">
									<i class="glyphicon glyphicon-trash"></i>
								</a>
							</td>
						</tr>
					<?endforeach?>
				</tbody>
			</table>
		</div>
	</div>
</div>
