<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Site extends Kohana_Controller {

	public function action_index()
	{
        //in case private_site is not enabled go back to the home.
        if (Core::config('general.private_site')!='1')
            HTTP::redirect(Route::url('default'));

        $this->response->status(403);
        $this->response->headers('Retry-After','555'); // header('Retry-After: 555');
 
        $this->response->body(View::factory('pages/error/403')->render());
	}

} // End 
