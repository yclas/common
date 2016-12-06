<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sociual auth class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */


class Social {

    public static function get()
    {
        $config = json_decode(core::config('social.config'),TRUE);
        return (!is_array($config))? array():$config;
    }

    public static function get_providers()
    {
        $providers = self::get();

        return (isset($providers['providers']))?$providers['providers']:array();
    }

    public static function enabled_providers()
    {
        $providers         = self::get();
        $enabled_providers = array();

        if (isset($providers['providers']))
        {
            foreach ($providers['providers'] as $k => $provider) {
                if ($provider['enabled'])
                {
                    $enabled_providers[$k] = $providers['providers'][$k];
                }
            }
        }

        return $enabled_providers;
    }

    public static function include_vendor()
    {
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Auth','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Endpoint','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Logger','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Exception','php');
    }

    public static function post_ad($id_ad)
    {

        
$page_access_token = 'EAAMbimzc0X4BAAMsR2bLSu1EZBfsmF5GUJ6yMdFNC7s4XshP5gMPgeOenMZBgapTrDmZCByzZAHc48vNpFJ99HwJEVhnnRujbsE9LAZCQdfJzZBFZCv23Vs9gpXCZC9DA4iqZBTE5uAINZBsQ4et3XWooRAVxMYt3j1SqYEUsFEpuLjAZDZD';
$page_id = '345351725560260';

$data['picture'] = "http://www.example.com/image.jpg";
$data['link'] = "http://www.example.com/";
$data['message'] = "Your message";
$data['caption'] = "Caption";
$data['description'] = "Description";


$data['access_token'] = $page_access_token;

$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($ch);
curl_close($ch);
d($return);



        $fb_token = core::config('advertisement.facebook_token');
        
        $fb_token = '10154258433555157';

        if ($fb_token != NULL)
        {

            require_once Kohana::find_file('vendor', 'facebook-php-graph-sdk-5.4/src/Facebook/autoload','php');
            $fb_details = social::get()['providers']['Facebook']['keys'];

            $fb = new Facebook\Facebook([
              'app_id' => $fb_details['id'],
              'app_secret' => $fb_details['secret'],
              'default_graph_version' => 'v2.2',
              ]);

            $linkData = [
              'link' => 'http://www.example.com',
              'message' => 'User provided message',
              ];

            try {
              // Returns a `Facebook\FacebookResponse` object
              $response = $fb->post('/me/feed', $linkData, $fb_token);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }

            $graphNode = $response->getGraphNode();

            echo 'Posted with id: ' . $graphNode['id'];

        }

    }

}