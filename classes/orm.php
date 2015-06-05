<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for ORM
 *
 * @package    OC
 * @category   Model
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class ORM extends Kohana_ORM {

	/**
	 * Name of the database to use
	 *
	 * @access	protected
	 * @var		string	$_db default [default]
	 */
	protected $_db = 'default';


	/**
	 * 
	 * formo definitions
	 * 
	 */
	public function form_setup($form){}


    /**
     * Insert a new object to the database - Overwrite!
     * @param  Validation $validation Validation object
     * @throws Kohana_Exception
     * @return ORM
     */
    public function create(Validation $validation = NULL)
    {
        //Hack to use the PHP date time instead of the MySQL for created
        $cols = $this->list_columns();
        
        // the column created exists and we didnt pass any value before
        if (isset($cols['created']) AND !array_key_exists('created', $this->_changed))
        {
            //add the value, forcing it so wont use the DB default ;)
            $this->set('created',Date::unix2mysql());
        }

        return parent::create($validation);
    }

    /**
     * Count the number of records in the table. Modified does not reset!
     * @param bool $reset optional to reset the query
     * @return integer
     */
    public function count_all($reset = FALSE)
    {
        $selects = array();

        foreach ($this->_db_pending as $key => $method)
        {
            if ($method['name'] == 'select')
            {
                // Ignore any selected columns for now
                $selects[] = $method;
                unset($this->_db_pending[$key]);
            }
        }

        if ( ! empty($this->_load_with))
        {
            foreach ($this->_load_with as $alias)
            {
                // Bind relationship
                $this->with($alias);
            }
        }

        $this->_build(Database::SELECT);

        $records = $this->_db_builder->from(array($this->_table_name, $this->_object_name))
            ->select(array(DB::expr('COUNT('.$this->_db->quote_column($this->_object_name.'.'.$this->_primary_key).')'), 'records_found'))
            ->execute($this->_db)
            ->get('records_found');

        // Add back in selected columns
        $this->_db_pending += $selects;

        //Edited!
        if ($reset === TRUE)
            $this->reset();

        // Return the total number of records in a table
        return (int) $records;
    }


    /**
     * pagination for result set using return link for headers
     * @param  array $params        received on the api request
     * @param  integer $count          total results of the query
     * @param  route $route_params   the route used in the view
     * @return string                 link:header / false not done
     */
    public function api_pagination($params, $count, $route_params)
    {
        //page X
        if (isset($params['page']) AND is_numeric($params['page']))
        {
            $items_per_page = ( isset($params['items_per_page']) AND is_numeric($params['items_per_page']) )?$params['items_per_page']:10;

            $pagination = Pagination::factory(array(
                    'view'           => 'api-pagination',
                    'total_items'    => $count,
                    'items_per_page' => $items_per_page
            ))->route_params($route_params);

            $this->limit($pagination->items_per_page)->offset($pagination->offset);

            return $pagination->render();
        }

        return FALSE;
    }

    /**
     * filters the ORM using the api params, see Api_Controller -> _init_filter_params
     * @param  array $params parameters to try filter
     * @return Model_ORM         
     */
    public function api_filter($params)
    {
        //filter results by param, verify field exists and has a value
        foreach ($params as $key => $field) 
        {
            //add to where in case in columns
            if(in_array($field['field'],(array_keys($this->table_columns()))) AND isset($field['value']) AND !empty($field['value']))
                $this->where($field['field'],$field['operator'],$field['value']);

        }

        return $this;
    }

    /**
     * sorts the ORM results using the api params, see Api_Controller -> _init_sort
     * @param  array $sort
     * @return Model_ORM         
     */
    public function api_sort($sort)
    {
        //sorting results by param, verify field exists
        foreach ($sort as $field => $direction) 
        {
            if(in_array($field,(array_keys($this->table_columns()))))
                $this->order_by($field,$direction);
        }

        return $this;
    }

}