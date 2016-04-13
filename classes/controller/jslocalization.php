<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Jslocalization extends Controller {

    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->auto_render = FALSE;
        $this->response->headers('Content-type','application/javascript');
        $this->template = View::factory('js');
    }

    public function action_cookieconsent()
    {        
        $localization_rules = "$(document).ready(function(){
                                  $.cookieBar({message: '".__('We use cookies to track usage and preferences')."',
                                                acceptButton: true,
                                                acceptText: '".__('I Understand')."',
                                                effect: 'slide',
                                                append: true,
                                                fixed: true,
                                                bottom: true
                                            });
                                });"; 
        $this->template->content = $localization_rules;
    }

    public function action_adi()
    {
        $ret = '$(function(){
                    $.adi({
                        theme: "dark",
                        title: "'.__('Adblock detected!').'",
                        content: "'.__('We noticed that you may have an Ad Blocker turned on. Please be aware that our site is best experienced with Ad Blockers turned off.').'"
                    });
                });';
        $this->template->content = $ret;
    }

    public function action_select2()
    {
        $ret = '(function() {
                    if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd) var e = jQuery.fn.select2.amd;
                    return e.define("select2/i18n/es", [], function() {
                        return {
                            errorLoading: function() {
                                return "'.__('The results could not be loaded.').'"
                            },
                            inputTooLong: function(e) {
                                var t = e.input.length - e.maximum,
                                    n = "'.__('Please delete').' " + t + " '.__('character').'";
                                return t != 1 && (n += "s"), n
                            },
                            inputTooShort: function(e) {
                                var t = e.minimum - e.input.length,
                                    n = "'.__('Please enter').' " + t + " '.__('or more characters').'";
                                return n
                            },
                            loadingMore: function() {
                                return "'.__('Loading more result...').'"
                            },
                            maximumSelected: function(e) {
                                var t = "'.__('You can only select').' " + e.maximum + " '.__('item').'";
                                return e.maximum != 1 && (t += "s"), t
                            },
                            noResults: function() {
                                return "'.__('No results found').'"
                            },
                            searching: function() {
                                return "'.__('Searching...').'"
                            }
                        }
                    }), {
                        define: e.define,
                        require: e.require
                    }
                })();';
        $this->template->content = $ret;
    }
        
    public function action_validate()
    {
        $localization_rules=array(
                                  'required'        => __('This field is required.'),
                                  'remote'          => __('Please fix this field.'),
                                  'email'           => __('Please enter a valid email address.'),
                                  'url'             => __('Please enter a valid URL.'),
                                  'date'            => __('Please enter a valid date.'),
                                  'dateISO'         => __('Please enter a valid date (ISO).'),
                                  'number'          => __('Please enter a valid number.'),
                                  'digits'          => __('Please enter only digits.'),
                                  'creditcard'      => __('Please enter a valid credit card number.'),
                                  'equalTo'         => __('Please enter the same value again.'),
                                  'accept'          => __('Please enter a value with a valid extension.'),
                                  'maxlength'       => __('Please enter no more than {0} characters.'),
                                  'minlength'       => __('Please enter at least {0} characters.'),
                                  'rangelength'     => __('Please enter a value between {0} and {1} characters long.'),
                                  'range'           => __('Please enter a value between {0} and {1}.'),
                                  'max'             => __('Please enter a value less than or equal to {0}.'),
                                  'min'             => __('Please enter a value greater than or equal to {0}.'),          
                                  'regex'           => __('Please enter a valid format.'),        
        );
        
        $this->template->content = '(function ($) {$.extend($.validator.messages, '.json_encode($localization_rules). ');}(jQuery));';
    }

    public function action_chosen()
    {
        $localization_rules = 'function getChosenLocalization(text)
                                {
                                    switch (text)
                                    { 
                                        case "no_results_text": 
                                            return "'.__('No results match').'";
                                            break;
                                        case "placeholder_text_multiple": 
                                            return "'.__('Select Some Options').'";
                                            break;
                                        case "placeholder_text_single": 
                                            return "'.__('Select an Option').'";
                                            break;
                                    }
                                }'; 
        $this->template->content = $localization_rules;
    }

    public function action_bstour()
    {
        $bstour_basepath = explode('/', core::config('general.base_url'));
        $bstour_basepath = array_slice($bstour_basepath, 3);
        $bstour_basepath = '/'.implode('/', $bstour_basepath);
        
        $localization_rules = 'function getTourLocalization(text)
                                {
                                    switch (text)
                                    { 
                                        case "step1_title": 
                                            return "'.__('Hey!').'";
                                            break;
                                        case "step1_content": 
                                            return "'.__('You are now viewing your admin panel, where you can control almost everything in your classifieds site.').'";
                                            break;
                                        case "step2_content": 
                                            return "'.__('Get started by creating and editing categories and locations for your site here.').'";
                                            break;
                                        case "step3_content": 
                                            return "'.__('Put your website on maintenance mode until you want to launch it, manage other general settings and create custom fields through this tab.').'";
                                            break;
                                        case "step4_content": 
                                            return "'.__('Customize your website look and feel by choosing one of the many available themes and changing theme options.').'";
                                            break;
                                        case "step5_content": 
                                            return "'.__('When there is something you want to know type your question here or check the full list of our <a href=\'https://docs.yclas.com/\'>guides and faqs</a>.').'";
                                            break;
                                        case "step6_title": 
                                            return "'.__('Hey!').'";
                                            break;
                                        case "step6_content": 
                                            return "'.sprintf(__('You are now viewing the back panel at %s here you can manage your ads, favorites, payments and more.'), core::config('general.site_name')).'";
                                            break;
                                        case "step7_content": 
                                            return "'.__('Manage ads you published and edit them through this tab, you can also ask to feature or place your ad to top here.').'";
                                            break;
                                        case "step8_content": 
                                            return "'.__('Customize your profile, upload a photo, description and change your password.').'";
                                            break;
                                        case "step9_content": 
                                            return "'.__('You can check payments you made and see your favorites list here').'";
                                            break;
                                        case "step10_content": 
                                            return "'.sprintf(__('To continue your experience with %s you can get back to the main website by clicking here.'), core::config('general.site_name')).'";
                                            break;
                                    }
                                }';
        $localization_rules .= 'function getTourBasePath()
                                {
                                    return "'.$bstour_basepath.'";
                                }
                              ';
        $this->template->content = $localization_rules;
    }
    
}// End Jslocalization Controller
