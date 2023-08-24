<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-default" type="button" onclick="onOpenAddModal()">Add New</button>
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
                        <?php foreach ($data as $dt) : ?>
                            <tr>
                                <td></td>
                                <td><?= $dt->name ?></td>
                                <td><?= $dt->full_path ?></td>
                                <td>
                                    <button class="btn btn-danger" type="button" onclick="selectedId('<?= $dt->id ?>')">Delete</button>
                                    <a class="btn btn-default" href="/docs<?= $dt->full_path ?>" role="button">Open</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onsubmit="onSubmit(event)">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Name" name="name" required>
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
                            <input type="text" class="form-control" id="" placeholder="path" name="path" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control" name="type" onchange="onChangeType(event)" required>
                                <option hidden>Type</option>
                                <option value="path">Path</option>
                                <option value="form">Form</option>
                            </select>
                        </div>
                    </div>
                    <div id="if-type-form" class="panel panel-default" style="display:none">
                        <div class="panel-body">
                            <div id="field-list">
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
                                                    <input type="text" class="form-control" id="" placeholder="Field Label" name="field[][label]" value="Title" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Field Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="" placeholder="Field Name" name="field[][name]" value="title" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 control-label">Type</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" placeholder="Type" name="field[][type]" readonly>
                                                        <option hidden>Type</option>
                                                        <option value="text" selected>Text</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Default Value</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" placeholder="Default Value" name="field[][default_value]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-default" onclick="addField()">+</button>
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
                        <input type="text" class="form-control" placeholder="Field Label" name="field[][label]" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Field Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Field Name" name="field[][name]" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        <select class="form-control" placeholder="Type" name="field[][type]" required>
                            <option hidden>Type</option>
                            <option value="text">Text</option>
                            <option value="textarea">Text Area</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Default Value</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Default Value" name="field[][default_value]">
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

    function onChangeType(e) {
        if (e.target.value === "form") {
            document.getElementById("if-type-form").style.display = "block"
        } else {
            document.getElementById("if-type-form").style.display = "none"
        }
    }

    function selectedId(value) {
        selected = value;
        openModal("deleteModal");
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
        if (allPath.length === 0) {
            openModal("addModal").then(() => {
                fetch("/json", {
                    method: "get",
                }).then(function(res) {
                    return res.json()
                }).then(function(res) {
                    allPath = res.data;
                    res.data.forEach(function(item) {
                        var opt = document.createElement("option");
                        opt.text = item.full_path
                        opt.value = item.full_path

                        document.querySelector("select[name='parent']").appendChild(opt)
                    })
                })
            })
        }
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

        var form = new URLSearchParams();
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
            const fieldLabel = e.target[name = 'field[][label]'];
            if (Array.isArray(fieldLabel)) {
                form.append("field[0][label]", fieldLabel.value)
            } else {
                fieldLabel.forEach((item, index) => {
                    form.append("field[" + index + "][label]", item.value)
                })
            }
            const fieldName = e.target[name = 'field[][name]'];
            if (Array.isArray(fieldName)) {
                form.append("field[0][name]", fieldName.value)
            } else {
                fieldName.forEach((item, index) => {
                    form.append("field[" + index + "][name]", item.value)
                })
            }
            const fieldType = e.target[name = 'field[][type]'];
            if (Array.isArray(fieldType)) {
                form.append("field[0][type]", fieldType.value)
            } else {
                fieldType.forEach((item, index) => {
                    form.append("field[" + index + "][type]", item.value)
                })
            }
            const defaultValue = e.target[name = 'field[][default_value]'];
            if (Array.isArray(fieldType)) {
                form.append("field[0][default_value]", fieldType.value)
            } else {
                fieldType.forEach((item, index) => {
                    form.append("field[" + index + "][default_value]", item.value)
                })
            }
        }

        fetch("/admin", {
            method: "post",
            body: form
        }).then(function(res) {
            // window.location.reload();
        })
    }
</script>