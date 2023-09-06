<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <input id="path" class="form-control" value="/json/v1/admin/users" readonly />
        </div>
    </div>
    <div class="row" id="data-array">
        <div class="col-sm-12">

            <div class="row">
                <div class="col-sm-12">
                    <div class="pull-left">
                        <a href="/admin" class="btn btn-default">to Path Index</a>
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
                            <th>
                                <div class="pull-left">Username</div>
                                <div class="pull-right">
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['username'] == "asc" ? "active" : "" ?>" type="button" onclick="onChangeSort('username','asc')">
                                        <i class="glyphicon glyphicon-chevron-up"></i>
                                    </button>
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['username'] == "desc" ? "active" : "" ?>" type="button" onclick="onChangeSort('username','desc')">
                                        <i class="glyphicon glyphicon-chevron-down"></i>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="pull-left">E-mail</div>
                                <div class="pull-right">
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['email'] == "asc" ? "active" : "" ?>" type="button" onclick="onChangeSort('email','asc')">
                                        <i class="glyphicon glyphicon-chevron-up"></i>
                                    </button>
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['email'] == "desc" ? "active" : "" ?>" type="button" onclick="onChangeSort('email','desc')">
                                        <i class="glyphicon glyphicon-chevron-down"></i>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="pull-left">Role</div>
                                <div class="pull-right">
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['role'] == "asc" ? "active" : "" ?>" type="button" onclick="onChangeSort('role','asc')">
                                        <i class="glyphicon glyphicon-chevron-up"></i>
                                    </button>
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['role'] == "desc" ? "active" : "" ?>" type="button" onclick="onChangeSort('role','desc')">
                                        <i class="glyphicon glyphicon-chevron-down"></i>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="pull-left">Created at</div>
                                <div class="pull-right">
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['created_at'] == "asc" ? "active" : "" ?>" type="button" onclick="onChangeSort('created_at','asc')">
                                        <i class="glyphicon glyphicon-chevron-up"></i>
                                    </button>
                                    <button class="btn btn-sm btn-default <?= $_GET['sort']['created_at'] == "desc" ? "active" : "" ?>" type="button" onclick="onChangeSort('created_at','desc')">
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
                                <form class="form-inline" onsubmit="onChangeSearch('username', event)">
                                    <div class="form-group">
                                        <input name="username" type="text" class="form-control" placeholder="Search by username" value="<?= preg_replace("/%/i", "", ($_GET['search']['username'])) ?>">
                                    </div>
                                </form>
                            </td>
                            <td>
                                <form class="form-inline" onsubmit="onChangeSearch('email', event)">
                                    <div class="form-group">
                                        <input name="email" type="text" class="form-control" placeholder="Search by email" value="<?= preg_replace("/%/i", "", ($_GET['search']['email'])) ?>">
                                    </div>
                                </form>
                            </td>
                            <td>
                                <form class="form-inline" onsubmit="onChangeSearch('role', event)">
                                    <div class="form-group">
                                        <input name="role" type="text" class="form-control" placeholder="Search by role" value="<?= preg_replace("/%/i", "", ($_GET['search']['role'])) ?>">
                                    </div>
                                </form>
                            </td>
                            <td>
                                <form class="form-inline" onsubmit="onChangeSearch('created_at', event)">
                                    <div class="form-group">
                                        <input name="created_at" type="text" class="form-control" placeholder="Search by Created at" value="<?= preg_replace("/%/i", "", ($_GET['search']['created_at'])) ?>">
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row" id="relogin" style="display:none">
        <div class="col-md-12">
            <button class="btn btn-default" type="button" onclick="removeAuth()">Login</button>
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
    var deleteId = "";
    var total = 0;
    var limit = <?= !empty($_GET['limit']) ? $_GET['limit'] : "'all'" ?>;
    var page = <?= !empty($_GET['page']) ? $_GET['page'] : 1 ?>;
    var sort = <?= !empty($_GET['sort']) ? json_encode((object) $_GET['sort']) : "{}" ?>;
    var search = <?= !empty($_GET['search']) ? json_encode((object) $_GET['search']) : "{}" ?>;


    var elAlert = document.createElement("div");
    elAlert.classList.add("alert");
    elAlert.classList.add("alert-success");


    var elAlertDgr = document.createElement("div");
    elAlertDgr.classList.add("alert");
    elAlertDgr.classList.add("alert-danger");

    function onChangeSearch(name, e) {
        e.preventDefault();
        var getArr = <?= json_encode(count($_GET) > 0 ? $_GET : (object) []) ?>;
        if (e.target[name = name].value == "") {
            delete search[name];
        } else {
            search = {
                ...search,
                [name]: encodeURI(`%${e.target[name = name].value}%`)
            }
        }

        getArr = {
            ...getArr,
            search
        };
        onGetNewPath(getArr)
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

    function selectedDeleteId(path) {
        deleteId = path;
        openModal("deleteModal");
    }

    function onGetNewPath(querySearch) {

        var path = document.getElementById("path").value;
        var actualpath = `${path}`.substring(14);

        var querySearchExp = expandJSON(querySearch);

        var querySearchArr = []
        querySearchExp.forEach(function(item) {
            querySearchArr = [...querySearchArr, `${item.label}=${item.value}`]
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
        var actualpath = `${path}`.substring(14);

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

        fetch(`${path}${querySearchStr}`, {
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
            var no = 1;
            if (limit != "all") {
                no = (page * limit) - (limit - 1);
            }
            var elementArray = document.getElementById("data-array");

            result.data.data.forEach((item) => {

                elementArray.querySelector("tbody").innerHTML = elementArray.querySelector("tbody").innerHTML + `
                    <tr>
                        <td>${no}.</td>
                        <td>${item.username}</td>
                        <td>${item.email}</td>
                        <td>${item.role}</td>
                        <td>${item.created_at}</td>
                        <td>
                            ${getAuth().username !== item.username ? `<button class="btn btn-danger" type="button" onclick="selectedDeleteId('${item.id}')">Delete</button>`:""}
                            <a class="btn btn-default" href="/admin/users/${item.id}" role="button">Open</a>
                        </td>
                    </tr>
                    `;
                no++;
            })

        });
    }

    function onDelete() {
        fetch(`/json/v1/admin/users/${deleteId}`, {
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
        }).catch(function() {
            closeModal("deleteModal");
        })
    }
    onGetData();
</script>