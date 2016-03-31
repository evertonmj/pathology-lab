<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;
use app\models\Result;
use app\models\Test;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Report */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'patient_id')->dropdownList(
      User::find('type = "U"')->select(['fullname', 'id'])->indexBy('id')->column(),
      ['prompt'=>'Select Patient']
    );?>

    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php
        if($model->id != null) {
          echo Html::button('Add New Result', ['value' => Url::to(['result/create']), 'title' => 'Creating New result', 'class' => 'showModalButton btn btn-success']);
        }
        ?>
    </div>

    <h3>Results</h3>

    <?php
      //shows results already added
      $dataProvider = new ActiveDataProvider([
          'query' => Result::find('report_id = :report_id', ['report_id'=>$model->id]),
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

              ['class' => 'yii\grid\ActionColumn'],
          ],
      ]);
      Pjax::end();
    ?>
    <?php ActiveForm::end(); ?>

    <?php
    //open modal to insert new results
    Modal::begin([
      'header' => '<span id="Add a Result"></span>',
      'headerOptions' => ['id' => 'modalHeader'],
      'id' => 'modal',
      'size' => 'modal-lg',
      //keeps from closing modal with esc key or by clicking out of the modal.
      // user must click cancel or X to close
      //'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    ]); ?>

    <?php $formResult = ActiveForm::begin(['action'=>Yii::$app->homeUrl . 'result/add-result', 'options' => ['id' => 'create-result-form', 'enableClientValidation'=>false]]);
      $modelResult = new Result();
    ?>

    <?= $formResult->field($modelResult, 'test_id')->dropdownList(
      Test::find()->select(['name', 'id'])->indexBy('id')->column(),
      ['prompt'=>'Select Test']
    );?>

    <?= Html::hiddenInput('Result[report_id]', $model->id); ?>

    <?= $form->field($modelResult, 'final_value')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php yii\bootstrap\Modal::end();?>
</div>
<?php
$this->registerJs('
    $("#create-result-form").submit(function(e) {
        e.preventDefault();
        $(".form-group").removeClass("has-error");      //remove error class
        $(".help-block").html("");                      //remove existing error messages

        var form_data = $("#create-result-form").serialize();
        var action_url = $("#create-result-form").attr("action");

        $.ajax({
            method: "POST",
            url: action_url,
            data: form_data
        })
        .done(function( data ) {
            //console.log(data);
            if(data.success == true)    {       //data saved successfully
              $.pjax.reload({container: "#results-grid"});
              $("#modal").modal("hide");
            }   else    {//validation errors occurred
                $.each(data.error, function(ind, vl) {      //show errors to user
                    $(".field-contactform-"+ind).addClass("has-error");
                    $(".field-contactform-"+ind).find(".help-block").html(vl[0]);
                });
            }

        });
        return false;
    });', \yii\web\View::POS_READY, 'result-ajax-form-submit');
