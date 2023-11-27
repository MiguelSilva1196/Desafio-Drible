<?php 
Class headertextmodule extends Module
{
    public function __construct()
    {
        $this->name = 'headertextmodule';
        $this->version = '1.00.0';
        $this->author = 'Miguel Silva';
        $this->displayName = $this->l('Header Text Module');
        $this->description = $this->l('Header Text Miguel Silva Module');
        $this->controllers = array('default');
        $this->bootstrap = 1;
        parent::__construct();
    }

    public function install()
    {
        if( !parent::install() || !$this->registerHook('displayBanner'))
            return false;
        return true;
    }

    public function uninstall()
    {
        if( !parent::uninstall() || !$this->unregisterHook('displayBanner'))
            return false;
        return true;
    }
    public function getContent()
    {
        return $this->postProcess() . $this->getForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('headertextmodule')) {
            $text = Tools::getValue('text');
            Configuration::updateValue('MODULE_HEADER_TEXT', $text);
            return $this->displayConfirmation($this->l('Updated Successfully'));
        }
    }

    public function getForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->context->controller->getLanguages();
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $this->context->controller->default_form_language;
        $helper->allow_employee_form_lang = $this->context->controller->allow_employee_form_lang;
        $helper->title = $this->displayName;

        $helper->submit_action = 'headertextmodule';
        $helper->fields_value['text'] = Configuration::get('MODULE_HEADER_TEXT');
        
        $this->form[0] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->displayName
                 ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Text'),
                        'desc' => $this->l('What text to show in the header ?'),
                        'hint' => $this->l(''),
                        'name' => 'text',
                        'lang' => false,
                     ),
                 ),
                'submit' => array(
                    'title' => $this->l('Save')
                 )
             )
         );
        return $helper->generateForm($this->form);
    }

    public function hookDisplayBanner()
    {
        $text = Configuration::get('MODULE_HEADER_TEXT');
        $this->context->smarty->assign(array(
            'header_text' => $text,
        ));
        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/banner.tpl');
    }
}
?>