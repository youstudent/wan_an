<aside class="main-sidebar">

    <section class="sidebar">
        <?php
        $menu = \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id);
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                "encodeLabels" => false,
                "options" => ["class" => "sidebar-menu"],
                'items' => $menu
            ]
        ) ?>

    </section>

</aside>
