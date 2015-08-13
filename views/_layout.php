<!DOCTYPE HTML>
<html>
    <head>
        <title><?=$model->title?> - PWResource</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <?php //$model->renderScript("jquery.ui.position.js"); ?>
        <?php $model->renderScript("jquery.contextMenu.js"); ?>
        <?php $model->renderStyle("jquery.contextMenu.css"); ?>
        <?php $model->renderStyle("site.css"); ?>
        <?php $model->renderScript(); ?>
        <?php $model->renderStyle(); ?>
        <?php $model->renderScript("general.js"); ?>
    </head>
    <body>
        <main>
            <?php $model->output(); ?>
        </main>
    </body>
</html>