<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($title) ? $title : "" ?></title>


    <?php if (!empty($meta) && is_array($meta)) : ?>

        <?php foreach ($meta as $value) : ?>

            <meta name="<?= $value->name ?>" content="<?= $value->content ?>">

        <?php endforeach; ?>

    <?php endif; ?>




    <script defer src="/assets/js/app.bundle.js"></script>
    <script defer src="/assets/js/runtime.bundle.js"></script>
    <script defer src="/assets/js/shared.bundle.js"></script>
    <script defer src="/assets/js/shared-dom.bundle.js"></script>
    <script defer src="/assets/js/shared-router-dom.bundle.js"></script>
</head>

<body>
    <div id="root"></div>
</body>

</html>