<div class="container">
    <h1>Edit user &laquo;<?= $user['User']['username']; ?>&raquo;</h1>

    <div class="user-manager-form">
        <?php echo $this->Form->create('User'); ?>
        <?php echo $this->Form->input('username'); ?>
        <?php echo $this->Form->input('first_name'); ?>
        <?php echo $this->Form->input('last_name'); ?>
        <?php echo $this->Form->input('company'); ?>
        <?php echo $this->Form->input('zipcode'); ?>
        <?php echo $this->Form->input('email'); ?>
        <?php echo $this->Form->input('mobile'); ?>
        <?php echo $this->Form->input('type'); ?>
        <?php echo $this->Form->end('Update user'); ?>
    </div>
</div>
