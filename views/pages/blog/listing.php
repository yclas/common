<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="pull-right" action="<?=Route::URL('blog')?>" method="get">
    <button class="btn btn-default pull-right" type="submit" value="<?=__('Search')?>"><?=__('Search')?></button>
    <div class="pull-right">&nbsp;</div>
    <div class="pull-right">
        <input type="text" class="form-control" placeholder="<?=__('Search')?>" type="search" value="<?=core::get('search')?>" name="search" />
    </div>
</form>

<div class="page-header">
    <h1><?=Core::config('general.site_name')?> <?=__('Blog')?></h1>
</div>

<?if(count($posts)):?>
    <?foreach($posts as $post ):?>
    <article class="list well clearfix">
    	<h2>
    		<a title="<?=HTML::chars($post->title)?>" href="<?=Route::url('blog', array('seotitle'=>$post->seotitle))?>"> <?=$post->title; ?></a>
    	</h2>
    	
    	<?=Date::format($post->created, core::config('general.date_format'))?>
        
    	<div class="text-description blog-description"><?=Text::truncate_html($post->description, 255, NULL)?></div>
    	
	    <a title="<?=HTML::chars($post->title)?>" href="<?=Route::url('blog', array('seotitle'=>$post->seotitle))?>"><i class="glyphicon glyphicon-share"></i><?=__('Read more')?></a>
    	<?if ($user !== NULL AND $user!=FALSE AND $user->id_role == Model_Role::ROLE_ADMIN):?>
    		<br />
			<a href="<?=Route::url('oc-panel', array('controller'=>'blog','action'=>'update','id'=>$post->id_post))?>"><?=__("Edit");?></a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'blog','action'=>'delete','id'=>$post->id_post))?>" 
				onclick="return confirm('<?=__('Delete?')?>');"><?=__("Delete");?></a>
        <?endif?>
    </article>
    <?endforeach?>
    <?=$pagination?>
<?else:?>
<!-- Case when we dont have ads for specific category / location -->
	<div class="page-header">
	   <h3><?=__('We do not have any blog posts')?></h3>
    </div>
<?endif?>
