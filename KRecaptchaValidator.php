<?php

class KRecaptchaValidator extends CValidator {

    /**
     * Skip if there are any other errors (e.g., required)
     * @var bool
     */
    public $skipOnError = true;

    /**
     * Private key for reCAPTCHA (can be set in model rules()
     * @var string
     */
    public $privateKey;

    /**
     * Constructor (CValidator does not have an init() function)
     */
    public function __construct() {
        // include recaptcha library
        require_once dirname(__FILE__) . '/recaptcha/recaptchalib.php';

        // note: validator has no parent::__construct()
    }


    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel the object being validated
     * @param string the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {
        // get public key from settings if not set yet
        $this->privateKey = $this->privateKey ? $this->privateKey : Yii::app()->params['krecaptcha']['private'];

        // get input value
        $value = $object->$attribute;

        // check input using recaptcha api
        $resp = recaptcha_check_answer($this->privateKey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $value);
        if (!$resp->is_valid) {
            $message = $this->message !== null ? $this->message : Yii::t('yii', 'The verification code is incorrect.');
            $this->addError($object, $attribute, $message);
        }
    }
}