<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Core class for OC common, contains commons functions and helpers
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class OC_Core {

	/**
	 * 
	 * OC version
	 * @var string
	 */
	const VERSION = '1.0.0';

    /**
     * @var string used to populate data from valid domain
     */
    const DOMAIN  = '';

	/**
	 * 
	 * Initializes configs for the APP to run
	 */
	public static function initialize()
	{	
        //enables friendly url
        Kohana::$index_file = FALSE;

        //temporary cookie salt in case of exception
        Cookie::$salt = 'cookie_oc_temp';

		//Change the default cache system, based on your config /config/cache.php
		Cache::$default = Core::config('cache.default');

        //loading configs from table config
		//is not loaded yet in Kohana::$config
		Kohana::$config->attach(new ConfigDB(), FALSE);

		//overwrite default Kohana init configs.
		Kohana::$base_url = Core::config('general.base_url');

		//cookie salt for the app
		Cookie::$salt = Core::config('auth.cookie_salt');

		// i18n Configuration and initialization 
		I18n::initialize(Core::config('i18n.locale'),Core::config('i18n.charset'));

		//Loading the OC Routes
        require_once APPPATH.'config/routes.php';

        //getting the selected theme, and loading options
        Theme::initialize();

        //run crontab
        if (core::config('general.cron')==TRUE)
            Cron::run();

	}


	/**
     * Shortcut to load a group of configs
     * @param type $group
     * @return array 
     */
    public static function config($group)
    {
    	return Kohana::$config->load($group);
    }

    /**
     * Shortcut to get the URL of a CSS and JS resource
     * Use config settings to retrieve local or CDN file + HTTPS CDNs
     * 
     * @param string $resource_name = ['a'|'b']
     * @return array
     */
    public static function get_external_resource($resource_name,$bootswatchtheme=NULL)
    {
        static $b_use_cdn; if ( ! isset($b_use_cdn)) $b_use_cdn = Core::config('general.cdn_for_css_js') == '1';
        static $cdn_protocol; if ( $b_use_cdn AND ! isset($cdn_protocol)) $cdn_protocol = Core::config('general.cdn_use_https') == '1' ? 'https:':'http:';
        static $arr_resources; if ( ! isset($arr_resources)) $arr_resources = array(
            'bootstrap' => array(
                'css'=>($b_use_cdn?($cdn_protocol.'//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/'):'css/').'bootstrap.min.css',
                'js' =>($b_use_cdn?($cdn_protocol.'//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/'):'js/').'bootstrap.min.js',
            ),
            'bootswatch' => array(
                'css'=>($b_use_cdn?($cdn_protocol.'//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/'):'css/bootswatch/').($bootswatchtheme?:'cerulean').'/bootstrap.min.css',
            ),
            'jquery' => array(
                'js' =>($b_use_cdn?($cdn_protocol.'//code.jquery.com/'):'js/').'jquery-1.11.1.min.js',
            ),
            'prettyphoto'=> array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/prettyphoto/3.1.5/css/'):'css/').'prettyPhoto.css',
                'js' =>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/prettyphoto/3.1.5/js/'):'js/').'jquery.prettyPhoto.js',
            ),
            'jquery.sceditor'=> array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css'):'css/jquery.sceditor.min.css'),
                'js' =>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/sceditor/1.4.3/jquery.sceditor.min.js'):'js/jquery.sceditor.min.js?v=144'),
            ),
            'summernote'=> array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdnjs.cloudflare.com/ajax/libs/summernote/0.5.2/'):'css/').'summernote.css', // @TOFIX @FIXME for BS3: summernote-bs3.css instead ?,
                'js' =>($b_use_cdn?($cdn_protocol.'//cdnjs.cloudflare.com/ajax/libs/summernote/0.5.2/'):'js/').'summernote.min.js',
            ),
            'chosen'=> array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/chosen/1.1.0/'):'css/').'chosen.min.css',
                'js' =>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/chosen/1.1.0/'):'js/').'chosen.jquery.min.js',
            ),
            'bootstrap.datepicker' => array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/'):'css/').'datepicker.css',
                'js' =>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/'):'js/').'bootstrap-datepicker.js',
            ),
            'bootstrap.tagsinput' => array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/'):'css/').'bootstrap-tagsinput.css',
                'js' =>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/'):'js/').'bootstrap-tagsinput.min.js',
            ),
            'bootstrap.rtl' => array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.2.0-rc2/css/'):'css/').'bootstrap-rtl.min.css',
            ),
            'jquery.validate' => array(
                'js' =>($b_use_cdn?($cdn_protocol.'//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.12.0/'):'js/').'jquery.validate.min.js',
            ),
            'jquery.cookie' => array(
                'js' =>($b_use_cdn?($cdn_protocol.'//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/'):'js/').'jquery.cookie.min.js',
            ),
            'jquery.fileupload' => array(
                'css'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/jquery.fileupload/9.5.2/css/'):'css/').'jquery.fileupload.css',
                'js' =>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/'):'js/').'jquery.fileupload.js',
                'js.ui'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/vendor/'):'js/').'jquery.ui.widget.js',
                'js.iframe'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/'):'js/').'jquery.iframe-transport.js',
            ),
            'fonts.awesome' => array(
                'css'=>($b_use_cdn?($cdn_protocol.'//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/'):'css/').'font-awesome.min.css',
            ),
            'sorttable' => array(
                'js'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/sorttable/2/'):'js/').'sorttable.min.js',
            ),
            '.sorttable' => array(
                'js'=>($b_use_cdn?($cdn_protocol.'//cdn.jsdelivr.net/sorttable/2/'):'js/').'sorttable.min.js',
                //'js'=>'js/jquery-sortable-min.js',
            ),
        );
        $resource_name = strtolower($resource_name);
        if (array_key_exists($resource_name, $arr_resources))
            return $arr_resources[$resource_name];
        else
            return array('css'=>NULL,'js'=>NULL);
    }

    /**
     * shortcut for the query method $_GET
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function get($key,$default=NULL)
    {
        if (Request::current()!==NULL)
           return (Request::current()->query($key)!=NULL) ? Request::current()->query($key):$default;
        else
    	   return (isset($_GET[$key]))?$_GET[$key]:$default;
    }

    /**
     * shortcut for $_POST[]
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function post($key,$default=NULL)
    {
        if (Request::current()!==NULL)
           return (Request::current()->post($key)!=NULL) ? Request::current()->post($key):$default;
        else
           return (isset($_POST[$key]))?$_POST[$key]:$default;
    }

    /**
     * shortcut to get or post
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function request($key,$default=NULL)
    {
        return (Core::post($key)!==NULL)?Core::post($key):Core::get($key,$default);
    }

    /**
     * shortcut for $_COOKIE[]
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function cookie($key,$default=NULL)
    {
        return (isset($_COOKIE[$key]))?$_COOKIE[$key]:$default;
    }

    /**
     * shortcut for the cache instance
     * 
     * @param   string  $name       name of the cache
     * @param   mixed   $data       data to cache
     * @param   integer $lifetime   number of seconds the cache is valid for, if 0 delete cache
     * @return  mixed    for getting
     * @return  boolean  for setting
     */
    public static function cache($name, $data = NULL, $lifetime = NULL)
    {
        //deletes the cache
        if ($lifetime===0)
            return Cache::instance()->delete($name);

        //in development we do not store or read we always return null
        if (Kohana::$environment == Kohana::DEVELOPMENT)
            return NULL;

        //no data provided we read
        if ($data===NULL)
            return Cache::instance()->get($name);
        //saves data
        else
            return Cache::instance()->set($name,$data, $lifetime);
    }

    /**
     * deletes all the cache + theme temp files
     * @return [type] [description]
     */
    public static function delete_cache()
    {
        Cache::instance()->delete_all();
        Theme::delete_minified();
    }

    /**
     * optmizes all the tables found in the database
     * @return [type] [description]
     */
    public static function optimize_db()
    {
        $db = Database::instance('default');
        $tables = $db->query(Database::SELECT, 'SHOW TABLES');

        foreach ($tables as $table)
        {
            $table = array_values($table);
            $to[] = $table[0];
        }
        $db->query(Database::SELECT, 'OPTIMIZE TABLE '.implode(', ', $to));
    }


    /**
     * Function modified from WordPress = http://phpdoc.wordpress.org/trunk/WordPress/_wp-includes---functions.php.html#functionget_file_data
     * 
     * Retrieve metadata from a file.
     *
     * Searches for metadata in the first 8kiB of a file, such as a plugin or theme.
     * Each piece of metadata must be on its own line. Fields can not span multiple
     * lines, the value will get cut at the end of the first line.
     *
     * If the file data is not within that first 8kiB, then the author should correct
     * their plugin file and move the data headers to the top.
     *     
     * 
     * @param string $file Path to the file
     * @param array $default_headers List of headers, in the format array('HeaderKey' => 'Header Name')
     * @return array
     */
    public static function get_file_data( $file, $default_headers) 
    {
        // We don't need to write to the file, so just open for reading.
        $fp = fopen( $file, 'r' );

        // Pull only the first 8kiB of the file in.
        $file_data = fread( $fp, 8192 );

        // PHP will close file handle, but we are good citizens.
        fclose( $fp );

        // Make sure we catch CR-only line endings.
        $file_data = str_replace( "\r", "\n", $file_data );

        foreach ( $default_headers as $field => $regex )
        {
            if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
                $default_headers[ $field ] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '',  $match[1] ));
            else
                $default_headers[ $field ] = '';
        }

        return $default_headers;
    }



    /**
     * get updates from json hosted at our site
     * @param  boolean $reload  
     * @return void
     */
    public static function get_updates($reload = FALSE)
    {
        //we check the date of our local versions.php
        $version_file = APPPATH.'config/versions.php';

        //if older than a month or ?reload=1 force reload
        if ( time() > strtotime('+1 week',filemtime($version_file)) OR $reload === TRUE )
        {
            //read from oc/versions.json on CDN
            $json = Core::curl_get_contents('http://'.Core::DOMAIN.'/files/versions.json?r='.time());
            $versions = json_decode($json,TRUE);
            if (is_array($versions))
            {
                //update our local versions.php
                $content = "<?php defined('SYSPATH') or die('No direct script access.');
                return ".var_export($versions,TRUE).";";// die($content);
                //@todo check file permissions?
                File::write($version_file, $content);
            }

        }
    }


    /**
     * get market from json hosted currently at our site
     * @param  boolean $reload  
     * @return void
     */
    public static function get_market($reload = FALSE)
    {
        $market_url = (Kohana::$environment!== Kohana::DEVELOPMENT)? 'market.'.Core::DOMAIN.'':'eshop.lo';
        $market_url = 'http://'.$market_url.'/api/products';

        //try to get the json from the cache
        $market = Core::cache($market_url);

        //not cached :(
        if ($market === NULL OR  $reload === TRUE)
        {
            $market = Core::curl_get_contents($market_url.'?r='.time());
            //save the json
            Core::cache($market_url,$market,strtotime('+1 day'));
        }

        return json_decode($market,TRUE);

    }

    /**
     * gets the html content from a URL
     * @param  string $url
     * @param  integer $timeout seconds to timeout the request
     * @return string on success, false on errors
     * @return string      
     */
    public static function curl_get_contents($url, $timeout = 30)
    {
        $c = curl_init(); if ($c === FALSE) return FALSE;
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT,$timeout); 
        curl_setopt($c, CURLOPT_REFERER, URL::current());
        // curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
        // $contents = curl_exec($c);
        $contents = core::curl_exec_follow($c);


        if( ! curl_errno($c))
        {
            curl_close($c);
            return ($contents)? $contents : FALSE;
        }
        else 
            return FALSE;
            //throw new Kohana_Exception('Curl '.$url.' error: ' . curl_error($c));
    }

    /**
     * [curl_exec_follow description] http://us2.php.net/manual/en/function.curl-setopt.php#102121
     * @param  curl  $ch          handler
     * @param  integer $maxredirect hoe many redirects we allow
     * @return contents
     */
    public static function curl_exec_follow($ch, $maxredirect = 5) 
    { 
        //using normal curl redirect
        if (ini_get('open_basedir') == '' AND ini_get('safe_mode' == 'Off')) 
        { 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $maxredirect > 0); 
            curl_setopt($ch, CURLOPT_MAXREDIRS, $maxredirect); 
        } 
        //using safemode...WTF!
        else 
        { 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); 
            if ($maxredirect > 0) 
            { 
                $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 

                $rch = curl_copy_handle($ch); 
                curl_setopt($rch, CURLOPT_HEADER, TRUE); 
                curl_setopt($rch, CURLOPT_NOBODY, TRUE); 
                curl_setopt($rch, CURLOPT_FORBID_REUSE, FALSE); 
                curl_setopt($rch, CURLOPT_RETURNTRANSFER, TRUE); 

                do 
                { 
                    curl_setopt($rch, CURLOPT_URL, $newurl); 
                    $header = curl_exec($rch); 
                    if (curl_errno($rch))
                        $code = 0; 
                    else 
                    { 
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE); 
                        if ($code == 301 OR $code == 302) 
                        { 
                            preg_match('/Location:(.*?)\n/', $header, $matches); 
                            $newurl = trim(array_pop($matches)); 
                        }
                        else 
                            $code = 0; 
                    } 
                } 
                while ($code AND --$maxredirect); 

                curl_close($rch); 

                if (!$maxredirect) 
                { 
                    if ($maxredirect === NULL) 
                        trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING); 
                    else  
                        $maxredirect = 0; 

                    return FALSE; 
                } 

                curl_setopt($ch, CURLOPT_URL, $newurl); 
            } 
        } 

        return curl_exec($ch); 
    } 

    /**
     * Akismet spam check. Invokes akismet class to get response is spam.
     * @param name
     * @param email
     * @param comment
     * @return bool true is not spam false is spam
     */
    public static function akismet($name,$email,$comment)
    {
        require_once Kohana::find_file('vendor', 'akismet/akismet','php');

        if (core::config('general.akismet_key')!='')
        {
            //d(func_get_args());
            $akismet = new Akismet(core::config('general.base_url') ,core::config('general.akismet_key'));
            $akismet->setCommentAuthor($name);
            $akismet->setCommentAuthorEmail($email);
            $akismet->setCommentContent($comment);

            try 
            {
                return $akismet->isCommentSpam();
            } 
            catch (Exception $e) 
            {
                return FALSE;
            }
        }
        else //we return is not spam since we do not have the api :(
            return FALSE;
    }

    /**
     * prints the QR code script from the view
     * @param $url is the URL for your QRCode
     * @param $size in pixels for image
     * @param $EC_level Error Correction Level
     * @param $margin around image
     * @return string HTML or false in case not loaded
     */
    public static function generate_qr($url = NULL, $size ='150',$EC_level='L',$margin='0')
    {
        $url = ($url == NULL)?URL::current():$url;
        $url = urlencode($url);
        return '<img src="//chart.googleapis.com/chart?chs='.$size.'x'.$size.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$url.'" alt="QR code" width="'.$size.'" height="'.$size.'"/>';
    }

    /**
     * [ocacu description]
     * @return void
     */
    public static function ocacu()
    {
        //first time install notify of installation to ocacu once month
        if (Core::config('general.ocacu') < strtotime('-1 second'))
        {
            $url = (Kohana::$environment!== Kohana::DEVELOPMENT)? 'ocacu.com':'ocacu.lo';
            $url = 'http://'.$url.'/api/new/?siteUrl='.URL::base();
            if (Core::curl_get_contents($url,5))
                Model_Config::set_value('general','ocacu',time());
        }
    }

    /**
     * checks if URL is using HTTPS
     * we use this since Core::is_HTTPS() checks that the server has a real SSL certificate installed.
     * @return boolean 
     */
    public static function is_HTTPS()
    {
        return (strpos(URL::base(),'https://')===0) ? TRUE : FALSE;
    }


    /**
     * shortcut to upload files to S3
     * @param file $file        
     * @param string $destination
     */
    public static function S3_upload($file,$destination)
    {
        if(core::config('image.aws_s3_active') AND is_readable($file) )
        {
            require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
            $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));
            $s3->putObject($s3->inputFile($file), core::config('image.aws_s3_bucket'), $destination, S3::ACL_PUBLIC_READ);
        }
    }

    /**
     * returns the domain on aws s3 or local base if s3 is not active
     */
    public static function S3_domain()
    {
        if ( core::config('image.aws_s3_active') )
        {
            $protocol = Core::is_HTTPS() ? 'https://' : 'http://';
            return $protocol.core::config('image.aws_s3_domain');
        }
        else
            return URL::base();

        return FALSE;
    }

    /**
     * CSV file to array
     * @param  file  $csv             
     * @param  array  $expected_header 
     * @param  boolean $convert_object  you want it returned as an object?
     * @param  string  $delimiter       
     * @param  string  $enclosure       
     * @return array                   
     */
    public static function csv_to_array($csv,$expected_header=NULL,$convert_object = FALSE, $delimiter = "," , $enclosure = '"')
    {
        $end_array =  array();
        
        //open CSV
        if (file_exists($csv) AND ($handle = fopen($csv, 'r')) !== FALSE) 
        {
            $i = 0;

            //line by line
            while(($data = fgetcsv($handle, 0, $delimiter, $enclosure)) !== FALSE)
            {
                //check the header
                if ($i == 0 AND is_array($expected_header))
                {
                    if($data != $expected_header)
                        return FALSE;
                }
                //not header 
                else
                {
                    //return as object
                    if ($convert_object === TRUE)
                    {
                        $obj = new stdClass();
                        foreach ($data as $field => $value) 
                        {
                            try 
                            {
                                $expected_header_field = $expected_header[$field];
                                $obj->$expected_header_field = $value;   
                            } catch (Exception $e) {
                                //got a field that was not in the header :S
                                return FALSE;
                            }
                        }

                        $end_array[$i] = $obj;
                    }
                    else
                        $end_array[$i] = $data;
                }

                $i++;
            }
            fclose($handle); 
        }

        return $end_array;
    }

    /**
     * push notiication using GCM
     * @param  array/string $device_id devices
     * @param  string $message   
     * @param  array $data      
     * @return bool            
     */
    public static function push_notification($device_id,$message,$data = NULL)
    {
        if (core::config('general.gcm_apikey')!=NULL )
        {
            require_once Kohana::find_file('vendor', 'GCMPushMessage','php');
            $gcpm = new GCMPushMessage(core::config('general.gcm_apikey'));
            $gcpm->setDevices($device_id);

            try 
            {
                return ($gcpm->send($message, $data))?TRUE:FALSE;
            } 
            catch (Exception $e) 
            {
                return FALSE;
            }
        }

        return FALSE;        
    }

    /**
     * resize images with imagefly or CDN 
     * @param  string $image  image URI
     * @param  integer $width  
     * @param  integer $height 
     * @param  integer $mode   mode
     * @return string         URI
     */
    public static function imagefly($image,$width=NULL,$height=NULL,$mode='crop')
    {
        //usage of WP CDN, if they use AWS also!
        if ( (Theme::get('cdn_files') == TRUE OR Core::config('image.aws_s3_active') == TRUE)  
            AND Valid::url($image) == TRUE
            AND Kohana::$environment!== Kohana::DEVELOPMENT)
        {
            $protocol = Core::is_HTTPS() ? 'https://' : 'http://';

            $image = str_replace($protocol, '', $image);

            //for images we use the cached CDN of wp - getting the numbers to calculate the photon domain
            $num_images = preg_replace('/\D/', '',$image);
            $photon_domain = (is_numeric($num_images))?$num_images%3:1;

            if ($mode = 'crop' AND is_numeric($width) AND is_numeric($height) )
                $params = 'resize='.$width.','.$height;
            elseif (is_numeric($width) AND $height===NULL )
                $params = 'w='.$width;
            elseif ($width==NULL AND is_numeric($height) )
                $params = 'h='.$height;

            //add = or & param to url
            $params = ((strpos($image, '?')>0)?'&':'?').$params;

            return $protocol.'i'.$photon_domain.'.wp.com/'.$image.$params;
        }
        //local resize
        else
        {
            $image_path = NULL;

            //remove HTTP
            if (Valid::url($image))
            {
                $image_path = str_replace(Core::S3_domain(), '', $image);
                if (($pos = strpos($image_path, '?'))>0)
                    $image_path = substr($image_path, 0, $pos);
            }

            if (file_exists($image_path))
            {
                if (is_numeric($width) AND is_numeric($height) )
                    $params = 'w'.$width.'-h'.$height;
                elseif (is_numeric($width) AND $height===NULL )
                    $params = 'w'.$width;
                elseif ($width==NULL AND is_numeric($height) )
                    $params = 'h'.$height;

                if ($mode = 'crop')
                    $params.='-c';

                return Route::url('imagefly',  array('params'=>$params,'imagepath'=>$image_path));
            }
            
        }

        return $image;
            
    }


} //end core

/**
 * Common functions
 */


/**
 *
 * Dies and var_dumps
 * @param any $var
 */
function d($var)
{
	die(var_dump($var));
}

/**
 *
 * Dies and print_r
 * @param any $var
 */
function dr($var)
{
    die('<pre>'.print_r($var,TRUE).'</pre>');
}
