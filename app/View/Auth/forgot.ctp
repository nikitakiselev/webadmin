<style>
    #login_user{margin: 0px auto; padding: 10px 0px 0px; width: 95%;}
    .btn-default{background: #dcdcdc -moz-linear-gradient(center top , #fefefe, #dcdcdc) repeat scroll 0 0;
        border: 1px solid #bbb;
        border-radius: 4px; box-shadow: 0 1px 0 rgba(255, 255, 255, 0.3) inset, 0 1px 1px rgba(0, 0, 0, 0.2);
        color: #333; padding: 4px 8px; font-size: 100% !important;
    }
</style>
<div id="loginMaster">
    <form action="/auth/forgot" method="post" class="form-horizontal">
        <table width="100%" style="border-collapse: separate; border-spacing: 15px; text-align: left;">
            <tr>
                <td width="20%">E-mail: </td>
                <td width="75%">
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address..." required style="width:91%; font-size: 100%; padding:8px; border-radius: 5px; border: 1px solid grey;" >
                </td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: right">
                    <button type="submit" class="btn btn-default" style="background: #cddddd">
                        Send password reset link
                    </button>
                </td>
            </tr>
        </table>
    </form>
</div>