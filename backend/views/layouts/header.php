<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">万岸</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->

                <li class="dropdown user user-menu">
                        <?= Html::a(
                            "退出 <span>（".  Yii::$app->user->identity->username ."）</span>",
                            ['/site/logout'],
                            ['data-method' => 'post', 'class' => 'dropdown-toggle']
                        ) ?>
                </li>

            </ul>
        </div>
    </nav>
</header>
