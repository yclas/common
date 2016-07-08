<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal auth" method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">         
    <?=Form::errors()?>
    <div class="form-group">
        <label class="col-sm-2 control-label"><?=_e('Email')?></label>
        <div class="col-md-5 col-sm-6">
            <input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
        </div>
    </div>
     
    <div class="form-group">
        <label class="col-sm-2 control-label"><?=_e('Password')?></label>
        <div class="col-md-5 col-sm-6">
            <input class="form-control" type="password" name="password" placeholder="<?=__('Password')?>">
            <p class="help-block">
          		<small><a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'forgot'))?>#forgot-modal">
          			<?=_e('Forgot password?')?>
          		</a></small>
            </p>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" checked="checked"><?=_e('Remember me')?>
                </label>
            </div>
        </div>
    </div>
    <div class="page-header"></div>     
    <div class="col-sm-offset-2">
        <button type="submit" class="btn btn-primary">
            <i class="glyphicon glyphicon-user glyphicon"></i> <?=_e('Login')?>
        </button>
      	<a class="btn btn-default" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
        	<?=_e('Register')?>
        </a>
    </div>
    <?=Form::redirect()?>
    <?=Form::CSRF('login')?>
    <?=View::factory('pages/auth/social')?>
</form>      	