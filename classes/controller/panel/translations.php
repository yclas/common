<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Translations
 */


class Controller_Panel_Translations extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Translations'))->set_url(Route::url('oc-panel',array('controller'  => 'translations'))));
        
    }

    public function action_index()
    {

        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('List')));  
        $this->template->title = __('Translations');

        //scan project files and generate .po
        $parse = $this->request->query('parse');
        if($parse)
        {
            //scan script
            require_once Kohana::find_file('vendor', 'POTCreator/POTCreator','php');

            $obj = new POTCreator;
            $obj->set_root(DOCROOT);
            $obj->set_exts('php');
            $obj->set_regular('/_[_|e]\([\"|\']([^\"|\']+)[\"|\']\)/i');
            $obj->set_base_path('..');
            $obj->set_read_subdir(true);
            
            $obj->write_pot(i18n::get_language_path());
            Alert::set(Alert::SUCCESS, 'File regenerated');
        }

        //change default site language
        if($this->request->param('id'))
        {
         //save language
            $locale = new Model_Config();

            $locale->where('group_name','=','i18n')
                    ->where('config_key','=','locale')
                    ->limit(1)->find();

            if (!$locale->loaded())
            {
                $locale->group_name = 'i18n';
                $locale->config_key = 'locale';
            }

            $locale->config_value = $this->request->param('id');
            try {
                $locale->save();
                Alert::set(Alert::SUCCESS,__('Translations regenarated'));
            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'translations'))); 
        }
        
        //create language
        if(Core::post('locale'))
        {
            $language   = $this->request->post('locale');
            $folder     = DOCROOT.'languages/'.$language.'/LC_MESSAGES/';
            
            // if folder does not exist, try to make it
            if ( !file_exists($folder) AND ! @mkdir($folder, 0775, true)) { // mkdir not successful ?
                Alert::set(Alert::ERROR, __('Language folder cannot be created with mkdir. Please correct to be able to create new translation.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'translations')));  
            };
            
            // write an empty .po file for $language
            $out = 'msgid ""'.PHP_EOL;
            $out .= 'msgstr ""'.PHP_EOL;
            File::write($folder.'messages.po', $out);
            
            Alert::set(Alert::SUCCESS, $this->request->param('id').' '.__('Language saved'));
        }

        $this->template->content = View::factory('oc-panel/pages/translations/index',array('languages' => i18n::get_languages(),
                                                                                            'current_language' => core::config('i18n.locale')
                                                                                            ));

    }

    public function action_edit()
    {
        $user = Auth::instance()->get_user();
        $language   = $this->request->param('id');

        //be sure is correct capital letters
        if (strlen($language)==5)
            $language = substr($language, 0,3).strtoupper(substr($language, 3,5));

        $mo_translation = DOCROOT.'languages/'.$language.'/LC_MESSAGES/messages.po';

        if(!file_exists($mo_translation))
        {
            Alert::set(Alert::ERROR, $language);
            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'translations')));
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Translation')));  
        $this->template->title = __('Edit Translation');     
        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/translations/edit');

        $this->template->scripts['footer'][] = 'js/oc-panel/translations.js';

        $base_translation = i18n::get_language_path();

        //pear gettext scripts
        require_once Kohana::find_file('vendor', 'GT/Gettext','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/PO','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/MO','php');
        //.po to .mo script
        require_once Kohana::find_file('vendor', 'php-mo/php-mo','php');

        //load the .po files
        //original en translation
        $pocreator_en = new File_Gettext_PO();
        $pocreator_en->load($base_translation);
        //the translation file
        $pocreator_translated = new File_Gettext_PO();
        $pocreator_translated->load($mo_translation);

        //get an array with all the strings
        $en_array_order = $pocreator_en->strings;

        //order the en words with SORT_NATURAL since php 5.4
        if (defined('SORT_NATURAL'))
            ksort($en_array_order,SORT_NATURAL);
        else
            ksort($en_array_order);
        
        //array with translated language may contain missing from EN
        $origin_translation = $pocreator_translated->strings;

        //lets get the array with translated values and sorted, will include everything even if was not previously saved
        $translation_array  = array();
        $array_untranslated = array();//keep track of words not translated stores ID
        
        $i = 0;
        foreach ($en_array_order as $origin => $value) 
        {
            //do we have the translation?
            if (isset($origin_translation[$origin]) AND !empty($origin_translation[$origin])>0)
            {
                $translated = $origin_translation[$origin];
            }
            else
            {
                $array_untranslated[] = $i;
                $translated = '';
            }

            $translation_array[] = array('original' => $origin,
                                         'translated' => $translated);

            $i++;
        }

        //watch out at any standard php installation there's a limit of 1000 posts....edit php.ini max_input_vars = 10000 to amend it.
        if($this->request->post() AND $this->request->post('translations'))
        {
            $translations = $this->request->post('translations');

            //changing the translation_array with the posted values
            foreach($translations as $key => $value)
            {
                if (isset($translation_array[$key]['translated']))
                    $translation_array[$key]['translated'] = $value;
            }

            //let's generate a proper .po file for the mo converter
            $out = '';

            foreach($translation_array as $key => $values)
            {
                list($original,$translated) = array_values($values);
                if ($translated!='')
                {
                    //only adding translated items
                    $out .= '#: String '.$key.PHP_EOL;
                    $out .= 'msgid "'.$original.'"'.PHP_EOL;
                    $out .= 'msgstr "'.$translated.'"'.PHP_EOL;
                    $out .= PHP_EOL;
                }
            }

            //write the generated .po to file
            file_put_contents($mo_translation, $out, LOCK_EX);

            //generate the .mo from the .po file
            phpmo_convert($mo_translation);

            //we regenerate the file again to be poedit friendly
            $out = 'msgid ""
msgstr ""
"Project-Id-Version: '.Core::VERSION.'\n"
"POT-Creation-Date: '.Date::unix2mysql().'\n"
"PO-Revision-Date: '.Date::unix2mysql().'\n"
"Last-Translator: '.$user->name.' <'.$user->email.'>\n"
"Language-Team: en\n"
"Language: '.strtolower(substr($language,0,2)).'\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset='.i18n::$charset.'\n"
"Content-Transfer-Encoding: 8bit\n"
"X-Generator: Open Classifieds '.Core::VERSION.'\n"'.PHP_EOL.PHP_EOL;

            foreach($translation_array as $key => $values)
            {
                list($original,$translated) = array_values($values);
                //only adding translated items
                $out .= '#: String '.$key.PHP_EOL;
                $out .= 'msgid "'.$original.'"'.PHP_EOL;
                $out .= 'msgstr "'.$translated.'"'.PHP_EOL;
                $out .= PHP_EOL;
            }

            //write the generated .po to file
            file_put_contents($mo_translation, $out, LOCK_EX);


            Alert::set(Alert::SUCCESS, $language.' '.__('Language saved'));
            $this->redirect(URL::current());
        }

        //add filters to search
        $translation_array_filtered = $translation_array;

        //only display not translated
        if (core::get('translated')==1)
        {
            $translation_array_filtered_aux = array();
            foreach ($array_untranslated as $key=>$value ) 
            {
                $translation_array_filtered_aux[$value] =  $translation_array_filtered[$value];
            }

            $translation_array_filtered = $translation_array_filtered_aux;
        }

        //how many translated items we have?
        $total_items = count($translation_array);


        //get elements for current page
        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $total_items,
                    'items_per_page' => 100,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
                    'id'         => $language,
        ));

        $trans_array_paginated = array();
        $from = $pagination->offset;
        $to   = $from + $pagination->items_per_page;

        for ($key=$from; $key <$to ; $key++) 
        { 
            if (isset($translation_array_filtered[$key]))
                $trans_array_paginated[$key] = $translation_array_filtered[$key];
        }

        $content->edit_language     = $language;
        $content->translation_array = $trans_array_paginated;
        $content->cont_untranslated = count($array_untranslated);
        $content->total_items       = $total_items;
        $content->pagination        = $pagination->render();

    }



}//end of controller
