<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal register"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>">         
          <?=Form::errors()?>
          
          <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('Name')?></label>
            <div class="col-md-5 col-sm-6">
              <input class="form-control" type="text" name="name" value="<?=Request::current()->post('name')?>" placeholder="<?=__('Name')?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('Email')?></label>
            <div class="col-md-5 col-sm-6">
              <input
                class="form-control" 
                type="text" 
                name="email" 
                value="<?=Request::current()->post('email')?>" 
                placeholder="<?=__('Email')?>" 
                data-domain='<?=(core::config('general.email_domains') != '') ? json_encode(explode(',', core::config('general.email_domains'))) : ''?>' 
                data-error="<?=__('Email must contain a valid email domain')?>"
              >
            </div>
          </div>
     
          <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('New password')?></label>
            <div class="col-md-5 col-sm-6">
            <input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('Repeat password')?></label>
            <div class="col-md-5 col-sm-6">
            <input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
              <p class="help-block">
              		<?=__('Type your password twice')?>
              </p>
            </div>
          </div>
            <div class="page-header"></div> 
            <div class="col-sm-offset-2">
              	<a class="btn btn-default"  data-dismiss="modal" data-toggle="modal"  href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
    				<i class="glyphicon glyphicon-user"></i> 
    				<?=__('Login')?>
    			</a>
            <button type="submit" class="btn btn-primary"><?=__('Register')?></button>
            </div>
          <?=Form::redirect()?>
          <?=Form::CSRF('register')?>
          <?=View::factory('pages/auth/social')?>
</form>      	
