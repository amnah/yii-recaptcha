YiiRecaptcha
=============================

Yii Recaptcha extension. This simple extension was built to give developers full control over their recaptcha styling via custom theming.

Please see https://developers.google.com/recaptcha/docs/customization.

## Installation

* Install via composer - https://packagist.org/packages/amnah/yii-recaptcha
* OR extract files into vendor dir - **protected/vendor/amnah/yii-recaptcha**

## Usage

* Ensure that you have a model with an appropriate "recaptcha" attribute
* Add path alias (modify path as needed)

```php
Yii::setPathOfAlias('YiiRecaptcha',Yii::getPathOfAlias("application.vendor.amnah.yii-recaptcha"));
```

* Call widget in view file (take note of the **style** attribute, as that determines the function it calls)

```php
<?php $this->widget('YiiRecaptcha\Recaptcha', array(
    'model' => $model,
    'attribute' => 'recaptcha',
    'publicKey' => '<recaptcha public key>',
    'style' => 'custom',
)); ?>
```

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
        array('recaptcha', 'YiiRecaptcha\RecaptchaValidator', 'privateKey' => '<recaptcha private key>'),
		...
	);
}
```

## Extending

* Create a new file that extends the YiiRecaptcha\Recaptcha or YiiRecaptcha\RecaptchaValidator class
* Reference the new file instead of the original

```php
<?php $this->widget('application.extensions.MyRecaptcha', array(...)); ?>
```
