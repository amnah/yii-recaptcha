<?php

class KRecaptchaBase extends CInputWidget {

    /**
     * Public key for reCAPTCHA
     * @var string
     */
    public $publicKey;

    /**
     * The styling of the recaptcha widget, which will decide which function to use
     * For example,
     * $style = "default"   =>     $this->runDefault();
     * $style = "custom"    =>     $this->runCustom();
     * @var string
     */
    public $style = "default";

    /**
     * Init
     */
    public function init() {
        // call parent and include recaptcha library
        parent::init();
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'recaptchalib.php';
    }

    /**
     * Run widget
     */
    public function run() {
        // get public key from4 settings if not set yet
        $this->publicKey = $this->publicKey ? $this->publicKey : Yii::app()->params['kRecaptchaPublic'];

        // calculate and call method name
        $methodName = "run" . ucfirst($this->style);
        $this->$methodName();
    }

    /**
     * Displays default style recaptcha
     */
    public function runDefault() {
    
        // add js client validation for required
        $this->_addClientRequiredValidation();
        
        // display default recaptcha theme
        echo recaptcha_get_html($this->publicKey, null, Yii::app()->request->isSecureConnection);
        
    }

    /**
     * Adds js client-side required validation script into document ready
     * We have to handle client validation manually because recaptcha requires the input id to be "recaptcha_challenge_field"
     * (so we cannot use Yii's built in required validator, which uses an automatically generated input id)
     */
    protected function _addClientRequiredValidation() {
    
        // set message for required
        $message = Yii::t('yii','{attribute} cannot be blank.');
        $message = strtr($message, array(
            '{attribute}' => $this->model->getAttributeLabel($this->attribute),
        ));

        // add script
        $scriptValidation = <<<JS
    $("#recaptcha_response_field").blur(function() {
        var widgetDiv = $("#recaptcha_widget_div");
        if ($.trim($(this).val()) == "") {
            $(this).addClass("error");
            widgetDiv.siblings("label").addClass("error");
            widgetDiv.siblings("div.errorMessage").html('$message').show();

        }
        else {
            widgetDiv.siblings("label").removeClass("error");
            widgetDiv.siblings("div.errorMessage").hide();
            $(this).removeClass("error");
        }
    });
JS;
        // register in document ready
        Yii::app()->clientScript->registerScript('krecaptcha_client_validation', $scriptValidation, CClientScript::POS_END);
    }
}

?>