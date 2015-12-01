<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Custom menu')?></h1>  
    <p><a href="https://docs.yclas.com/modify-top-menu/" target="_blank"><?=__('Read more')?></a></p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Create Menu Item')?></h3>
            </div>
            <div class="panel-body">
                <div class="btn-group btn-primary pull-right">
                    <button class="btn btn-primary"><?=__('Menu type')?></button>
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="menu_type">
                        <!-- dropdown menu links -->
                        <li><a class="custom"><?=__('Custom')?></a></li>
                        <li><a class="categories"><?=__('Categories')?></a></li>
                        <li><a class="default"><?=__('Default')?></a></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
                <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'new'))?>">
                <!-- drop down selector -->
                <div class="form-group" style="display:none;" id="categories-group">
                    <?= FORM::label('category', __('Category'), array('class'=>'control-label col-sm-1', 'for'=>'category' ))?> 
                    <div class="col-sm-4">
                    <div class="accordion">
                
                    <?function lili3($item, $key,$cats){?>
                        <div class="accordion-group">
                            <div class="accordion-heading"> 
                
                                <?if (count($item)>0):?>
                                    <div class="radio">
                                    <label>
                                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" data-name="radio_<?=$cats[$key]['name']?>" class="menu_category"  value="<?=$cats[$key]['id']?>"> 
                                        <a class="btn btn-primary btn-xs" data-toggle="collapse" type="button"  
                                            data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                                            <i class=" glyphicon   glyphicon-plus"></i> <?=$cats[$key]['name']?>
                                        </a>
                                    </label>
                                    </div>
                
                                <?else:?>
                                    <div class="radio">
                                    <label>
                                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" data-name="radio_<?=$cats[$key]['name']?>" class="menu_category"  value="<?=$cats[$key]['id']?>"> 
                
                                        <a class="btn btn-xs btn-primary" data-toggle="collapse" type="button"  
                                            data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                                            <?=$cats[$key]['name']?>
                                        </a>
                                    </label>
                                    </div>
                                <?endif?>
                            </div>
                
                            <?if (count($item)>0):?>
                                <div id="acc_<?=$cats[$key]['seoname']?>" 
                                    class="accordion-body collapse <?=($cats[$key]['seoname']==Core::get('category'))?'in':''?>">
                                    <div class="accordion-inner">
                                        <? if (is_array($item)) array_walk($item, 'lili3', $cats);?>
                                    </div>
                                </div>
                            <?endif?>
                
                        </div>
                    <?}array_walk($order_categories, 'lili3',$categories);?>
                
                    </div>
                    </div>
                </div>
                
                <div class="form-group"  id="default-group" style="display:none;">
                    <?= FORM::label('default_links_label', __('Default links'), array('class'=>'control-label col-sm-1', 'for'=>'default_links' ))?>
                    <div class="col-sm-4"> 
                        <div class="accordion" >
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_home"  name="home" data-url="" data-icon="glyphicon-home glyphicon" value="<?=__('Home')?>">    
                                        <a class="btn btn-primary btn-xs" type="button"  >                    
                                             <?=__('Home')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_listing" name="listing" data-url="all" data-icon="glyphicon glyphicon-list" value="<?=__('Listing')?>">
                                        <a class="btn btn-primary btn-xs" type="button"  >                    
                                             <?=__('Listing')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_listing" name="profiles" data-url="user" data-icon="fa fa-users" value="<?=__('Profiles')?>">
                                        <a class="btn btn-primary btn-xs" type="button"  >                    
                                             <?=__('Profiles')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_search" name="search" data-url="<?=URL::title(__('search'))?>.html" data-icon="glyphicon glyphicon-search" value="search">
                                        <a class="btn btn-primary btn-xs" type="button"  >                    
                                             <?=__('Search')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_contact" name="contact" data-url="<?=URL::title(__('contact'))?>.html" data-icon="glyphicon glyphicon-envelope" value="contact">
                                        <a class="btn btn-primary btn-xs" type="button"  >                    
                                             <?=__('Contact')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_rss" name="rss" data-url="rss.xml" data-icon="glyphicon glyphicon-signal" value="rss">
                                        <a class="btn btn-primary btn-xs" type="button"  >                    
                                             <?=__('rss')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_map" name="map" data-url="map.html" data-icon="glyphicon glyphicon-globe" value="map">
                                        <a class="btn btn-primary btn-xs" type="button"  >                    
                                             <?=__('map')?>
                                        </a>
                                    </label>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-1"><?=__('Title')?></label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="title" value="<?=Core::post('title')?>" placeholder="<?=__('Title')?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-1"><?=__('Url')?></label>
                    <div class="col-sm-4">
                        <input class="form-control" type="url" id="url" name="url" value="<?=Core::post('Url')?>" placeholder="http://somedomain.com" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= FORM::label('target', __('Target'), array('class'=>'control-label col-sm-1', 'for'=>'target' ))?>
                    <div class="col-sm-4">
                        <select name="target" id="target" class="form-control" REQUIRED>
                            <option>_self</option>
                            <option>_blank</option>
                            <option>_parent</option>
                            <option>_top</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-1"><a target="_blank" href="http://getbootstrap.com/components/#glyphicons"><?=__('Icon')?></a></label>
                    <div class="col-sm-4">
                        <input class="form-control icon-picker" type="text" name="icon" value="<?=Core::post('icon')?>">
                    </div>
                </div>
                
                <div class="form-actions">
                
                <button type="submit" class="btn btn-primary"><?=__('Save')?></button>
                </div>
                
                
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <ol class='plholder' id="ol_1" data-id="1">
                    <?if (is_array($menu)):?>
                        <?foreach($menu as $key=>$data):?>
                            <li data-id="<?=$key?>" id="<?=$key?>">
                                <div class="drag-item">
                                    <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                    <div class="drag-name">
                                        <?if($data['icon']!=''):?><i class="<?=$data['icon']?>"></i><?endif?>
                                        <span class="label label-info "><?=$data['title']?></span>
                                        <?=$data['url']?> (<?=$data['target']?>)
                                    </div>
                                    <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                        href="<?=Route::url('oc-panel', array('controller'=>'menu','action'=>'update','id'=>$key))?>">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a 
                                        href="<?=Route::url('oc-panel', array('controller'=> 'menu', 'action'=>'delete','id'=>$key))?>"
                                        class="drag-action index-delete" 
                                        title="<?=__('Are you sure you want to delete?')?>" 
                                        data-id="<?=$key?>" 
                                        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                        data-btnCancelLabel="<?=__('No way!')?>">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                </div>
                            </li>
                        <?endforeach?>
                    <?endif?>
                </ol><!--ol_1-->
                <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'saveorder'))?>'></span>
            </div>
        </div>
    </div>
</div>