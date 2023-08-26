<div class="container">
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
                                <input type="text" class="form-control" placeholder="Title" name="title" required autocomplete="off" onkeyup="onAlterSlug(event)" onchange="onAlterSlug(event)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Slug</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Slug" name="slug" required autocomplete="off" readonly>
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
            <div class="modal-body">
                <form class="form-horizontal" onsubmit="onSubmit(event)">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Default Fields</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Title" name="title" required autocomplete="off" onkeyup="onAlterSlug(event,'addModal')" onchange="onAlterSlug(event,'addModal')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Slug" name="slug" required autocomplete="off" readonly>
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


    var elAlert = document.createElement("div");
    elAlert.classList.add("alert");
    elAlert.classList.add("alert-success");
    elAlert.innerHTML = "Success"

    function selectedDeleteId(path) {
        deleteId = path;
        openModal("deleteModal");
    }

    function onGetData() {

        var path = document.getElementById("path").value;
        var actualpath = `${path}`.substring(5)
        fetch(path, {
            method: "get",
        }).then(function(res) {
            return res.json()
        }).then(function(result) {
            if (Array.isArray(result.data)) {
                var elementArray = document.getElementById("data-array");
                elementArray.style.display = "block";
                result.data.forEach((item) => {
                    elementArray.querySelector("tbody").innerHTML = elementArray.querySelector("tbody").innerHTML + `
                    <tr>
                        <td></td>
                        <td>${item.title}</td>
                        <td>${item.keywords}</td>
                        <td>${item.description}</td>
                        <td><button class="btn btn-danger" type="button" onclick="selectedDeleteId('${actualpath}/${item.slug}')">Delete</button><a class="btn btn-default" href="/admin${actualpath}/${item.slug}" role="button">Open</a></td>
                    </tr>
                    `
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

                Object.keys(result.data.fields).map(function(key) {
                    var [field] = fields.filter(function(item) {
                        return item.name === key
                    });
                    if (field) {
                        var selector = `input[name='field[${key}]']`;
                        if (field.type == "textarea") {
                            selector = `textarea[name='field[${key}]']`;
                        }
                        document.querySelector(selector).value = result.data.fields[key];
                    }
                })

            }
        });
    }

    function onOpenAddModal() {
        openModal("addModal")
    }

    function onDelete() {
        fetch(`/json${deleteId}`, {
            method: "delete"
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

        var path = document.getElementById("path").value;
        var form = new URLSearchParams(new FormData(e.target));

        fetch(path, {
            method: "post",
            body: form
        }).then(function(res) {
            window.location.reload();
        })
    }

    function onSubmitEdit(e) {
        e.preventDefault();
        var path = document.getElementById("path").value;
        var form = new URLSearchParams(new FormData(e.target));

        fetch(path, {
            method: "put",
            body: form
        }).then(function(res) {
            elAlert.remove();

            document.getElementById("edit-form").appendChild(elAlert);

            setTimeout(function() {
                elAlert.remove();
            }, 1000)
        })

    }

    onGetData();
</script>