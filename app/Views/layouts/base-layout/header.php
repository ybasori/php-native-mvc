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
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <script>
        var backdropmodal = document.createElement("div");
        backdropmodal.classList.add("modal-backdrop")
        backdropmodal.classList.add("fade");

        function onArrayForm(name, data, obj) {
            var newObj = [...obj];
            Object.keys(data).forEach(function(key) {
                if (
                    (Array.isArray(data[key]) || typeof data[key] === "object") &&
                    !(data[key] instanceof File)
                ) {
                    newObj = onArrayForm(`${name}[${key}]`, data[key], newObj);
                } else {
                    newObj = [...newObj, {
                        label: `${name}[${key}]`,
                        value: data[key]
                    }];
                }
            })
            return newObj;
        };

        function expandJSON(data) {
            var obj = [];
            Object.keys(data).forEach(function(key) {
                if (
                    Array.isArray(data[key]) ||
                    (typeof data[key] === "object" && !(data[key] instanceof File))
                ) {
                    obj = onArrayForm(`${key}`, data[key], obj);
                } else {
                    obj = [
                        ...obj,
                        {
                            label: key,
                            value: data[key],
                        },
                    ];
                }
            })
            return obj;
        };

        function openModal(id) {
            var el = document.getElementById(id);
            el.style.display = 'block';
            el.classList.add('in');
            var body = document.body;
            body.classList.add('modal-open');
            body.style['padding-right'] = '17px'

            body.appendChild(backdropmodal);
            backdropmodal.classList.add("in")

            return new Promise((resolve) => {
                resolve();
            })
        }

        function closeModal(id) {
            var el = document.getElementById(id);
            el.style.display = 'none';
            el.classList.remove('in');
            var body = document.body;
            body.classList.remove('modal-open');
            body.style = ''
            backdropmodal.remove();
        }

        function setAuth(value) {
            localStorage.setItem("auth", JSON.stringify(value))
        }

        function getAuth() {
            if (localStorage.getItem("auth")) {
                return JSON.parse(localStorage.getItem("auth"))
            }
            return null
        }

        function removeAuth() {
            localStorage.removeItem("auth")
            window.location.href = "/admin/login?redirect=" + encodeURI(window.location.pathname + window.location.search)
        }

        if (!getAuth()) {
            if (window.location.pathname !== "/admin/login") {
                window.location.href = "/admin/login" + window.location.search
            }
        } else {
            if (window.location.pathname === "/admin/login") {
                window.location.href = "/admin" + window.location.search
            }
        }
    </script>
</head>

<body>