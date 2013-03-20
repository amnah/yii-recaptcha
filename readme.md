KRecaptcha
=============================

Yii recaptcha extension

## Installation

* Extract files into **protected/extensions/krecaptcha**

## Usage

* Ensure that you have a model with an appropriate "recaptcha" attribute
* Call widget in view file

```php
<?php $this->widget('application.extensions.krecaptcha.KRecaptcha', array(
    'model' => $model,
    'attribute' => 'recaptcha',
    'publicKey' => '<recaptcha public key>',
    'style' => 'custom',
)); ?>
```

* Change recaptcha styling if desired by extending the **KRecaptcha** class and adding a new function

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