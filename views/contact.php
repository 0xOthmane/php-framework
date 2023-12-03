<?php

/** @var app\core\View $this  */
/** @var app\models\ContactForm $model  */

use app\core\form\Form;
use app\core\form\TextareaField;

$this->title = 'Contact';
?>
<h1>Contact</h1>
<?php $form = Form::begin('', 'POST') ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'email') ?>
<?= new TextareaField($model, 'body') ?>
<button type="submit" class="btn btn-primary">Submit</button>

<?php $form = Form::end() ?>