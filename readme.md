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
    'style' => 'custom', // "default" or "custom". extend if you want to theme yourself
)); ?>
```

* Add validator to model rules()

```php
public function rules() {
	return array(
		...
		array('recaptcha', 'required'),
		array('recaptcha', 'application.extensions.krecaptcha.KRecaptchaValidator'),
		...
	);
}
```