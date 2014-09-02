<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_User extends Auth_Crud {

    
	
	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('name','email','logins');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'user';
	

	/**
	 * CRUD controller: CREATE
	 */
	public function action_create()
	{
		$this->template->title = __('New').' '.__($this->_orm_model);
		
		$form = new FormOrm($this->_orm_model);
		
		if ($this->request->post())
		{
			if ( $success = $form->submit() )
			{
				//check we have this email in the DB
				$user = new Model_User();
				$user = $user->where('email', '=', Kohana::$_POST_ORIG['formorm']['email'])
						->limit(1)
						->find();
				
				if ($user->loaded())
				{
					Alert::set(Alert::ERROR, __('A user with the email you specified already exists'));
				}
				else 
				{
					$form->save_object();
					Alert::set(Alert::SUCCESS, __('Item created').'. '.__('Please to see the changes delete the cache')
						.'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
						.__('Delete All').'</a>');
		
					$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
				}
			}
			else 
			{
				Alert::set(Alert::ERROR, __('Check form for errors'));
			}
		}
	
		return $this->render('oc-panel/crud/create', array('form' => $form));
	}

	/**
	 * CRUD controller: UPDATE
	 */
	public function action_update()
	{
		$this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');
	
		$form = new FormOrm($this->_orm_model,$this->request->param('id'));
	
		if ($this->request->post())
		{
			if ( $success = $form->submit() )
			{
				//check we have this email in the DB
				$user = new Model_User();
				$user = $user->where('email', '=', Kohana::$_POST_ORIG['formorm']['email'])
                        ->where('id_user','!=',$this->request->param('id'))
						->limit(1)
						->find();
				
				if ($user->loaded())
				{
					Alert::set(Alert::ERROR, __('A user with the email you specified already exists'));
				}
				else
				{
					$form->save_object();
					Alert::set(Alert::SUCCESS, __('Item updated').'. '.__('Please to see the changes delete the cache')
						.'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
						.__('Delete All').'</a>');
					$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
				}
			}
			else
			{
				Alert::set(Alert::ERROR, __('Check form for errors'));
			}
		}
	
		return $this->render('oc-panel/crud/update', array('form' => $form));
	}	

}
