<?php defined('SYSPATH') or die('No direct script access.');

class Api_User extends Api_Controller {


    public $user = FALSE;

    public function before()
    {
        parent::before();

        $key = Core::request('apikey');

        //try authenticate the user
        if (($this->user = Auth::instance()->api_login($key))==FALSE)
        {
            $this->_error(__('Wrong Api User Key'),401);
        }
    }



} // End api