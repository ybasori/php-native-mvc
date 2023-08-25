<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <input id="path" class="form-control" value="/json<?= $path->realpath ?>" readonly />
        </div>
    </div>
    <div class="row" id="data-array" style="display:none">
        <div class="col-sm-12">
            <button class="btn btn-default" type="button" onclick="onOpenAddModal()">Add New</button>
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
</div>

<script>
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
                        <td>${JSON.parse(item.keywords_json).join(", ")}</td>
                        <td>${item.description}</td>
                        <td><a class="btn btn-default" href="/admin${actualpath}/${item.slug}" role="button">Open</a></td>
                    </tr>
                    `
                })
            }
        });
    }

    onGetData();
</script>