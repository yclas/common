<?php defined('SYSPATH') or die('No direct script access.');

class Pagination extends Kohana_Pagination 
{

    /**
     * Retrieves a pagination config group from the config file. One config group can
     * refer to another as its parent, which will be recursively loaded.
     *
     * @param   string  pagination config group; "default" if none given
     * @return  array   config settings
     */
    public function config_group($group = 'default')
    {
        // Load the pagination config file
        $config_file = Kohana::$config->load('pagination');

        // Initialize the $config array
        $config['group'] = (string) $group;

        // Recursively load requested config groups
        while (isset($config['group']))// AND isset($config_file->$config['group']))
        {
            // Temporarily store config group name
            $group = $config['group'];
            unset($config['group']);

            // Add config group values, not overwriting existing keys
            $config += $config_file->$group;
        }

        // Get rid of possible stray config group names
        unset($config['group']);

        // Return the merged config group settings
        return $config;
    }

	/**
	 * Title used in the links
	 */
	protected $title;
	
	/**
	 * Query parameters setter
	 * 
	 * @param	array	Query parameters to set
	 * @return	$this	Chainable as setter
	 */
	public function query_params($params = NULL)
	{
		if( ! empty($params))
		{
			Request::current()->query($params);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * sets/gets the title
	 * @param string $title
	 * @return string
	 */
	public function title($title = NULL)
	{
		if ($title !== NULL)
			$this->title = $title;
			
		return $this->title;
	}


    /**
     * Generates the full URL for a certain page.
     *
     * @param   integer  page number
     * @return  string   page URL
     */
    public function url($page = 1)
    {
        $url = mb_strtolower(parent::url($page));

        //removing the parameter rel=ajax just in case
        $url = str_replace(array('&rel=ajax','rel=ajax&','rel=ajax'), '', $url);

        return $url;
    }
	
}