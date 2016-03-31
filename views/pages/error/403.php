<?php defined('SYSPATH') or die('No direct script access.');?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=Core::config('general.site_name')?> - <?=__('Private Site')?></title>

    <link href="//cdn.jsdelivr.net/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

  </head>

  <body>
    <style type="text/css">
    /* Space out content a bit */
    body {
      padding-top: 20px;
      padding-bottom: 20px;
    }

    /* Customize container */
    @media (min-width: 768px) {
      .container {
        max-width: 730px;
      }
    }
    .container-narrow > hr {
      margin: 30px 0;
    }

    /* Main marketing message and sign up button */
    .jumbotron {
      text-align: center;
      border-bottom: 1px solid #e5e5e5;
    }
    .jumbotron .btn {
      padding: 14px 24px;
      font-size: 21px;
    }

    /* Responsive: Portrait tablets and up */
    @media screen and (min-width: 768px) {
      /* Remove the bottom border on the jumbotron for visual effect */
      .jumbotron {
        border-bottom: 0;
      }
    }
    </style>

    <div class="container">

      <div class="jumbotron">
        
        <h1><?=Core::config('general.site_name')?></h1>
        <h2><?=__('Private site!!!')?></h2>
        
      </div>

      <form class="well form-horizontal auth" method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">         
        <?=Form::errors()?>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('Email')?></label>
            <div class="col-md-5 col-sm-6">
                <input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
            </div>
        </div>
         
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('Password')?></label>
            <div class="col-md-5 col-sm-6">
                <input class="form-control" type="password" name="password" placeholder="<?=__('Password')?>">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" checked="checked"><?=__('Remember me')?>
                    </label>
                </div>
            </div>
        </div>
        <div class="page-header"></div>     
        <div class="col-sm-offset-2">
            <button type="submit" class="btn btn-primary">
                <i class="glyphicon glyphicon-user glyphicon"></i> <?=__('Login')?>
            </button>
        </div>
        <?=Form::CSRF('login')?>
    </form>         


    </div> 

  </body>
</html>
