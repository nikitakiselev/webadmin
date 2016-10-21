<div class="container">
    <h1 class="page-header">Create new user</h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                User Inforamtion and password
            </h3>
        </div>
        <div class="panel-body">
            <div class="user-manager-form">
                <?php echo $this->Form->create('User'); ?>
                    <?php echo $this->Form->input('username', ['class' => 'form-control']); ?>

                    <div class="input">
                        <label for="UserStatus">
                            <?php echo $this->Form->checkbox('status'); ?> Active user
                        </label>
                    </div>

                    <?php echo $this->Form->input('first_name', ['class' => 'form-control']); ?>
                    <?php echo $this->Form->input('last_name', ['class' => 'form-control']); ?>
                    <?php echo $this->Form->input('company', ['class' => 'form-control']); ?>
                    <?php echo $this->Form->input('zipcode', ['class' => 'form-control']); ?>
                    <?php echo $this->Form->input('email', ['class' => 'form-control']); ?>
                    <?php echo $this->Form->input('mobile', ['class' => 'form-control']); ?>
                    <?php echo $this->Form->input('type', [
                        'options' => [
                            'admin' => 'Administrator',
                            //'subscriber' => 'Subscriber',
                            'investor' => 'Investor',
                        ],
                        'class' => 'form-control',
                    ]); ?>
                    <?php print $this->Form->input('password', ['class' => 'form-control']); ?>
                    <?php print $this->Form->input('password_confirmation', ['class' => 'form-control', 'type' => 'password']); ?>
                <?php echo $this->Form->end([
                    'label' => 'Create new user',
                    'class' => 'btn btn-primary'
                ]); ?>
            </div>
        </div>
    </div>
</div>
