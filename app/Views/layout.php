<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" href="/favicon.ico" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#000000" />
    <title><?= !empty($title) ? $title : "" ?></title>


    <?php if (!empty($meta) && is_array($meta)) : ?>

        <?php foreach ($meta as $value) : ?>

            <meta name="<?= $value->name ?>" content="<?= $value->content ?>">

        <?php endforeach; ?>

    <?php endif; ?>
    <link rel="apple-touch-icon" href="/logo192.png" />
    <link rel="manifest" href="/manifest.json" />

    <link rel="stylesheet" href="/assets/css/styles.css?<?= date("s"); ?>">
    <script defer src="/assets/js/app.bundle.js?<?= date("s"); ?>"></script>
    <script defer src="/assets/js/runtime.bundle.js?<?= date("s"); ?>"></script>
    <script defer src="/assets/js/shared.bundle.js?<?= date("s"); ?>"></script>
    <script defer src="/assets/js/shared-dom.bundle.js?<?= date("s"); ?>"></script>
    <script defer src="/assets/js/shared-router-dom.bundle.js?<?= date("s"); ?>"></script>
</head>

<body>
    <div id="root"></div>
</body>

</html>