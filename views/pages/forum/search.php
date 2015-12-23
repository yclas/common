<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <?if (!Auth::instance()->logged_in()):?>
        <a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal" 
            href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
    <?else:?>
        <a class="btn btn-success pull-right" href="<?=Route::url('forum-new')?>">
    <?endif?>
        <?=__('New Topic')?></a>
    <?=View::factory('pages/forum/search-form')?>
    <h1 class="forum-title pull-left"><?=__('Search')?> <?=core::get('search')?></h1>
    <div class="clearfix"></div>
</div>

<?if ($topics->count()>0):?>
    <table class="table table-hover" id="task-table">
        <tbody>
            <?foreach($topics as $topic):?>
                <?
                if (is_numeric($topic->id_post_parent))
                {
                    $title      = $topic->parent->title;
                    $seotitle   = $topic->parent->seotitle;
                }
                else
                {
                    $title      = $topic->title;
                    $seotitle   = $topic->seotitle;
                }
                    
                ?>
                <tr class="success">
                    <td><a title="<?=HTML::chars($title)?>" href="<?=Route::url('forum-topic', array('forum'=>$topic->forum->seoname,'seotitle'=>$seotitle))?>"><?=mb_strtoupper($topic->title);?></a></td>
                    <td width="10%"><a title="<?=HTML::chars($topic->forum->name)?>" href="<?=Route::url('forum-list', array('forum'=>$topic->forum->seoname))?>"><?=$topic->forum->name?></a></td>
                    <td width="10%"><span class="label label-info pull-right"><?=Date::format($topic->created, core::config('general.date_format'))?></span></td>
                    <?if (Auth::instance()->logged_in()):?>
                        <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
                            <td width="10%">
                                <a class="label label-warning" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                                    <span class="icon-edit icon-white glyphicon glyphicon-edit"></span>
                                </a>
                            </td>
                        <?endif?>
                    <?endif?>
                </tr>
            <?endforeach?>
        </tbody>
    </table>
<?else:?>
    <h2><?=__('Nothing found, sorry!')?></h2>
    <p><?=__('You can try a new search or publish a new topic ;)')?></p>
<?endif?>