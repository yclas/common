<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=__($name)?>">
	<h1><?=__('Update')?> <?=ucfirst(__($name))?></h1>
</div>
<?=$form->render()?>
<?if($name == "location") :?>
    <div class="row">
        <div class="col-md-10">
          <div class="page-header">
            <h1><?=__('Upload location icon')?></h1>
          </div>
          
          <?if (( $icon_src = $location->get_icon() )!==FALSE ):?>
          <div class="row">
              <div class="col-md-3">
                  <a class="thumbnail">
                      <img src="<?=$icon_src?>" class="img-rounded" alt="<?=__('Location icon')?>" height='200px'>
                  </a>
              </div>
          </div>
          <?endif?>
          <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'icon','id'=>$form->object->id_location))?>">         
                  <?=Form::errors()?>  
                
                <div class="form-group">
                  <div class="col-sm-4">
                      <?= FORM::label('location_icon', __('Select from files'), array('for'=>'location_icon'))?>
                      <input type="file" name="location_icon" class="form-control" id="location_icon" />
                  </div>
                </div>
                
                    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button> 
                    <?if (( $icon_src = $location->get_icon() )!==FALSE ):?>
                      <button type="submit"
                         class="btn btn-danger index-delete index-delete-inline"
                         onclick="return confirm('<?=__('Delete icon?')?>');" 
                         type="submit" 
                         name="icon_delete"
                         value="1" 
                         title="<?=__('Delete icon')?>">
                        <?=__('Delete icon')?>
                      </button>
                    <?endif?>
          </form>
        </div><!--end col-md-10-->
    </div>
<?endif?>