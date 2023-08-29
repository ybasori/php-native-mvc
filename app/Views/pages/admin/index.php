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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Login</td>
                                    <td>/auth/login</td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Register</td>
                                    <td>/auth/register</td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>Author</td>
                                    <td>/author/[username]</td>
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Path</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $key => $dt) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?>.</td>
                                        <td><?= $dt->name ?></td>
                                        <td><?= $dt->full_path ?></td>
                                        <td>
                                            <button class="btn btn-success" type="button" onclick="selectedEditId('<?= $dt->id ?>')">Edit</button>
                                            <button class="btn btn-danger" type="button" onclick="selectedId('<?= $dt->id ?>')">Delete</button>
                                            <?php if ($dt->type == "form") : ?>
                                                <a class="btn btn-default" href="/admin<?= $dt->full_path ?>" role="button">Open</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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
                            <input type="text" class="form-control" placeholder="Name" name="name" onkeyup="onAlterPath(event, 'addModal')" onchange="onAlterPath(event, 'addModal')" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control" name="type" onchange="onChangeType(event, 'addModal')" required>
                                <option hidden>Type</option>
                                <option value="path">Path</option>
                                <option value="form">Form</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control" name="parent">
                                <option value="0" selected hidden>(Optional)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Path</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="" placeholder="path" name="path" readonly>
                        </div>
                    </div>
                    <div id="if-type-form" style="display:none">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    Default Field
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="panel-group">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne" onclick="collapsePanel(event)">
                                            <h4 class="panel-title">
                                                Title (default field)
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Label</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Label" value="Title" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Name" value="title" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Type</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" placeholder="Type" readonly>
                                                            <option hidden>Type</option>
                                                            <option value="text" selected>Text</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne" onclick="collapsePanel(event)">
                                            <h4 class="panel-title">
                                                Slug (default field)
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Label</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Label" value="Slug" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Name" value="slug" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Type</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" placeholder="Type" readonly>
                                                            <option hidden>Type</option>
                                                            <option value="text" selected>Text</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne" onclick="collapsePanel(event)">
                                            <h4 class="panel-title">
                                                Description (default field)
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Label</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Label" value="Description" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Name" value="description" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Type</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" placeholder="Type" readonly>
                                                            <option hidden>Type</option>
                                                            <option value="text" selected>Text</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne" onclick="collapsePanel(event)">
                                            <h4 class="panel-title">
                                                Keywords (default field)
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Label</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Label" value="Keywords" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Field Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="" placeholder="Field Name" value="keywords" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label">Type</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" placeholder="Type" readonly>
                                                            <option hidden>Type</option>
                                                            <option value="text" selected>Text</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
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
                            <input type="text" class="form-control" placeholder="Name" name="name" required autocomplete="off">
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
    var allPath = [];
    var selected = null;
    var pathType = null;
    var editId = null;

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

        document.getElementById(id).querySelector("input[name=path]").setAttribute("value", name)
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
                        <input type="text" class="form-control" placeholder="Field Label" name="field[][label]" required autocomplete="off" onkeyup="onAlterHeader(event)" onchange="onAlterHeader(event)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Field Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Field Name" name="field[][name]" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        <select class="form-control" placeholder="Type" name="field[][type]" required>
                            <option hidden>Type</option>
                            <option value="text">Number</option>
                            <option value="text">Text</option>
                            <option value="textarea">Text Area</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Default Value</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Default Value" name="field[][default_value]" autocomplete="off">
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

        alteringPath(document.querySelector("input[name=name]").value, id);
    }

    function selectedId(value) {
        selected = value;
        openModal("deleteModal");
    }

    function arrToModalById(arr, id, value = null) {
        var data = null;
        allPath = arr;
        arr.forEach(function(item) {
            var opt = document.createElement("option");
            opt.text = item.full_path
            opt.value = item.full_path
            if (value !== null && item.id === Number(value)) {
                data = item;
            }

            if (id != "editModal") {
                document.getElementById(id).querySelector("select[name='parent']").appendChild(opt)
            }
        })

        if (data !== null && id === "editModal") {
            var modalEl = document.getElementById(id)
            modalEl.querySelector("input[name=name]").value = data.name;


        }
    }


    function selectedEditId(value) {
        editId = value;
        openModal("editModal").then(() => {
            if (allPath.length === 0) {
                fetch("/json", {
                    method: "get",
                }).then(function(res) {
                    return res.json()
                }).then(function(res) {
                    arrToModalById(res.data, "editModal", value)
                })
            } else {
                arrToModalById(allPath, "editModal", value)
            }
        })
    }

    function onDelete() {
        fetch(`/admin?id=${selected}`, {
            method: "delete"
        }).then(function() {
            selected = null;
            closeModal("deleteModal");
            window.location.reload();
        }).catch(function() {
            closeModal("deleteModal");
        })
    }

    function onOpenAddModal() {
        openModal("addModal").then(() => {
            if (allPath.length === 0) {
                fetch("/json", {
                    method: "get",
                }).then(function(res) {
                    return res.json()
                }).then(function(res) {
                    arrToModalById(res.data, "addModal")
                })
            } else {
                arrToModalById(allPath, "addModal")
            }
        })
    }

    function onSubmitEdit(e) {
        e.preventDefault();

        var form = new URLSearchParams(new FormData(e.target));

        fetch(`/admin?id=${editId}`, {
            method: "put",
            body: form
        }).then(function(res) {
            window.location.reload();
        })
    }

    function onSubmit(e) {
        e.preventDefault();

        var pathName = e.target[name = 'name'].value;
        var plural = e.target[name = 'name'].value;
        if (plural[plural.length - 1] == "y") {
            plural = plural.substring(0, plural.length - 1);
            plural = plural + "ies";
        } else {
            plural = plural + "s";
        }
        var pathPrefix = "";
        if (e.target[name = 'parent'].value !== "0") {
            pathPrefix = e.target[name = 'parent'].value;
        }

        var form = new FormData();
        form.append('name', pathName);
        form.append('name_singular', pathName);
        form.append('name_plural', plural);
        form.append('full_path', `${pathPrefix}/${e.target[name = 'path'].value}`);
        form.append('type', `${e.target[name = 'type'].value}`);
        form.append('path', `${e.target[name = 'path'].value}`);

        if (e.target[name = 'parent'].value !== "0") {
            form.append("parent_id", allPath[allPath.findIndex((item) => item.full_path == pathPrefix)].id)
        }

        if (e.target[name = 'type'].value === 'form') {
            form.append('resources_json', JSON.stringify(["index", "show", "store", "update", "delete"]));
            if (e.target[name = 'field[][label]']) {
                const fieldLabel = e.target[name = 'field[][label]'];
                if (fieldLabel.length === undefined) {
                    form.append("field[0][label]", fieldLabel.value)
                } else {
                    fieldLabel.forEach((item, index) => {
                        form.append("field[" + index + "][label]", item.value)
                    })
                }
            }
            if (e.target[name = 'field[][name]']) {
                const fieldName = e.target[name = 'field[][name]'];
                if (fieldName.length === undefined) {
                    form.append("field[0][name]", fieldName.value)
                } else {
                    fieldName.forEach((item, index) => {
                        form.append("field[" + index + "][name]", item.value)
                    })
                }
            }
            if (e.target[name = 'field[][type]']) {
                const fieldType = e.target[name = 'field[][type]'];
                if (fieldType.length === undefined) {
                    form.append("field[0][type]", fieldType.value)
                } else {
                    fieldType.forEach((item, index) => {
                        form.append("field[" + index + "][type]", item.value)
                    })
                }
            }
            if (e.target[name = 'field[][default_value]']) {
                const defaultValue = e.target[name = 'field[][default_value]'];
                if (defaultValue.length === undefined) {
                    form.append("field[0][default_value]", defaultValue.value)
                } else {
                    defaultValue.forEach((item, index) => {
                        form.append("field[" + index + "][default_value]", item.value)
                    })
                }
            }
        } else {
            form.append('resources_json', JSON.stringify(["index"]));
        }

        fetch("/admin", {
            method: "post",
            body: form
        }).then(function(res) {
            window.location.reload();
        })
    }
</script>