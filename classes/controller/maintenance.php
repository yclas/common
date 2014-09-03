<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Maintenance extends Kohana_Controller {

	public function action_index()
	{
        //in case there's no maintenance go back to the home.
        if (core::config('general.maintenance')!=1)
            HTTP::redirect(Route::url('default'));

        $this->response->status(503);
        // Notify bots & crawlers to consider the site as only temporary unavailable
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        // Notify bots & crawlers to come back in 15 minutes, i.e. 900 seconds
        header('Retry-After: 900');
        $this->response->body(View::factory('pages/error/503')->render());
	}


} // End 
