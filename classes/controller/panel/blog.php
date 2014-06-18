<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Blog extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('title','created','status');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'post';

	/**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __($this->_orm_model);
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $elements = new Model_Post();
        $elements->where('id_forum','IS',NULL);

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $elements->count_all(),
        //'items_per_page' => 10// @todo from config?,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $elements = $elements->order_by('created','desc')
        ->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        if ($view === NULL)
            $view = 'oc-panel/crud/index';
        
        $this->render($view, array('elements' => $elements,'pagination'=>$pagination));
    }    
}
