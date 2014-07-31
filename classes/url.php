<?php defined('SYSPATH') or die('No direct script access.');
/**
 * URL helper class.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class URL extends Kohana_URL {


    /**
     * Convert a phrase to a URL-safe title. Overwriten original to ascii only depending on language
     *
     *     echo URL::title('My Blog Post'); // "my-blog-post"
     *
     * @param   string   $title       Phrase to convert
     * @param   string   $separator   Word separator (any single character)
     * @param   boolean  $ascii_only  Transliterate to ASCII?
     * @return  string
     * @uses    UTF8::transliterate_to_ascii
     */
    public static function title($title, $separator = '-', $ascii_only = NULL)
    {
        /**
         * this hack is to add tohse languages that are not in ascii, so we add them to the array
         * @var boolean
         */
        // if ($ascii_only === NULL)
        //     $ascii_only = ( in_array(i18n::$locale, array('hi_IN','ar','ur_PK','ru_RU','bn_BD','ml_IN','ja_JP')) )? FALSE:TRUE;
                
        return parent::title(str_replace("'",'-',$title), $separator, mb_detect_encoding($title,'ASCII'));
    }

    /**
     * Fetches an absolute site URL based on a URI segment.
     *
     *     echo URL::site('foo/bar');
     *
     * @param   string  $uri        Site URI to convert
     * @param   mixed   $protocol   Protocol string or [Request] class to use protocol from
     * @param   boolean $index      Include the index_page in the URL
     * @return  string
     * @uses    URL::base
     */
    public static function site($uri = '', $protocol = NULL, $index = TRUE)
    {
        // Chop off possible scheme, host, port, user and pass parts
        $path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', trim($uri, '/'));

        // Encode all non-ASCII characters, as per RFC 1738
        if(mb_detect_encoding($path,'ASCII')===TRUE)
        {
            $path = parent::title($path, '-', TRUE);
        }

        // Concat the URL
        return URL::base($protocol, $index).$path;
    }

    /**
     * returns the current url we are visiting with querystring included
     * @return [type] [description]
     */
    public static function current()
    {
        $query_string = (isset($_SERVER['QUERY_STRING']) AND !empty($_SERVER['QUERY_STRING']))? '?'.$_SERVER['QUERY_STRING']:'';

        return URL::base().Request::current()->uri().$query_string;
    }


    /**
     * @param string $domain Pass $_SERVER['SERVER_NAME'] here
     * @param bool $debug
     *
     * @debug bool $debug
     * @return string
     * @see https://gist.github.com/pocesar/5366899
     */
    public static function get_domain($domain, $debug = false)
    {
        $original = $domain = strtolower($domain);

        if (filter_var($domain, FILTER_VALIDATE_IP)) { return $domain; }

        $debug ? print('<strong style="color:green">&raquo;</strong> Parsing: '.$original) : false;

        $arr = array_slice(array_filter(explode('.', $domain, 4), function($value){
            return $value !== 'www';
        }), 0); //rebuild array indexes

        if (count($arr) > 2)
        {
            $count = count($arr);
            $_sub = explode('.', $count === 4 ? $arr[3] : $arr[2]);

            $debug ? print(" (parts count: {$count})") : false;

            if (count($_sub) === 2) // two level TLD
            {
                $removed = array_shift($arr);
                if ($count === 4) // got a subdomain acting as a domain
                {
                    $removed = array_shift($arr);
                }
                $debug ? print("<br>\n" . '[*] Two level TLD: <strong>' . join('.', $_sub) . '</strong> ') : false;
            }
            elseif (count($_sub) === 1) // one level TLD
            {
                $removed = array_shift($arr); //remove the subdomain

                if (strlen($_sub[0]) === 2 && $count === 3) // TLD domain must be 2 letters
                {
                    array_unshift($arr, $removed);
                }
                else
                {
                    // non country TLD according to IANA
                    $tlds = array(
                        'aero',
                        'arpa',
                        'asia',
                        'biz',
                        'cat',
                        'com',
                        'coop',
                        'edu',
                        'gov',
                        'info',
                        'jobs',
                        'mil',
                        'mobi',
                        'museum',
                        'name',
                        'net',
                        'org',
                        'post',
                        'pro',
                        'tel',
                        'travel',
                        'xxx',
                    );

                    if (count($arr) > 2 && in_array($_sub[0], $tlds) !== false) //special TLD don't have a country
                    {
                        array_shift($arr);
                    }
                }
                $debug ? print("<br>\n" .'[*] One level TLD: <strong>'.join('.', $_sub).'</strong> ') : false;
            }
            else // more than 3 levels, something is wrong
            {
                for ($i = count($_sub); $i > 1; $i--)
                {
                    $removed = array_shift($arr);
                }
                $debug ? print("<br>\n" . '[*] Three level TLD: <strong>' . join('.', $_sub) . '</strong> ') : false;
            }
        }
        elseif (count($arr) === 2)
        {
            $arr0 = array_shift($arr);

            if (strpos(join('.', $arr), '.') === false
                && in_array($arr[0], array('localhost','test','invalid')) === false) // not a reserved domain
            {
                $debug ? print("<br>\n" .'Seems invalid domain: <strong>'.join('.', $arr).'</strong> re-adding: <strong>'.$arr0.'</strong> ') : false;
                // seems invalid domain, restore it
                array_unshift($arr, $arr0);
            }
        }

        $debug ? print("<br>\n".'<strong style="color:gray">&laquo;</strong> Done parsing: <span style="color:red">' . $original . '</span> as <span style="color:blue">'. join('.', $arr) ."</span><br>\n") : false;

        return join('.', $arr);
    }

} // End url
