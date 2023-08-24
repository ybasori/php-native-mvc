<div class="container">
    <div class="row">
        <div class="col-sm-12">
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
                        <?php foreach ($data_path as $path) : ?>
                            <tr>
                                <td></td>
                                <td><?= $path->name ?></td>
                                <td><?= $path->full_path ?></td>
                                <td><a class="btn btn-default" href="/docs<?= $path->full_path ?>" role="button">Open</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>