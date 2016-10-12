<div class="container">
    <h1 class="page-header">Users manager</h1>

    <div class="table-toolbar">
        <a href="/usersManager/create" class="btn btn-primary">
            <i class="fa fa-plus"></i> Create new user
        </a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Company</th>
                <th>Zip code</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['User']['id']; ?></td>
                <td><?= $user['User']['username']; ?></td>
                <td><?= $user['User']['first_name']; ?></td>
                <td><?= $user['User']['last_name']; ?></td>
                <td><?= $user['User']['company']; ?></td>
                <td><?= $user['User']['zipcode']; ?></td>
                <td><?= $user['User']['email']; ?></td>
                <td><?= $user['User']['mobile']; ?></td>
                <td><?= $user['User']['type']; ?></td>
                <td>
                    <?php if ($user['User']['status'] == true): ?>
                        <span class="label label-success">Active</span>
                    <?php else: ?>
                        <span class="label label-danger">Non active</span>
                    <?php endif; ?>
                </td>
                <td><?= $user['User']['created']; ?></td>
                <td>
                    <a href="/usersManager/edit/<?= $user['User']['id']; ?>" class="btn btn-xs btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>