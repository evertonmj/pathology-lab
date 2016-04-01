<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Report;
use app\models\Result;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Report */
$model = Report::findOne($_GET['id']);
$operatorName = $model->operator->fullname;
$patientName = $model->patient->fullname;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
              'attribute' => 'operator_id',
              'value' => $operatorName
            ],
            [
              'attribute' => 'patient_id',
              'value' => $patientName
            ],
            'creation_date',
            'description',
        ],
    ]) ?>

    <?php
      //shows results already added
      $dataProvider = new ActiveDataProvider([
          'query' => Result::find()->where(['report_id'=>$model->id]),
          'pagination' => [
              'pageSize' => 50,
          ],
      ]);
      Pjax::begin(['id' => 'results-grid']);
      echo GridView::widget([
          'dataProvider' => $dataProvider,
          'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              [
                'attribute' => 'test_id',
                'value' => 'test.name'
              ],
              'final_value',
          ],
      ]);
      Pjax::end();
    ?>

</div>
