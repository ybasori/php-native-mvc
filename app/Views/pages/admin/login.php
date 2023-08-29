<div class="container-fluid">
    <div class="row" style="display:flex;justify-content:center;">
        <div class="col-sm-5">
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="nav nav-tabs nav-justified" style="margin-bottom: 3em">
                        <li role="presentation" data='login' class="active"><a href="javascript:void(0)" onclick="onTab(event, 'login')">Login</a></li>
                        <li role="presentation" data='register'><a href="javascript:void(0)" onclick="onTab(event, 'register')">Register</a></li>
                    </ul>
                    <form class="form-horizontal" id="login" onsubmit="onSubmitLogin(event)">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" placeholder="Email" name="email" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" value="1"> Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Sign in</button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="register" style="display:none" onsubmit="onSubmitRegister(event)">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" placeholder="Email" name="email" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Retype Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Password" name="retype_password" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Sign up</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var elAlert = document.createElement("div");
    elAlert.classList.add("alert");
    elAlert.classList.add("alert-success");


    var elAlertDgr = document.createElement("div");
    elAlertDgr.classList.add("alert");
    elAlertDgr.classList.add("alert-danger");

    function onTab(e, value) {
        e.target.parentElement.parentElement.querySelector("li[data='login']").classList.remove('active')
        e.target.parentElement.parentElement.querySelector("li[data='register']").classList.remove('active')


        e.target.parentElement.parentElement.querySelector(`li[data='${value}']`).classList.add('active')

        document.getElementById("login").style.display = "none"
        document.getElementById("register").style.display = "none"

        document.getElementById(value).style.display = "block"
    }

    function onSubmitLogin(e) {
        e.preventDefault();
        elAlert.remove();
        elAlertDgr.remove();

        var form = new FormData(e.target);

        fetch("/json/auth/login", {
            method: "post",
            body: form
        }).then(function(res) {
            res.json().then(function(data) {

                if (res.status >= 200 && res.status < 300) {
                    setAuth(data.data);
                    document.getElementById("login").appendChild(elAlert);
                    elAlert.innerHTML = "Success"
                    setTimeout(function() {
                        elAlert.innerHTML = "";
                        elAlert.remove();
                        window.location.reload()
                    }, 1000)
                } else if (res.status >= 400 && res.status < 500) {
                    elAlertDgr.innerHTML = "";
                    if (data.errors) {
                        var ul = document.createElement("ul");
                        Object.keys(data.errors).forEach(function(name) {
                            data.errors[name].forEach(function(item) {
                                var li = document.createElement("li");
                                li.innerHTML = item;
                                ul.appendChild(li);

                            })
                        })

                        elAlertDgr.appendChild(ul);
                    } else {
                        elAlertDgr.innerHTML = data.message;
                    }
                    document.getElementById("login").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                } else {
                    elAlertDgr.innerHTML = "Something went wrong!";
                    document.getElementById("login").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                }
            })

        })
    }

    function onSubmitRegister(e) {
        e.preventDefault();
        elAlert.remove();
        elAlertDgr.remove();

        if (e.target[name = 'retype_password'].value === e.target[name = 'password'].value) {
            var form = new FormData(e.target);

            fetch("/json/auth/register", {
                method: "post",
                body: form
            }).then(function(res) {
                res.json().then(function(data) {

                    if (res.status >= 200 && res.status < 300) {

                        document.getElementById("register").appendChild(elAlert);
                        elAlert.innerHTML = "Success"
                        setTimeout(function() {
                            elAlert.innerHTML = "";
                            elAlert.remove();
                        }, 1000)
                    } else if (res.status >= 400 && res.status < 500) {
                        elAlertDgr.innerHTML = "";
                        var ul = document.createElement("ul");

                        Object.keys(data.errors).forEach(function(name) {
                            data.errors[name].forEach(function(item) {
                                var li = document.createElement("li");
                                li.innerHTML = item;
                                ul.appendChild(li);

                            })
                        })

                        elAlertDgr.appendChild(ul);
                        document.getElementById("register").appendChild(elAlertDgr);
                    } else {
                        elAlertDgr.innerHTML = "Something went wrong!";
                        document.getElementById("register").appendChild(elAlertDgr);
                    }
                })

            }).then(function(res) {

            })
        } else {
            elAlertDgr.innerHTML = "Retype Password not matched!"
            document.getElementById("register").appendChild(elAlertDgr);

        }
    }
</script>