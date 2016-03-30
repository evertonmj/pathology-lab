<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Result */

$this->title = Yii::t('app', 'Create Result');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
