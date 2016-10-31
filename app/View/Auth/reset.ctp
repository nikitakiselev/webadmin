<?php
    $errors = [];
    if (isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);
    }
?>

<div id="loginMaster">
    <form action="/auth/reset/<?php print $code; ?>" method="post" class="form-horizontal" autocomplete="off">
        <table width="100%" style="border-collapse: separate; border-spacing: 15px; text-align: left;">
            <?php if (count($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <tr width="100%">
                        <p class="error-message"><?php print $error; ?></p>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr>
                <td width="100%">
                    <div class="form-group<?php print isset($errors['password']) ? ' has-error': ''; ?>">
                        <label for="password" class="control-label">Enter new password:</label>
                        <input id="password"
                               autocomplete="off"
                               type="password"
                               name="password"
                               class="form-control"
                               required
                               style="width:100%; font-size: 100%; padding:8px; border-radius: 5px; border: 1px solid grey;" >
                    </div>
                </td>
            </tr>

            <tr>
                <td width="100%">
                    <label for="password_confirmation" class="control-label">Enter new password again:</label>
                    <input id="password_confirmation"
                           type="password"
                           autocomplete="off"
                           name="password_confirmation"
                           class="form-control"
                           required
                           style="width:100%; font-size: 100%; padding:8px; border-radius: 5px; border: 1px solid grey;" >
                </td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: right">
                    <button type="submit" class="btn btn-default" style="background: #cddddd">
                        Set my new password
                    </button>
                </td>
            </tr>
        </table>
    </form>
</div>
