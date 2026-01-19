<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var array $authors */

?>

    <h1><?= Html::encode('Create book') ?></h1>

<?= $this->render('_form', [
    'model' => $model,
    'authors' => $authors,
]) ?>