<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Pathology Lab Reporting System',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if(Yii::$app->user->id != null) {
      $user = User::findOne(Yii::$app->user->id);
    }

    $items = [['label' => 'Home', 'url' => ['/site/index']]];
    if(!Yii::$app->user->isGuest && $user->type != "U") {
      $items[] = ['label' => 'Users', 'url' => ['/user']];
      $items[] = ['label' => 'Tests', 'url' => ['/test']];
      $items[] = ['label' => 'Reports', 'url' => ['/report']];
      $items[] = ['label' => 'Logout', 'url' => ['/site/logout']];
    }
    elseif(!Yii::$app->user->isGuest && $user->type == "U") {
      $items[] = ['label' => 'Results', 'url' => ['/result']];
      $items[] = ['label' => 'Logout', 'url' => ['/site/logout']];
    }
    else {
      $items[] = ['label' => 'Login', 'url' => ['/site/login']];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Avrea <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
