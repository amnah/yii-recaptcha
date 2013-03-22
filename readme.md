KRecaptcha
=============================

Yii recaptcha extension

## Installation

* Extract files into **protected/extensions/krecaptcha**
* Copy **protected/extensions/krecaptcha/KRecaptchaExt.php** to **protected/extensions/KRecaptchaExt.php**

## Usage

* Ensure that you have a model with an appropriate "recaptcha" attribute
* Call widget in view file (take note of the **style** attribute, as that determines the function it calls)

```php
<?php $this->widget('application.extensions.KRecaptchaExt', array(
    'model' => $model,
    'attribute' => 'recaptcha',
    'publicKey' => '<recaptcha public key>',
    'style' => 'custom',
)); ?>
```

* Change recaptcha styling if desired by modifying the **KRecaptchaExt.php** file. Either modify `runCustom()` or create a new function

```php
/**
 * The styling of the recaptcha widget, which will decide which function to use
 * For example,
 * $style = "default"   =>     $this->runDefault();
 * $style = "custom"    =>     $this->runCustom();
 * @var string
 */
```

* Add validator to model rules()

```php
public function rules() {
	return array(
		...
		array('recaptcha', 'required'),
		array('recaptcha', 'application.extensions.krecaptcha.KRecaptchaValidator', 'privateKey' => '<recaptcha private key>'),
		...
	);
}
```