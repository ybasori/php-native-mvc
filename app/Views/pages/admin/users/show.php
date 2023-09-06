<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <input id="path" class="form-control" value="<?= $path->realpath ?>" readonly />
        </div>
    </div>

    <div class="row" id="data-single">
        <div class="col-sm-12">
            <a href="/admin/users" class="btn btn-default">to Index</a>
            <form id="edit-form" onsubmit="onSubmitEdit(event)">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2">E-mail</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="E-mail" name="email" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="role">
                                    <option value="superadmin">Superadmin</option>
                                    <option value="admin">admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off" />
                                <span class="help-block">leave it empty if you don't want to change</span>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <button type="submit" id="editSubmit" style="display:none"></button>
        </form>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editSubmit').click()">Save changes</button>
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

    function onGetData() {
        var path = document.getElementById("path").value;




        fetch(`${path}`, {
            method: "get",
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function(res) {

            if (res.status >= 200 && res.status < 300) {
                return res.json()
            }

            if (res.status == 401) {
                document.getElementById("relogin").style.display = "block"
            }
        }).then(function(result) {
            total = result.data.total;
            var elementSingle = document.getElementById("data-single");


            var elFormEdit = document.getElementById("edit-form");

            elFormEdit.querySelector("input[name=username]").value = result.data.username;
            elFormEdit.querySelector("input[name=email]").value = result.data.email;
            elFormEdit.querySelector("select[name=role]").value = result.data.role;

            if (getAuth().username === result.data.username) {
                elFormEdit.querySelector("select[name=role]").setAttribute("disabled", "")
            }


        });
    }

    function onSubmitEdit(e) {
        e.preventDefault();
        elAlert.remove();
        elAlertDgr.remove();
        var path = document.getElementById("path").value;
        var form = new URLSearchParams(new FormData(e.target));

        fetch(path, {
            method: "put",
            body: form,
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function(res) {
            res.json().then(function(data) {
                if (res.status >= 200 && res.status < 300) {
                    document.getElementById("edit-form").appendChild(elAlert);
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
                    document.getElementById("edit-form").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                } else {
                    elAlertDgr.innerHTML = "Something went wrong!";
                    document.getElementById("edit-form").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                }

            })
        })

    }

    onGetData()
</script>