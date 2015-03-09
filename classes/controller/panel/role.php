<?php 

class Controller_Panel_Role extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_role','name');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'role';

    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __($this->_orm_model);
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $elements = ORM::Factory($this->_orm_model);//->find_all();
        //do not display admin!
        $elements = $elements->where('id_role','!=',Model_Role::ROLE_ADMIN);
        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $elements->count_all(),
        //'items_per_page' => 10// @todo from config?,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $elements = $elements->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        if ($view === NULL)
            $view = 'oc-panel/crud/index';
        
        $this->render($view, array('elements' => $elements,'pagination'=>$pagination));
    }

	/**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $id_role = $this->request->param('id');

        //we do not allow modify the admin
        if ($id_role == Model_Role::ROLE_ADMIN)
        {
            Alert::set(Alert::WARNING, __('Admin Role can not be modified!'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'role')));
        }

        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$id_role;
    
        $role = new Model_Role($id_role);
        
        if ($this->request->post() AND $role->loaded())
        {
            //delete all the access
            DB::delete('access')->where('id_role','=',$role->id_role)->execute();
            //set all the access where post = on
            foreach ($_POST as $key => $value) 
            {
                if ($value == 'on')
                {
                   DB::insert('access', array('id_role','access' ))->values(array($role->id_role, str_replace('|', '.', $key)))->execute();
                }
            }

            //saving the role params
            $role->name = core::post('name');
            $role->description = core::post('description');
            $role->save();            

            Alert::set(Alert::SUCCESS, __('Item updated'));
           
            $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
           
        }

        //getting controllers actions
        $controllers = Model_Access::list_controllers();

        //get all the access this user has
        $query = DB::select('access')
                        ->from('access')
                        ->where('id_role','=',$id_role)                        
                        ->execute();

        $access_in_use = array_keys($query->as_array('access'));
    
   // d(in_array('access_index',$access_in_use));
//d($access_in_use);

        return $this->render('oc-panel/pages/role/update', array('role' => $role, 
                                                                'controllers' => $controllers,
                                                                'access_in_use'=>$access_in_use));
    }
}
