<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title">
                        Default Path
                    </h1>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Path</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Login</td>
                                    <td>/json/v1/auth/login</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Register</td>
                                    <td>/json/v1/auth/register</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td>Users</td>
                                    <td>/json/v1/admin/users</td>
                                    <td>
                                        <a class="btn btn-default" href="/admin/users">Open</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5.</td>
                                    <td>Author</td>
                                    <td>/json/v1/author/[username]</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title">Custom Path</h1>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
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
                    <div id="all-path" class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>
                                        <div class="pull-left">Name</div>
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default <?= $_GET['sort']['name'] == "asc" ? "active" : "" ?>" type="button" onclick="onChangeSort('name','asc')">
                                                <i class="glyphicon glyphicon-chevron-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-default <?= $_GET['sort']['name'] == "desc" ? "active" : "" ?>" type="button" onclick="onChangeSort('name','desc')">
                                                <i class="glyphicon glyphicon-chevron-down"></i>
                                            </button>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="pull-left">Path
                                        </div>
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default <?= $_GET['sort']['full_path'] == "asc" ? "active" : "" ?>" type="button" onclick="onChangeSort('full_path','asc')">
                                                <i class="glyphicon glyphicon-chevron-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-default <?= $_GET['sort']['full_path'] == "desc" ? "active" : "" ?>" type="button" onclick="onChangeSort('full_path','desc')">
                                                <i class="glyphicon glyphicon-chevron-down"></i>
                                            </button>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="pull-left">Privacy
                                        </div>
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default <?= $_GET['sort']['privacy'] == "asc" ? "active" : "" ?>" type="button" onclick="onChangeSort('privacy','asc')">
                                                <i class="glyphicon glyphicon-chevron-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-default <?= $_GET['sort']['privacy'] == "desc" ? "active" : "" ?>" type="button" onclick="onChangeSort('privacy','desc')">
                                                <i class="glyphicon glyphicon-chevron-down"></i>
                                            </button>
                                        </div>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td></td>
                                    <td>
                                        <form class="form-inline" onsubmit="onChangeSearch('name', event)">
                                            <div class="form-group">
                                                <input name="name" type="text" class="form-control" placeholder="Search by name" value="<?= preg_replace("/%/i", "",  $_GET['search']['name']) ?>">
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-inline" onsubmit="onChangeSearch('full_path', event)">
                                            <div class="form-group">
                                                <input name="full_path" type="text" class="form-control" placeholder="Search by path" value="<?= preg_replace("/%/i", "",  $_GET['search']['full_path']) ?>">
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-inline" onsubmit="onChangeSearch('privacy', event)">
                                            <div class="form-group">
                                                <input name="privacy" type="text" class="form-control" placeholder="Search by privacy" value="<?= preg_replace("/%/i", "",  $_GET['search']['privacy']) ?>">
                                            </div>
                                        </form>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" id="relogin" style="display:none">
                <div class="col-md-12">
                    <button class="btn btn-default" type="button" onclick="removeAuth()">re-Login</button>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('addModal')"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Path</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onsubmit="onSubmit(event)">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Name" name="name" onkeyup="onAlterPath(event, 'addModal')" onchange="onAlterPath(event, 'addModal')" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control" name="type" onchange="onChangeType(event, 'addModal')">
                                <option value="" hidden>Type</option>
                                <option value="path">Path</option>
                                <option value="form">Form</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control" name="parent_id">
                                <option value="" selected hidden>(Optional)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Path</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-path" placeholder="path" readonly>
                        </div>
                    </div>
                    <div id="if-type-form" style="display:none">

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Privacy</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="privacy" value="none" checked>
                                        None
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="privacy" value="only-logged-in-user">
                                        Only logged in user
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="privacy" value="only-me">
                                        Only user him or her self
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    Default Field
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="panel-group">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab">
                                            <h4 class="panel-title">
                                                Title
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab">
                                            <h4 class="panel-title">
                                                Slug
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab">
                                            <h4 class="panel-title">
                                                Description
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab">
                                            <h4 class="panel-title">
                                                Keywords
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    Custom Field
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="panel-group" id="field-list"></div>
                                <button type="button" class="btn btn-default" onclick="addField()">+</button>
                            </div>
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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('editModal')"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Path</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onsubmit="onSubmitEdit(event)">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Name" name="name" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control" name="parent_id">
                                <option value="" selected hidden>(Optional)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Path</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-path" placeholder="path" readonly>
                        </div>
                    </div>




                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="editSubmit" type="submit" class="btn btn-default" style="display: none;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal('editModal')">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('editSubmit').click()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
    var total = 0;
    var limit = <?= !empty($_GET['limit']) ? $_GET['limit'] : "'all'" ?>;
    var page = <?= !empty($_GET['page']) ? $_GET['page'] : 1 ?>;
    var sort = <?= !empty($_GET['sort']) ? json_encode((object) $_GET['sort']) : "{}" ?>;
    var search = <?= !empty($_GET['search']) ? json_encode((object) $_GET['search']) : "{}" ?>;

    var allPath = [];
    var selected = null;
    var pathType = null;
    var editId = null;

    var numberField = 0;


    var elAlert = document.createElement("div");
    elAlert.classList.add("alert");
    elAlert.classList.add("alert-success");


    var elAlertDgr = document.createElement("div");
    elAlertDgr.classList.add("alert");
    elAlertDgr.classList.add("alert-danger");

    function onAlterHeader(e) {

        e.target.parentElement.parentElement.parentElement.parentElement.parentElement.querySelector(".panel-title").innerHTML = e.target.value === "" ? "Field" : e.target.value
    }

    function alteringPath(name, id) {
        if (name !== "") {

            name = name.replace(/[^a-zA-Z0-9-_\s]/g, '');
            name = name.replace(/\s/g, '-');

            const lastChar = name.slice(-1);
            if (lastChar === '-' || lastChar === '_') {
                name = name.slice(0, -1);
            }

            if (pathType === "form") {
                if (name[name.length - 1] == "y") {
                    name = name.substring(0, name.length - 1);
                    name = name + "ies";
                } else {
                    name = name + "s";
                }
            }

            name = name.toLowerCase()
        }

        document.getElementById(id).querySelector(".input-path").setAttribute("value", name)
    }

    function onAlterPath(e, id) {
        let name = e.target.value
        alteringPath(name, id);
    }

    function collapsePanel(e) {
        var panelStatus = e.target.parentElement.parentElement.querySelector(".panel-collapse");
        if (panelStatus.classList.contains("in")) {
            panelStatus.classList.remove("in")
        } else {
            panelStatus.classList.add("in")
        }
    }

    function addField() {
        var panelEl = document.createElement('div');
        panelEl.classList.add("panel");
        panelEl.classList.add("panel-default");
        var html = `
        <div class="panel-heading" role="tab" onclick="collapsePanel(event)">
            <h4 class="panel-title">
                Field 
            </h4>
        </div>
        <div class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Field Label</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Field Label" name="field[${numberField}][label]"  autocomplete="off" onkeyup="onAlterHeader(event)" onchange="onAlterHeader(event)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Field Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Field Name" name="field[${numberField}][name]"  autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        <select class="form-control" placeholder="Type" name="field[${numberField}][type]" >
                            <option value="" hidden>Type</option>
                            <option value="number">Number</option>
                            <option value="text">Text</option>
                            <option value="textarea">Text Area</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Default Value</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Default Value" name="field[${numberField}][default_value]" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-default" onclick="removeField(event)">-</button>
                    </div>
                </div>
            </div>
        </div>`;

        panelEl.innerHTML = html;

        document.getElementById("field-list").appendChild(panelEl);
        numberField += 1;
    }

    function removeField(e) {
        e.target.parentElement.parentElement.parentElement.parentElement.parentElement.remove();
    }

    function onChangeType(e, id) {
        pathType = e.target.value
        if (e.target.value === "form") {
            document.getElementById(id).querySelector("#if-type-form").style.display = "block";
        } else {
            document.getElementById(id).querySelector("#if-type-form").style.display = "none"
        }

        alteringPath(document.getElementById("addModal").querySelector("input[name=name]").value, id);
    }

    function selectedId(value) {
        selected = value;
        openModal("deleteModal");
    }

    function arrToModalById(arr, id, value = null) {
        var data = null;

        document.getElementById(id).querySelector("select[name='parent_id']").innerHTML = "";

        var opt = document.createElement("option");
        opt.text = "None";
        opt.value = "";
        document.getElementById(id).querySelector("select[name='parent_id']").appendChild(opt)

        allPath = arr;
        arr
            .forEach(function(item) {
                var opt = document.createElement("option");
                opt.text = item.full_path
                opt.value = item.id
                if (value !== null && item.id === Number(value)) {
                    data = item;
                }
                document.getElementById(id).querySelector("select[name='parent_id']").appendChild(opt)
            })

        if (data !== null && id === "editModal") {
            var modalEl = document.getElementById(id)
            modalEl.querySelector("input[name=name]").value = data.name;
            modalEl.querySelector("select[name=parent_id]").value = data.parent_id ? data.parent_id : "";
            modalEl.querySelector(".input-path").value = data.path;


        }
    }


    function selectedEditId(value) {
        editId = value;
        openModal("editModal").then(() => {
            if (allPath.length === 0) {
                fetch("/json/v1/custom", {
                    method: "get",
                }).then(function(res) {
                    return res.json()
                }).then(function(res) {
                    arrToModalById(res.data.data, "editModal", value)
                })
            } else {
                arrToModalById(allPath, "editModal", value)
            }
        })
    }

    function onDelete() {
        fetch(`/json/v1/admin/path/${selected}`, {
            method: "delete",
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function(res) {
            if (res.status >= 200 && res.status < 300) {
                selected = null;
                closeModal("deleteModal");
                window.location.reload();
            }
        })
    }

    function onOpenAddModal() {
        openModal("addModal").then(() => {
            if (allPath.length === 0) {
                fetch("/json/v1/custom", {
                    method: "get",
                }).then(function(res) {
                    return res.json()
                }).then(function(res) {
                    arrToModalById(res.data.data, "addModal")
                })
            } else {
                arrToModalById(allPath, "addModal")
            }
        })
        numberField = 0;
    }

    function onSubmitEdit(e) {
        e.preventDefault();

        var form = new URLSearchParams(new FormData(e.target));

        fetch(`/json/v1/admin/path/${editId}`, {
            method: "put",
            body: form,
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function(res) {
            if (res.status >= 200 && res.status < 300) {
                window.location.reload();
            }
        })
    }

    function onSubmit(e) {
        e.preventDefault();


        var form = new FormData(e.target);

        fetch("/json/v1/admin/path", {
            method: "post",
            body: form,
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function(res) {
            res.json().then(function(data) {
                if (res.status >= 200 && res.status < 300) {
                    window.location.reload();
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
                    document.querySelector("#addModal .modal-body").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                } else {
                    elAlertDgr.innerHTML = "Something went wrong!";
                    document.querySelector("#addModal .modal-body").appendChild(elAlertDgr);
                    setTimeout(function() {
                        elAlertDgr.remove();
                    }, 1000)
                }
            })
        })
    }

    function onGetNewPath(querySearch) {

        var querySearchExp = expandJSON(querySearch);

        var querySearchArr = []
        querySearchExp.forEach(function(item) {
            querySearchArr = [...querySearchArr, `${item.label}=${item.value}`]
        })

        var querySearchStr = ""

        if (querySearchArr.length > 0) {
            querySearchStr = `?${querySearchArr.join("&")}`
        }

        window.location.href = `/admin${querySearchStr}`;
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

    function onChangeSort(name, type) {
        var getArr = <?= json_encode(count($_GET) > 0 ? $_GET : (object) []) ?>;

        var sIndex = Object.keys(sort).findIndex(function(item) {
            return item === name
        })

        if (sIndex >= 0 && sort[name] == type) {
            delete sort[name];
        } else {
            sort = {
                ...sort,
                [name]: type
            }
        }
        getArr = {
            ...getArr,
            sort
        };
        onGetNewPath(getArr)

    }

    function onChangeSearch(name, e) {
        e.preventDefault();
        var getArr = <?= json_encode(count($_GET) > 0 ? $_GET : (object) []) ?>;
        if (e.target[name = name].value == "") {
            delete search[name];
        } else {
            search = {
                ...search,
                [name]: `%${e.target[name = name].value}%`
            }
        }

        Object.keys(search).forEach(function(key) {
            search[key] = encodeURI(search[key]);
        })
        getArr = {
            ...getArr,
            search
        };
        onGetNewPath(getArr)
    }


    function onGetData() {
        Object.keys(search).forEach(function(key) {
            search[key] = encodeURI(search[key]);
        })
        var querySearch = {
            search,
            sort,
            limit,
            page
        }

        if (querySearch.limit == "all") {
            delete querySearch.limit;
            delete querySearch.page;
        }

        var querySearchExp = expandJSON(querySearch);

        var querySearchArr = []
        querySearchExp.forEach(function(item) {
            querySearchArr = [...querySearchArr, `${item.label}=${item.value}`]
        })

        var querySearchStr = ""

        if (querySearchArr.length > 0) {
            querySearchStr = `?${querySearchArr.join("&")}`
        }

        fetch(`/json/v1/custom${querySearchStr}`, {
            method: "get",
            headers: {
                "Authorization": `Bearer ${getAuth().token}`
            }
        }).then(function(res) {
            if (res.status >= 200 && res.status < 300) {
                return res.json().then(function(res) {
                    total = res.data.total;
                    var no = 1;
                    if (limit != "all") {
                        no = (page * limit) - (limit - 1);
                    }
                    var elAllPath = document.getElementById("all-path");
                    var tbody = elAllPath.querySelector("tbody");

                    res.data.data.forEach(function(item) {
                        tbody.innerHTML = tbody.innerHTML + `
                <tr>
                    <td>${no}</td>
                    <td>${item.name}</td>
                    <td>/json/v1/custom${item.full_path}</td>
                    <td>${item.privacy}</td>
                    <td>
                        <button class="btn btn-success" type="button" onclick="selectedEditId('${item.id}')">Edit</button>
                        <button class="btn btn-danger" type="button" onclick="selectedId('${item.id}')">Delete</button>
                        ${item.type==="form"?`<a class="btn btn-default" href="/admin/try${item.full_path}" role="button">Open</a>`:""}
                    </td>
                </tr>`;
                        no++;
                    })
                });
            }
            if (res.status == 401) {
                document.getElementById("relogin").style.display = "block"
            }
        })
    }

    onGetData()
</script>