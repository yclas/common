<?php defined('SYSPATH') or die('No direct script access.');
/**
 * theme functionality
 *
 * @package    OC
 * @category   Theme
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class OC_Theme {
    
    
    public static $theme        = 'default';
    public static $parent_theme = NULL; //used for child themes
    public static $skin         = ''; //skin that the theme is using, used in premium themes
    private static $views_path  = 'views';
    public  static $scripts     = array();
    public  static $styles      = array();
    


    /**
     * returns the JS scripts to include in the view with the tag
     * @param  array $scripts 
     * @param  string $type    placeholder
     * @param  string $theme   
     * @return string          HTML
     */
    public static function scripts($scripts, $type = 'header' , $theme = NULL)
    {
        
        if ($theme === NULL)
            $theme = self::$theme;

        $ret = '';
    
        if (isset($scripts[$type])===TRUE)
        {

            if (Kohana::$environment == Kohana::DEVELOPMENT OR Core::config('general.minify') == FALSE)
            {
                //for each type (header/footer etc) we print the script tag
                foreach($scripts[$type] as $file)
                {
                    $file = self::public_path($file, $theme);
               
                    if ($file !== FALSE)
                        $ret .= HTML::script($file, NULL, TRUE);
                }
            }
            //only minify in production or stagging OR if specfied
            else
            {
                $files = array();

                foreach($scripts[$type] as $file)
                {
                    //not external file we need the public link
                    if (!Valid::url($file))
                    {
                        $files[] = $file;
                    }
                    //externals do nothing...
                    else
                        $ret .= HTML::script($file, NULL, TRUE); 
                }

                //name for the minify js file
                $js_minified_name = URL::title('minified-'.str_replace('js', '', implode('-',$files)) ).'.js';

                //check if file exists.
                $file_name = self::theme_folder($theme).'/js/'.$js_minified_name;

                //only generate if file doesnt exists or older than 1 week
                if (!file_exists($file_name) OR (time() > strtotime('+1 week',filemtime($file_name))) )
                {
                    $min = '';
                    require_once Kohana::find_file('vendor', 'minify/jsmin','php');
                    //getting the content form files
                    foreach ($files as $file) 
                    {
                        $file = self::file_path($file,$theme);
                        if ($file !== FALSE)
                            $min.=file_get_contents($file);
                    }

                    File::write($file_name,JSMin::minify($min));
                }

                $ret .= HTML::script(self::public_path('js/'.$js_minified_name,$theme), NULL, TRUE);

            }
        }
        return $ret;
    }

    
    /**
     * merges and minifies the styles
     * @param  array $styles 
     * @param  string $theme  
     * @return string         HTML
     */
    public static function styles($styles , $theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        $ret = '';

        if (Kohana::$environment == Kohana::DEVELOPMENT OR Core::config('general.minify') == FALSE)
        {   
            //for each style we add a HTML tag to include the CSS
            foreach($styles as $file => $type)
            {
                $file = self::public_path($file, $theme);
                if ($file !== FALSE)
                    $ret .= HTML::style($file, array('media' => $type));
            }
        }
        //only minify in production or stagging
        else
        {
        
            $files = array();

            foreach($styles as $file => $type)
            {            
                //not external file we need the public link
                if (!Valid::url($file))
                {
                    $files[] = $file;
                }
                //externals do nothing...
                else
                    $ret .= HTML::style($file, array('media' => $type));
            }

            //name for the minify js file
            $css_minified_name = URL::title('minified-'.str_replace('css', '', implode('-',$files)) ).'.css';

            //check if file exists.
            $file_name = self::theme_folder($theme).'/css/'.$css_minified_name;

            //only generate if file doesnt exists or older than 1 week
            if (!file_exists($file_name) OR (time() > strtotime('+1 week',filemtime($file_name))) )
            {
                $min = '';
                require_once Kohana::find_file('vendor', 'minify/css','php');
                //getting the content from files
                foreach ($files as $file) 
                {
                    $file = self::file_path($file,$theme);
                    if ($file !== FALSE)
                        $min.=file_get_contents($file);
                }

                File::write($file_name,Minify_CSS_Compressor::process($min));
            }

            $ret .= HTML::style(self::public_path('css/'.$css_minified_name,$theme), array('media' => 'screen'));
        }

        return $ret;
    }

    /**
     * deletes minified files for theme, for JS and CSS
     * @param  string $theme  
     * @return void 
     */
    public static function delete_minified($theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        $css_folder  = self::theme_folder($theme).'/css/';
        $js_folder   = self::theme_folder($theme).'/js/';
        $match       = 'minified-';

        //check directory for files and delete them
        if (is_writable($css_folder))
        {
            foreach (new DirectoryIterator($css_folder) as $file) 
            {
                if($file->isFile() AND !$file->isDot() AND  strpos($file->getFilename(), $match) === 0 )
                {
                    unlink($css_folder.$file->getFilename());
                }
            }
        }

        if (is_writable($js_folder))
        {
            foreach (new DirectoryIterator($js_folder) as $file) 
            {
                if($file->isFile() AND !$file->isDot() AND  strpos($file->getFilename(), $match) === 0 )
                {
                    unlink($js_folder.$file->getFilename());
                }
            }
        }
    }

    
    /**
     *
     * gets where the views are located in the default theme
     * @return string path
     *
     */
    public static function default_views_path()
    {
        return 'default'.DIRECTORY_SEPARATOR.self::$views_path;
    }
    
    /**
     *
     * gets the where the views are located in the theme
     * @return string path
     *
     */
    public static function views_path()
    {
        return self::$theme.DIRECTORY_SEPARATOR.self::$views_path;
    }


    /**
     *
     * gets  where the views are located in the parent theme
     * @return string path
     *
     */
    public static function views_parent_path()
    {
        return Theme::$parent_theme.DIRECTORY_SEPARATOR.self::$views_path;
    }
    
    /**
     *
     * given a file returns it's public path relative to the selected theme
     * @param string $file
     * @param string $theme optional
     * @return string
     */
    public static function public_path($file, $theme = NULL)
    {
        if ($theme === NULL)
        {
            $theme = self::$theme;
            $parent_theme = Theme::$parent_theme;
        }
        else
            $parent_theme = self::get_theme_parent($theme);

        // Handle CDN protocol-relative URLs
        if (strpos($file,'//')===0) 
        {
            // add current page protocol to CDN protocol-relative URL
            $protocol = (
                isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 )
            || 	isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
        ) ? 'https:' : 'http:';

            return $protocol.$file; // return it now as a valid URL regarding check done by \Kohana_HTML::script oc/kohana/system/classes/Kohana/HTML.php:242
        }

        //getting the public url only if was not external
        if (!Valid::url($file))
        {
            //copy of the file
            $file_check = $file;

            //public URI
            $uri = URL::base().'themes'.DIRECTORY_SEPARATOR;

            //remove the query from the uri
            if ( ($version = strpos($file, '?'))>0 )
                    $file_check = substr($file, 0, $version );

            //check file exists in the theme folder
            if (file_exists(self::theme_folder($theme).'/'.$file_check))
            {
                return $uri.$theme.DIRECTORY_SEPARATOR.$file;
            }
            //check if the parent has the file
            elseif ($parent_theme!==NULL AND file_exists(self::theme_folder($parent_theme).'/'.$file_check))
            {
                return $uri.$parent_theme.DIRECTORY_SEPARATOR.$file;
            }
            //lastly check at default theme as last resource
            elseif (file_exists(self::theme_folder('default').'/'.$file_check))
            {
                return $uri.'default'.DIRECTORY_SEPARATOR.$file;
            }
                   
        }
        //seems an external url, we return it directly
        else
        {
            return $file;
        }

        return FALSE;          
        
    }

    /**
     *
     * given a file returns it's full path relative to the selected theme
     * @param string $file
     * @param string $theme optional
     * @return string
     */
    public static function file_path($file, $theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        //remove the query from the uri
        if ( ($version = strpos($file, '?'))>0 )
            $file = substr($file, 0, $version );

        //get the contents of the file, if not found read from parent
        if (file_exists(self::theme_folder($theme).'/'.$file))
        {
            return self::theme_folder($theme).'/'.$file;
        }
        //reading from parent
        elseif (Theme::$parent_theme!==NULL AND file_exists(self::theme_folder(Theme::$parent_theme).'/'.$file))
        {
            return self::theme_folder(Theme::$parent_theme).'/'.$file;
        }    

        return FALSE;
    }   
    
    /**
     * get the full path folder for the theme
     * @param  string $theme 
     * @return string        
     */
    public static function theme_folder($theme = 'default')
    {
        return DOCROOT.'themes'.DIRECTORY_SEPARATOR.$theme;
    }

    /**
     * get the full path folder for the theme init.php file
     * @param  string $theme 
     * @return string        
     */
    public static function theme_init_path($theme = 'default')
    {
        return self::theme_folder($theme).DIRECTORY_SEPARATOR.'init.php';
    }

    /**
     * detect if visitor browser is mobile
     * @return boolean/theme name
     */
    public static function is_mobile()
    {
        $is_mobile = FALSE;

        //we check if we are forcing not to show mobile
        if( Core::get('theme')!=Core::config('appearance.theme_mobile') AND Core::get('theme')!==NULL)
        {
            $is_mobile = FALSE;
        }
        //check if we selected a mobile theme
        elseif ( Core::config('appearance.theme_mobile')!='' )
        {
            
            //they are forcing to show the mobile
            if ( Core::get('theme')==Core::config('appearance.theme_mobile')
                OR Cookie::get('theme')==Core::config('appearance.theme_mobile'))
            {
                $is_mobile = TRUE;
            }
            //none of this scenarios try to detect if ismobile
            else
            {
                require Kohana::find_file('vendor', 'Mobile-Detect/Mobile_Detect','php');
                $detect = new Mobile_Detect();
                if ($detect->isMobile() AND ! $detect->isTablet())
                    $is_mobile = TRUE;    
            }
        }


        return ($is_mobile)?Core::config('appearance.theme_mobile'):FALSE;
    }


    /**
     * initialize theme
     * @param  string $theme forcing theme to load used in the admin
     * @return void        
     */
    public static function initialize($theme = NULL)
    {

        //we are not forcing the view of other theme
        if ($theme === NULL)
        {
            //first we check if it's a mobile device
            if(($mobile_theme = self::is_mobile())!==FALSE)
            {
               $theme = $mobile_theme;
            }
            else 
                $theme = Core::config('appearance.theme');
                
            //if we allow the user to select the theme, perfect for the demo
            if (Core::config('appearance.allow_query_theme')=='1')
            {
                if (Core::get('theme')!==NULL)
                {
                    $theme = Core::get('theme');
                }
                elseif (Cookie::get('theme')!=='')
                {
                    $theme = Cookie::get('theme');
                }
            }

            //we save the cookie for next time
            Cookie::set('theme', $theme, Core::config('auth.lifetime'));
        }

        //check the theme exists..
        if (!file_exists(self::theme_init_path($theme)))
            $theme = Core::config('appearance.theme');
            

        //load theme init.php like in module, to load default JS and CSS for example
        self::$theme = $theme;

        Kohana::load(self::theme_init_path(self::$theme));

        self::load();
    }


    /**
     * sets the theme we need to use in front
     * @param string $theme 
     */
    public static function set_theme($theme)
    {
        //we check the theme exists and it's correct
        if (!file_exists(self::theme_init_path($theme)))
            return FALSE;

        // save theme to DB
        $conf = new Model_Config();
        $conf->where('group_name','=','appearance')
                    ->where('config_key','=','theme')
                    ->limit(1)->find();

        if (!$conf->loaded())
        {
            $conf->group_name = 'appearance';
            $conf->config_key = 'theme';
        }
        
        $conf->config_value = $theme;

        try 
        {
            Cookie::set('theme', $theme, Core::config('auth.lifetime'));
            $conf->save();
            return TRUE;
        } 
        catch (Exception $e) 
        {
            throw HTTP_Exception::factory(500,$e->getMessage());     
        }   

    }


     /**
     * sets the theme we need to use in front
     * @param string $theme 
     */
    public static function set_mobile_theme($theme)
    {
        if ($theme == 'disable' OR !file_exists(self::theme_init_path($theme)))
            $theme = '';

        // save theme to DB
        $conf = new Model_Config();
        $conf->where('group_name','=','appearance')
                    ->where('config_key','=','theme_mobile')
                    ->limit(1)->find();

        if (!$conf->loaded())
        {
            $conf->group_name = 'appearance';
            $conf->config_key = 'theme_mobile';
        }
        
        $conf->config_value = $theme;

        try 
        {
            $conf->save();
            return TRUE;
        } 
        catch (Exception $e) 
        {
            throw HTTP_Exception::factory(500,$e->getMessage());     
        }   

    }
    

    /**
     * Read the folder /themes/ for themes
     * @param  boolean $only_mobile set to true an returns the mobile themes
     * @return array               
     */
    public static function get_installed_themes($only_mobile = FALSE)
    {
        //read folders in theme folder
        $folder = DOCROOT.'themes';

        $themes = array();

        //check directory for themes
        foreach (new DirectoryIterator($folder) as $file) 
        {
            if($file->isDir() AND !$file->isDot())
            {
                if ( ($info = self::get_theme_info($file->getFilename())) !==FALSE )
                {

                    if ($only_mobile AND $info['Mobile']=='TRUE')
                        $themes[$file->getFilename()] = $info;
                    elseif(!$only_mobile AND $info['Mobile']!='TRUE')
                        $themes[$file->getFilename()] = $info;
                }
                    
            }
        }

        return $themes;
    }


    /**
     * returns the info regarding to the theme stores at init.php
     * @param  string $theme theme to search info
     * @return array        
     */
    public static function get_theme_info($theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        if (!file_exists($file = self::theme_init_path($theme)))
            return FALSE;

        return Core::get_file_data($file , array(
            'Name'        => 'Theme Name',
            'ThemeURI'    => 'Theme URI',
            'ThemeDemo'   => 'Theme Demo',
            'Description' => 'Description',
            'Author'      => 'Author',
            'AuthorURI'   => 'Author URI',
            'Version'     => 'Version',
            'License'     => 'License',
            'Tags'        => 'Tags',
            'Mobile'      => 'Mobile',
            'Parent'      => 'Parent Theme',
        )); 
    }


    /**
     * returns the parent from the header at init.php, used in cases where theme is not loaded from the init.php
     * @param  string $theme theme to search info
     * @return string/false        
     */
    public static function get_theme_parent($theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        $info = self::get_theme_info($theme);
        return ($info['Parent']!='')?$info['Parent']:NULL;
    }


    /**
     * returns the screenshot
     * @param  string $theme theme to search info
     * @return array        
     */
    public static function get_theme_screenshot($theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        return self::public_path('screenshot.png',$theme);
    }


    /**
     * this belongs to the admin, so needs to be loaded no matter, the theme. not a good place here?? not nice...
     * generates a link used in the admin HTML
     * @param  string $name       translated name in the A
     * @param  string $controller
     * @param  string $action     
     * @param  string $route      
     * @param  string $icon         class name of bootstrap icon to append with nav-link 
     */
    public static function admin_link($name,$controller,$action='index',$route='oc-panel', $icon = NULL, $id=NULL)
    {   
        if (Auth::instance()->get_user()->has_access($controller))
        {
            $data = array('name'=>$name,
                            'controller'=> $controller,
                            'action'    => $action,
                            'route'     => $route,
                            'icon'      => $icon,
                            'id'        => $id,);
            return View::factory('oc-panel/admin_link',$data);
        }
    }


    /**
     * nav_link generates a link for main nav-bar
     * @param  string $name       translated name in the A
     * @param  string $controller
     * @param  string $action  
     * @param  string $icon         class name of bootstrap icon to append with nav-link   
     * @param  string $route      
     * @param  string $style extra class div 
     * @param  mixed  $id id for the route
     */
    public static function nav_link($name, $controller, $icon=NULL, $action='index', $route='default' , $style = NULL, $id=NULL)
    {   
        $data = array('name'=>$name,
                        'controller'=> $controller,
                        'action'    => $action,
                        'route'     => $route,
                        'icon'      => $icon,
                        'style'     => $style,
                        'id'        => $id,);

        return View::factory('nav_link',$data);
    }




    /**
     * All the Custom options for the theme goes here
     */
    

    /**
     * array option the theme have, defined in the theme/ init.php
     * ex:
     * array(     'rss_items' => array( 'type'      => 'numeric',
     *                                                  'display'   => 'select',
     *                                                  'label'     => __('# items to display'),
     *                                                  'options'   => range(1,10), 
     *                                                  'required'  => TRUE),);
     * @var array
     */
    public static $options = array();


    /**
     * data stored for each field
     * @var array
     */
    public static $data = array();

 

    /**
     * loads the theme data from the config
     * @param  string $theme theme to load from
     * @return void 
     */
    public static function load($theme = NULL, $create_data = TRUE)
    {   
        self::$data = array();

        if ($theme === NULL)
            $theme = self::$theme;

        //search for theme config
        $theme_data = core::config('theme.'.$theme);

        //found and with data!
        if($theme_data!==NULL AND !empty($theme_data) AND $theme_data !== '[]')
        { 
            self::$data = json_decode($theme_data, TRUE);
        }
        ///save empty with default values & first time installed
        elseif($create_data == TRUE)
            self::data_set($theme);

    }

    /**
     * function that sets the data of the theme if was the first time activated
     * @param  string $theme 
     * @return void        
     */
    public static function data_set($theme)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        self::$data = array();

        //we set the array with empty values or the default in the option attributes
        foreach (self::$options as $field => $attributes) 
        {
            self::$data[$field] = (isset($attributes['default']))?$attributes['default']:'';
        }

        self::save($theme);
    }

    public static function checker()
    {
        if (self::get('premium')!=1 
                OR (strtolower(Request::current()->controller())=='theme' AND strtolower(Request::current()->action())=='license')
                OR !Auth::instance()->logged_in())
            return TRUE;

        if (self::get('premium')==1 AND (self::get('license_date') < time() OR self::get('license_date')==NULL))
        {
            if (self::license(self::get('license'))==TRUE)
            {
                self::$data['license_date'] = time()+7*24*60*60;
                self::save();
                return TRUE;
            }
            elseif (Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN )
            {
                Alert::set(Alert::INFO, __('License validation error, please insert again.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'=>'theme', 'action'=>'license')));
            }
        } 
    }

    public static function license($l, $current_theme = NULL)
    {  
        if ($current_theme === NULL)
            $current_theme = Theme::$theme;

        $licenses = array();

        //getting the licenses unique. to avoid downloading twice
        $themes = core::config('theme');

        //child  theme can use parent license, so we remove the parent from the list
        $parent_theme = self::get_theme_parent($current_theme);
        if ( $parent_theme !==NULL AND isset($themes[$parent_theme]))
            unset($themes[$parent_theme]);

        //remove current theme from themes checking list
        if (isset($themes[$current_theme]))
            unset($themes[$current_theme]);

        //for the remaining themes checking the values
        foreach ($themes as $theme=>$settings) 
        {
            $settings = json_decode($settings,TRUE);
            //theme has a license 
            if (isset($settings['license']))
            {
                //license is already in use in that theme
                if ($settings['license'] == $l)
                {
                    Alert::set(Alert::INFO, sprintf(__('This license is in use in the theme %s'),$theme));
                    return FALSE;
                }
            }
        }
         
        $api_url = (Kohana::$environment!== Kohana::DEVELOPMENT)? 'market.'.Core::DOMAIN:'eshop.lo';
        $api_url = 'http://'.$api_url.'/api/license/'.$l.'/?domain='.parse_url(URL::base(), PHP_URL_HOST);

        return json_decode(Core::curl_get_contents($api_url));
    }

    public static function download($l)
    {  
        $api_url = (Kohana::$environment!== Kohana::DEVELOPMENT)? 'market.'.Core::DOMAIN:'eshop.lo';
        $download_url = 'http://'.$api_url.'/api/download/'.$l.'/?domain='.parse_url(URL::base(), PHP_URL_HOST);
        $fname = DOCROOT.'themes/'.$l.'.zip'; //root folder
        $file_content = core::curl_get_contents($download_url);

        if ($file_content!='false')
        {
            // saving zip file to dir.
            file_put_contents($fname, $file_content);
            $zip = new ZipArchive;
            if ($zip_open = $zip->open($fname)) 
            {
                //if theres nothing in that ZIP file...zip corrupted :(
                if ($zip->getNameIndex(0)===FALSE)
                    return FALSE;
                
                $theme_name = (substr($zip->getNameIndex(0), 0,-1));
                $zip->extractTo(DOCROOT.'themes/');
                $zip->close();  
                unlink($fname);

                return $theme_name;
            }   
        }

        return FALSE;
    }


    /**
     * saves thme options as json 'theme.NAMETHEME' = array json
     * @param  string $theme theme to save at
     * @param  array $data to save
     * @return void 
     */
    public static function save($theme = NULL, $data = NULL)
    {   
        if ($theme === NULL)
            $theme = self::$theme;

        if ($data === NULL)
            $data = self::$data;


        // save theme to DB
        $conf = new Model_Config();
        $conf->where('group_name','=','theme')
                    ->where('config_key','=',$theme)
                    ->limit(1)->find();

        if (!$conf->loaded())
        {
            $conf->group_name = 'theme';
            $conf->config_key = $theme;
        }
        
        $conf->config_value = json_encode($data);

        try 
        {
            $conf->save();
        } 
        catch (Exception $e) 
        {
            throw HTTP_Exception::factory(500,$e->getMessage());     
        }   
    }

    /**
     * to know if we need to render form for example
     * @return boolean 
     */
    public static function has_options()
    {
        return (count(self::$data)>0)? TRUE:FALSE;
    }

    /**
     * get from data array
     * @param  string $name key
     * @param mixed default value in case is not set
     * @return mixed       
     */
    public static function get($name, $default = NULL)
    {
        return (array_key_exists($name, self::$data)) ? self::$data[$name] : $default;
    }

    /**
     * gets the options array from and external file options.php
     * @param  string $theme theme to load from
     * @return array        
     */
    public static function get_options($theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        $options = self::file_path('options.php', $theme);

        //no options file found, let's try the parent
        if($options===FALSE)
        {
            //child  theme can use parent license
            if ( ($parent = self::get_theme_parent($theme))!==NULL )
            {
                $options = self::file_path('options.php', $parent);
            }
                
        }

        //$options = self::theme_folder($theme).DIRECTORY_SEPARATOR.'options.php';
        if ($options!==FALSE)
            return Kohana::load($options);
        else 
            return array();
    }

}
