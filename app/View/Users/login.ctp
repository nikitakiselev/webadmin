<style>
    #login_user{margin: 0px auto; padding: 10px 0px 0px; width: 95%;}
    .btn-default{background: #dcdcdc -moz-linear-gradient(center top , #fefefe, #dcdcdc) repeat scroll 0 0;
                 border: 1px solid #bbb;
                 border-radius: 4px; box-shadow: 0 1px 0 rgba(255, 255, 255, 0.3) inset, 0 1px 1px rgba(0, 0, 0, 0.2);
                 color: #333; padding: 4px 8px; font-size: 100% !important;
    }
</style>
<div id="loginMaster">
    <?php echo $this->Form->create("login", array('class'=>'form-horizontal','role'=>'form','id'=>'login_user','name'=>'login_user' ));?>
    <table width="100%" style="border-collapse: separate; border-spacing: 15px; text-align: left;">
        <tr>
            <td width="20%">Username: </td>
            <td width="75%"><?php echo $this->Form->input('User.username', array('class'=>'form-control', 'placeholder'=>__('username'), 'id'=>'username', 'div' => false, 'label'=>false, 'style'=>'width:91%; font-size: 100%; padding:8px; border-radius: 5px; border: 1px solid grey;') )?></td>
        </tr>
        <tr>
            <td>Password: </td>
            <td><?php echo $this->Form->input('User.password', array('class'=>'form-control', 'placeholder'=>__('password'), 'id'=>'password', 'div' => false, 'label'=>false, 'style'=>'width:91%; font-size: 100%; padding:8px; border-radius: 5px; border: 1px solid grey;') )?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right">
                <?php echo $this->Form->submit('Login', array('class'=>'btn btn-default', 'div'=>false, 'style'=>'background: #cddddd none repeat scroll 0% 0%;') )?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right"><?php echo $this->Html->link("Forgot Password?", array('controller'=>'auth', "action"=>'forgot')  )?></td>
        </tr>
    </table>
    <?php echo $this->Form->end();?>
</div>