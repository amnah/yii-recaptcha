KRecaptcha
=============================

Yii recaptcha extension. This simple extension was built to give developers full control over their recaptcha styling via custom theming.

Please see https://developers.google.com/recaptcha/docs/customization.

## Installation

* Extract files into **protected/extensions/krecaptcha**

## Usage

* Ensure that you have a model with an appropriate "recaptcha" attribute
* Call widget in view file (take note of the **style** attribute, as that determines the function it calls)

```php
<?php $this->widget('application.extensions.krecaptcha.KRecaptcha', array(
    'model' => $model,
    'attribute' => 'recaptcha',
    'publicKey' => '<recaptcha public key>',
    'style' => 'custom',
)); ?>
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

## Extending

* Creating a new file that extends the KRecaptcha or KRecaptchaValidator class
* Reference the new file instead of the original

```php
<?php $this->widget('application.extensions.MyRecaptcha', array(...)); ?>
```
