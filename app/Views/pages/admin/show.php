<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="pull-right">
                <button class="btn btn-default" type="button" onclick="removeAuth()">Logout</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <input id="path" class="form-control" value="/json<?= $path->realpath ?>" readonly />
        </div>
    </div>
    <div class="row" id="data-array" style="display:none">
        <div class="col-sm-12">

            <div class="row">
                <div class="col-sm-12">
                    <div class="pull-left">
                        <a href="/admin" class="btn btn-default">to Path Index</a>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-default" type="button" onclick="onOpenAddModal()">Add New</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <select id="page-limit" class="form-control" onchange="onChangePageLimit(event.target.value)">
                        <option value="all" <?= empty($_GET['limit']) ? "selected" : "" ?>>All</option>
                        <option value="5" <?= !empty($_GET['limit']) && $_GET['limit'] == "5" ? "selected" : "" ?>>5</option>
                        <option value="10" <?= !empty($_GET['limit']) && $_GET['limit'] == "10" ? "selected" : "" ?>>10</option>
                        <option value="15" <?= !empty($_GET['limit']) && $_GET['limit'] == "15" ? "selected" : "" ?>>15</option>
                        <option value="20" <?= !empty($_GET['limit']) && $_GET['limit'] == "20" ? "selected" : "" ?>>20</option>
                    </select>
                </div>
                <div class="col-sm-10">
                    <?php if (!empty($_GET['limit'])) : ?>
                        <button class="btn btn-default" type="button" onclick="onChangePage(<?= !empty($_GET['page']) ? $_GET['page'] - 1 : 0 ?>)">Prev</button>
                        <button class="btn btn-default" type="button" onclick="onChangePage(<?= !empty($_GET['page']) ? $_GET['page'] + 1 : 2 ?>)">Next</button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Keyword</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row" id="data-single" style="display:none">
        <div class="col-sm-12">
            <a href="/admin<?= $path->full_path ?>" class="btn btn-default">to Index</a>
            <form id="edit-form" onsubmit="onSubmitEdit(event)">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Default Fields</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Title" name="title" autocomplete="off" onkeyup="onAlterSlug(event)" onchange="onAlterSlug(event)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Slug</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Slug" name="slug" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Keywords</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Keywords" name="keywords" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Description" name="description" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Custom Fields</h4>
                    </div>
                    <div class="panel-body">
                        <?php foreach ($fields as $field) : ?>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><?= $field->label ?></label>
                                <div class="col-sm-10">
                                    <?php if ($field->type !== "textarea") : ?>
                                        <input type="<?= $field->type ?>" class="form-control edit" placeholder="<?= $field->label ?>" name="field[<?= $field->name ?>]" value="<?= $field->default_value ?>" autocomplete="off">
                                    <?php else : ?>
                                        <textarea class="form-control edit" placeholder="<?= $field->label ?>" name="field[<?= $field->name ?>]"><?= $field->default_value ?></textarea>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" id="editSubmit" style="display:none"></button>
            </form>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('editSubmit').click()">Save changes</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('addModal')"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Data</h4>
            </div>
            <div class="modal-body" id="add-form">
                <form class="form-horizontal" onsubmit="onSubmit(event)">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Default Fields</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Title" name="title" autocomplete="off" onkeyup="onAlterSlug(event,'addModal')" onchange="onAlterSlug(event,'addModal')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Slug" name="slug" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Keywords</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Keywords" name="keywords" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Description" name="description" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Custom Fields</h4>
                        </div>
                        <div class="panel-body">
                            <?php foreach ($fields as $field) : ?>
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label"><?= $field->label ?></label>
                                    <div class="col-sm-10">
                                        <?php if ($field->type !== "textarea") : ?>
                                            <input type="<?= $field->type ?>" class="form-control" placeholder="<?= $field->label ?>" name="field[<?= $field->name ?>]" value="<?= $field->default_value ?>" autocomplete="off">
                                        <?php else : ?>
                                            <textarea class="form-control" placeholder="<?= $field->label ?>" name="field[<?= $field->name ?>]"><?= $field->default_value ?></textarea>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="addSubmit" type="submit" class="btn btn-default" style="display: none;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal('addModal')">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('addSubmit').click()">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('deleteModal')"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="onDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    var deleteId = "";
    var total = 0;
    var limit = <?= !empty($_GET['limit']) ? $_GET['limit'] : "'all'" ?>;
    var page = <?= !empty($_GET['page']) ? $_GET['page'] : 1 ?>;


    var elAlert = document.createElement("div");
    elAlert.classList.add("alert");
    elAlert.classList.add("alert-success");


    var elAlertDgr = document.createElement("div");
    elAlertDgr.classList.add("alert");
    elAlertDgr.classList.add("alert-danger");

    function selectedDeleteId(path) {
        deleteId = path;
        openModal("deleteModal");
    }

    function onGetNewPath(querySearch) {

        var path = document.getElementById("path").value;
        var actualpath = `${path}`.substring(5);

        var querySearchArr = []
        Object.keys(querySearch).forEach(function(key) {
            querySearchArr = [...querySearchArr, `${key}=${querySearch[key]}`]
        })

        var querySearchStr = ""

        if (querySearchArr.length > 0) {
            querySearchStr = `?${querySearchArr.join("&")}`
        }

        window.location.href = `/admin${actualpath}${querySearchStr}`;
    }

    function onChangePageLimit(value) {
        var getArr = <?= json_encode(count($_GET) > 0 ? $_GET : (object) []) ?>;
        getArr = {
            ...getArr,
            limit: value
        };
        if (getArr.limit == "all") {
            delete getArr.limit;
            delete getArr.page;
        }

        onGetNewPath(getArr)
    }

    function onChangePage(value) {
        var getArr = <?= json_encode(count($_GET) > 0 ? $_GET : (object) []) ?>;
        getArr = {
            ...getArr,
            page: value
        };
        if (getArr.page == 1) {
            delete getArr.page;
        }

        if (value > 0 && value <= Math.ceil(total / getArr.limit)) {
            onGetNewPath(getArr)
        }
    }

    function onGetData() {
        var path = document.getElementById("path").value;
        var actualpath = `${path}`.substring(5);

        var querySearch = <?= json_encode(count($_GET) > 0 ? $_GET : (object) []) ?>;

        var querySearchArr = []
        Object.keys(querySearch).forEach(function(key) {
            querySearchArr = [...querySearchArr, `${key}=${querySearch[key]}`]
        })

        var querySearchStr = ""

        if (querySearchArr.length > 0) {
            querySearchStr = `?${querySearchArr.join("&")}`
        }

        fetch(`${path}${querySearchStr}`, {
            method: "get",
        }).then(function(res) {
            return res.json()
        }).then(function(result) {
            total = result.data.total;
            if (Array.isArray(result.data.data)) {
                var no = 1;
                if (limit != "all") {
                    no = (page * limit) - (limit - 1);
                }
                var elementArray = document.getElementById("data-array");
                elementArray.style.display = "block";
                result.data.data.forEach((item) => {
                    elementArray.querySelector("tbody").innerHTML = elementArray.querySelector("tbody").innerHTML + `
                    <tr>
                        <td>${no}.</td>
                        <td>${item.title}</td>
                        <td>${item.keywords}</td>
                        <td>${item.description}</td>
                        <td><button class="btn btn-danger" type="button" onclick="selectedDeleteId('${actualpath}/${item.slug}')">Delete</button><a class="btn btn-default" href="/admin${actualpath}/${item.slug}" role="button">Open</a></td>
                    </tr>
                    `;
                    no++;
                })
            } else {
                var elementSingle = document.getElementById("data-single");
                elementSingle.style.display = "block";
                var elementDefField = document.getElementById("default-fields");


                var elFormEdit = document.getElementById("edit-form");

                elFormEdit.querySelector("input[name=title]").value = result.data.title;
                elFormEdit.querySelector("input[name=slug]").value = result.data.slug;
                elFormEdit.querySelector("input[name=keywords]").value = result.data.keywords;
                elFormEdit.querySelector("input[name=description]").value = result.data.description;
                var fields = <?= json_encode($fields) ?>;

                fields.forEach((item) => {
                    var selector = `input[name='field[${item.name}]']`;
                    if (item.type == "textarea") {
                        selector = `textarea[name='field[${item.name}]']`;
                    }
                    document.querySelector(selector).value = result.data[item.name] != null ? result.data[item.name] : "";
                })

            }
        });
    }

    function onOpenAddModal() {
        openModal("addModal")
    }

    function onDelete() {
        fetch(`/json${deleteId}`, {
            method: "delete",
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function() {
            selected = null;
            closeModal("deleteModal");
            window.location.reload();
        }).catch(function() {
            closeModal("deleteModal");
        })
    }

    function alteringSlug(title, id) {
        if (title !== "") {

            title = title.replace(/[^a-zA-Z0-9-_\s]/g, '');
            title = title.replace(/\s/g, '-');

            const lastChar = title.slice(-1);
            if (lastChar === '-' || lastChar === '_') {
                title = title.slice(0, -1);
            }

            title = title.toLowerCase()
        }
        document.getElementById(id).querySelector("input[name=slug]").setAttribute("value", title)
    }

    function onAlterSlug(e, id) {
        let title = e.target.value
        alteringSlug(title, id);
    }

    function onSubmit(e) {
        e.preventDefault();
        elAlert.remove();
        elAlertDgr.remove();
        var path = document.getElementById("path").value;
        var form = new FormData(e.target);

        fetch(path, {
            method: "post",
            body: form,
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function(res) {

            res.json().then(function(data) {
                if (res.status >= 200 && res.status < 300) {
                    document.getElementById("add-form").appendChild(elAlert);
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
                    document.getElementById("add-form").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                } else {
                    elAlertDgr.innerHTML = "Something went wrong!";
                    document.getElementById("add-form").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                }

            })
        })
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

    onGetData();
</script>