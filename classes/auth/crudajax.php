<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CRUD Ajax controller for the admin interface.
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 * @see https://github.com/colinbm/kohana-formmanager
 * @see http://www.jquery-bootgrid.com/
 */

class Auth_CrudAjax extends Auth_Crud
{

    /**
     * @var $_search_fields ORM fields we allow to search, in case not specified will search in all the fields, so you can limit the search using this array
     */
    protected $_search_fields = array();

	/**
	 *
	 * Loads a basic list info
	 * @param string $view template to render 
	 */
	public function action_index($view = NULL)
	{
		$this->template->title = __($this->_orm_model);
        $this->template->scripts['footer'][] = 'js/jquery.bootgrid.min.js';
        $this->template->scripts['footer'][] = 'js/query.bootgrid.fa.min.js';
        $this->template->scripts['footer'][] = Route::url($this->_route_name, array('controller'=> Request::current()->controller(), 'action'=>'bootgrid'));
        $this->template->styles = array('css/jquery.bootgrid.min.css' => 'screen');
		
		$elements = ORM::Factory($this->_orm_model);//->find_all();

		if ($view === NULL)
			$view = 'oc-panel/crud/indexajax';
		
		$this->render($view, array('elements' => $elements));
	}

    /**
     * gets the info used from index
     * @return string json
     */
    public function action_ajax()
    {
        $elements = ORM::Factory($this->_orm_model);
        
        //in case we did not specify what to show
        if (empty($this->_search_fields))
            $this->_search_fields = array_keys($elements->list_columns());

        //search searchPhrase: from an array specified in the controller. If none search does not appear. do in bootdrig action
        if (Core::post('searchPhrase')!==NULL AND count($this->_search_fields) > 0)
        {
            foreach ($this->_search_fields as $field) 
                $elements->or_where($field,'LIKE','%'.Core::post('searchPhrase').'%');
        }

        //sort by sort[group_name]:asc
        if (Core::post('sort')!==NULL)
        {
            $sort = Core::post('sort');
            $field      = key($sort);
            $direction  = current($sort);
            if(in_array($field,(array_keys($elements->table_columns()))))
                $elements->order_by($field,$direction);
        }

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'current_page'   => array('page'=>Core::post('current',1)),
                    'total_items'    => $elements->count_all(),
                    'items_per_page' => Core::post('rowCount',10)
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $elements = $elements->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $rows = array();
        foreach ($elements as $element) 
        {
            foreach($this->_index_fields as $field)
                $result[$field] = Text::limit_chars(strip_tags($element->$field));

            $rows[]  = $result;
            $result  = array();
        }

        //the json returned
        $result = array( 'current'   => $pagination->current_page,
                         'rowCount'  => $pagination->items_per_page,
                         'rows'      => $rows,
                         'total'     => $pagination->total_items
                        );
        
        $this->auto_render = FALSE;
        //$this->response->headers('Content-type','application/javascript');//why the heck doesnt work with this???
        $this->template = View::factory('js');
        $this->template->content = json_encode($result);
    }

    /**
     * returns the JS with the config of bootgrid specific for this model
     * @return string JS/jquery
     */
    public function action_bootgrid()
    {
        $element = ORM::Factory($this->_orm_model);

        $this->auto_render = FALSE;
        $this->response->headers('Content-type','application/javascript');
        $this->template = View::factory('js');
        $data = array(  'element'   => $element,
                        'route'     => $this->_route_name,
                        'controller'=> $this);  
        $this->template->content = View::factory('oc-panel/crud/bootgrid',$data)->render();
    }

}