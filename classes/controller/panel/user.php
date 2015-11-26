<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_User extends Auth_CrudAjax {

    protected $_filter_fields = array(   
                                        'status' => array(0=>'Inactive',1=>'Active',5=>'Spam'),
                                        'id_role' => array('type'=>'SELECT','table'=>'roles','key'=>'id_role','value'=>'name'),
                                        );


    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('name','email','id_role','logins','last_login','status');
    
    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'user';

    protected $_search_fields = array('name','email');
    
    protected $_fields_caption = array( 'id_role'       => array('model'=>'role','caption'=>'name'),
                                         );

    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_buttons_actions = array(
                                        array( 'url'   => Route::url('oc-panel', array('controller'=>'order')).'?filter__id_user=' ,
                                                'title' => 'orders',
                                                'class' => 'btn btn-xs btn-default',
                                                'icon'  => 'fa fa-credit-card'
                                                ),
                                        array( 'url'   => Route::url('oc-panel', array('controller'=>'user', 'action'=>'spam')).'/' ,
                                                'title' => 'spam',
                                                'class' => 'btn btn-xs btn-warning',
                                                'icon'  => 'glyphicon glyphicon-fire'
                                                ),
                                        );

        //for OC display ads
        if (class_exists('Model_Ad'))
        {
            array_unshift($this->_buttons_actions,   array( 'url'   => Route::url('oc-panel', array('controller'=>'ad')).'?filter__id_user=' ,
                                                            'title' => 'ads',
                                                            'class' => 'btn btn-xs btn-success',
                                                            'icon'  => 'fa fa-th'
                                                            ));
        }

        //for OE display tickets
        if (class_exists('Model_Ticket'))
        {
            array_unshift($this->_buttons_actions,   array( 'url'   => Route::url('oc-panel', array('controller'=>'support', 'action'=>'index')).'?filter__id_user=' ,
                                                            'title' => 'support',
                                                            'class' => 'btn btn-xs btn-info',
                                                            'icon'  => 'fa fa-comment'
                                                            ));
        }
    }
	

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
				if (Valid::email($form->object->email,TRUE))
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
						$form->object->seoname = $user->gen_seo_title($form->object->name);
						$form->save_object();
						Alert::set(Alert::SUCCESS, __('Item created').'. '.__('Please to see the changes delete the cache')
							.'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
							.__('Delete All').'</a>');
			
						$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
					}
				}
				else
				{
					Alert::set(Alert::ERROR, __('Invalid Email'));
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
				if (Valid::email($form->object->email,TRUE))
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
							.'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
							.__('Delete All').'</a>');
						$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
					}
				}
				else
				{
					Alert::set(Alert::ERROR, __('Invalid Email'));
				}
			}
			else
			{
				Alert::set(Alert::ERROR, __('Check form for errors'));
			}
		}
	
		return $this->render('oc-panel/pages/user/update', array('form' => $form));
	}
	
	public function action_changepass()
	{
		// only admins can change password
		if ($this->request->post() AND $this->user->id_role == Model_Role::ROLE_ADMIN)
		{
			$user = new Model_User($this->request->param('id'));
	
			if (core::post('password1')==core::post('password2'))
			{
				if(!empty(core::post('password1'))){
	
					$user->password = core::post('password1');
					$user->last_modified = Date::unix2mysql();
	
					try
					{
						$user->save();
						
						// email user with new password
						Email::content($user->email,$user->name,NULL,NULL,'password-changed',array('[USER.PWD]'=>core::post('password1')));
					}
					catch (ORM_Validation_Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}
					catch (Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}
	
					Alert::set(Alert::SUCCESS, __('Password is changed'));
				}
				else
				{
					Form::set_errors(array(__('Nothing is provided')));
				}
			}
			else
			{
				Form::set_errors(array(__('Passwords do not match')));
			}
	
		}
	
		$this->redirect(Route::url('oc-panel',array('controller'=>'user', 'action'=>'update', 'id'=>$this->request->param('id'))));
	}

    /**
     * mark user as spamer, he can no longer login
     * @return [type] [description]
     */
    public function action_spam()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $user = new Model_User($this->request->param('id'));

        if ($user->loaded())
        {
            try
            {
                $user->user_spam();
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
            HTTP::redirect(Route::url('oc-panel', array('controller'=>$this->request->controller())));
        }
        
    }

}
