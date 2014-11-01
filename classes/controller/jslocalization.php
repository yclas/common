<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Jslocalization extends Controller {
		
	public function action_validate()
	{
		$this->auto_render = FALSE;
		$this->template = View::factory('js');
		
		$localization_rules=array(
								  'required'		=> __('This field is required.'),
								  'remote'			=> __('Please fix this field.'),
								  'email'			=> __('Please enter a valid email address.'),
								  'url'				=> __('Please enter a valid URL.'),
								  'date'			=> __('Please enter a valid date.'),
								  'dateISO'			=> __('Please enter a valid date (ISO).'),
								  'number'			=> __('Please enter a valid number.'),
								  'digits'			=> __('Please enter only digits.'),
								  'creditcard'		=> __('Please enter a valid credit card number.'),
								  'equalTo'			=> __('Please enter the same value again.'),
								  'accept'			=> __('Please enter a value with a valid extension.'),
								  'maxlength'		=> __('Please enter no more than {0} characters.'),
								  'minlength'		=> __('Please enter at least {0} characters.'),
								  'rangelength'		=> __('Please enter a value between {0} and {1} characters long.'),
								  'range'			=> __('Please enter a value between {0} and {1}.'),
								  'max'				=> __('Please enter a value less than or equal to {0}.'),
								  'min'				=> __('Please enter a value greater than or equal to {0}.'),		  
								  'regex'			=> __('Please enter a valid format.'),		  
		);
		
		$this->template->content = '(function ($) {$.extend($.validator.messages, '.json_encode($localization_rules). ');}(jQuery));';
	}

	public function action_chosen()
	{
		$this->auto_render = FALSE;
		$this->template = View::factory('js');
		
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
	
}// End Jslocalization Controller
