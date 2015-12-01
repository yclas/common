<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Crontab extends Auth_CrudAjax {

    protected $_orm_model = 'crontab';


    protected $_index_fields = array('name','period','callback','date_finished','date_next','running','active');

    protected $_search_fields = array('name','period','callback');

    protected $_filter_fields = array(  'running'       => array(0,1), 
                                        'active'        => array(0,1) ,
                                        );

    protected $_extra_info_view = 'oc-panel/pages/cron/index';

}