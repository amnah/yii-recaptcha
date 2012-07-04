<?php

// require base class
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'KRecaptchaBase.php';

class KRecaptcha extends KRecaptchaBase {

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
        custom_theme_widget: 'recaptcha_widget_div',
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

?>