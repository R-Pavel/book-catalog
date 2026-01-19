<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var array $authors */

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'year')->textInput() ?>
<?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'coverFile')->fileInput() ?>

<?= $form->field($model, 'author_ids')->checkboxList(
    ArrayHelper::map($authors, 'id', 'full_name'),
    ['prompt' => 'Choose athors']
) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>