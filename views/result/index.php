<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\Report;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Results');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="result-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => Report::find()->where(['patient_id'=>Yii::$app->user->id]),
        'pagination' => [
            'pageSize' => 50,
        ],
    ])
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'description',
            'creation_date',
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{print} {email}',
              'buttons' => [
                'print' => function ($modelGrid, $key, $index) {
                        $url = Url::toRoute($modelGrid);
                        return Html::a('<span class="glyphicon glyphicon-download"></span>', 'result/download-report?id=' . $index, [
                                    'title' => \Yii::t('yii', 'Download Report'),
                                    'data-method' => 'get',
                        ]);
                },
                'email' => function ($modelGrid, $key, $index) {
                        $url = Url::toRoute($modelGrid);
                        return Html::a('<span class="glyphicon glyphicon-envelope"></span>', 'result/email?id=' . $index, [
                                    'title' => \Yii::t('yii', 'Email Report'),
                                    'data-method' => 'post',
                        ]);
                }
              ]
            ],
        ],
    ]); ?>

</div>
