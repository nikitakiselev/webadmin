<div class="container">
    <h1 class="page-header">Edit user &laquo;<?= $user['User']['username']; ?>&raquo;</h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                User Inforamtion
            </h3>
        </div>
        <div class="panel-body">
            <div class="user-manager-form">
                <?php echo $this->Form->create('User'); ?>
                    <?php echo $this->Form->input('username'); ?>

                    <div class="input">
                        <label for="UserStatus">
                            <?php echo $this->Form->checkbox('status'); ?> Active user
                        </label>
                    </div>

                    <?php echo $this->Form->input('first_name'); ?>
                    <?php echo $this->Form->input('last_name'); ?>
                    <?php echo $this->Form->input('company'); ?>
                    <?php echo $this->Form->input('zipcode'); ?>
                    <?php echo $this->Form->input('email'); ?>
                    <?php echo $this->Form->input('mobile'); ?>
                    <?php echo $this->Form->input('type', [
                        'options' => [
                            'admin' => 'Administrator',
                            'subscriber' => 'Subscriber',
                            'investor' => 'Investor',
                        ],
                    ]); ?>
                <?php echo $this->Form->end('Update user'); ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Change user's password</h3>
        </div>

        <div class="panel-body">
            <div class="user-manager-form">
                <?php
                    $errors = [];
                    if (isset($_SESSION['errors'])) {
                        $errors = $_SESSION['errors'];
                        unset($_SESSION['errors']);
                    }
                ?>

                <form action="/usersManager/changePassword/<?php print $user['User']['id']; ?>" method="post">
                    <div class="input<?php print isset($errors['new_password']) ? ' error': ''; ?>">
                        <label for="new_password">New password</label>
                        <input type="password" id="new_password" name="new_password" value="" autocomplete="off">

                        <?php if (isset($errors['new_password'])): ?>
                            <p class="error-message"><?php print $errors['new_password']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input">
                        <?php echo $this->Form->input('new_password_confirmation', array(
                            'type' => 'password',
                            'label' => 'Confirm new password',
                            'autocomplete' => 'off'
                        )); ?>
                    </div>

                    <div class="input">
                        <button type="submit" class="btn btn-primary">Change password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
