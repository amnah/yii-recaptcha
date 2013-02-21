<?php

class KRecaptcha extends CInputWidget {

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
        require_once dirname(__FILE__) . '/recaptcha/recaptchalib.php';
    }

    /**
     * Run widget
     */
    public function run() {
        // get public key from4 settings if not set yet
        $this->publicKey = $this->publicKey ? $this->publicKey : Yii::app()->params['krecaptcha']['public'];

        // calculate and call method name
        $methodName = "run" . ucfirst($this->style);
        $this->$methodName();
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

    /**
     * Displays default style recaptcha
     */
    public function runDefault() {

        // add js client validation for required
        $this->_addClientRequiredValidation();

        // set recaptcha options for theme
        // valid options are: red, white, blackglass, clean
        $scriptOptions = <<<JS
    var RecaptchaOptions = {
        theme : 'blackglass'
    };
JS;

        // register script in head (must come before actual recaptcha call)
        Yii::app()->clientScript->registerScript('krecaptcha_options', $scriptOptions, CClientScript::POS_HEAD);

        // display default recaptcha theme
        echo recaptcha_get_html($this->publicKey, null, Yii::app()->request->isSecureConnection);

    }

    /**
     * Displays custom style recaptcha using recaptcha's custom theming
     * @link http://code.google.com/apis/recaptcha/docs/customization.html#Custom_Theming
     */
    public function runCustom() {

        // add js client validation for required
        $this->_addClientRequiredValidation();

        // set recaptcha options for custom theme
        $scriptOptions = <<<JS
    var RecaptchaOptions = {
        theme : 'custom',
        custom_theme_widget: 'recaptcha_widget_div'
    };
JS;

        // register script in head (must come before actual recaptcha call)
        Yii::app()->clientScript->registerScript('krecaptcha_options', $scriptOptions, CClientScript::POS_HEAD);

        // determine secure or normal call
        $recaptcha_server = Yii::app()->request->isSecureConnection ? RECAPTCHA_API_SECURE_SERVER : RECAPTCHA_API_SERVER;

        // set <input> text
        $input = CHtml::activeTextField($this->model, $this->attribute, array(
            "size"=> 32,
            "id"  => "recaptcha_response_field"
        ));

        // echo the custom style
        // note: use the same id as recaptcha's default theme - $("#recaptcha_widget_div")
        echo <<<HTML
    <div id="recaptcha_widget_div" style="display: none;">

        <div id="recaptcha_image" style="border: 1px solid black;"></div>

        {$input}

        <a href="javascript:Recaptcha.reload()">Generate new</a>

        <!--div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div-->

        <!--span class="recaptcha_only_if_image">Enter the words above:</span-->
        <!--span class="recaptcha_only_if_audio">Enter the numbers you hear:</span-->

        <!--div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div-->
        <!--div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div-->

        <script type="text/javascript" src="{$recaptcha_server}/challenge?k={$this->publicKey}"></script>
        <noscript>
            <iframe src="{$recaptcha_server}/noscript?k={$this->publicKey}" height="300" width="500" frameborder="0"></iframe><br>
            <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
            <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
        </noscript>

    </div>
HTML;

    }
}