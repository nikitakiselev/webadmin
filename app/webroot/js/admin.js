var left_side_width = 220; //Sidebar width in pixels

/*function gettime(s){
    set = s.split(" "); 
    
    for (var i = 0; i < set.length; i++) {
        t = set[0];
        x = set[1];
        u = t.split(":");
        for (var i = 0; i < u.length; i++) {
            v = u[0];
            w = u[1];
        }
    }
    if(v == '12' && x == "AM") { v = 00;}
    if(x == "PM")
    {
        v = parseInt(v)+parseInt(12);
    }
    var text = parseInt(v)+parseInt(03);
    u =  text+":"+w;
    var n1 =u.split(':');
    for (var i = 0; i < n1.length; i++) {
        tt = n1[0];
        tp = n1[1];
    }
    var time = hours_am_pm(tt,tp,x);
    if(time != "NaN:undefined PM") $('#raffle_ends').val(time+ ' EST');
}

/*function hours_am_pm(tt,tp,x) 
{
    var hours = tt;
    var min = tp; 
    if (hours <= 12 || hours >=24)
    {
        if(hours == 24) return  '12:' + min + ' AM';
        if(hours > 24) 
        {
            hours = parseInt(hours)-parseInt(12)-parseInt(12);
            if (hours ==3) return hours + ':' + min + ' PM';
            else return hours + ':' + min + ' AM';
        }
        else return hours + ':' + min + ' AM';
    } else {
        hours=hours - 12;
        hours=(hours.length < 10) ? '0'+hours:hours;
        return hours+ ':' + min + ' PM';
    }
}

/*function custom_validation()
{
    var message = "";
    var price_name = ['','st','nd','rd','th','th'];
    var chk = 0;
    $("input.gadha").each(function() {
        var atr = parseInt($(this).attr("number_of"));
        var price = $(this).val();
        if(price.trim().length!=0)
        {
            var id = $(this).attr("id");
            if((price.charAt(0)==" " || price.charAt(price.length-1)==" ")) {
                $(this).val(price.trim());               
            }
            $("#"+id).removeClass("error");
        }
        else
        {
            chk++;
            message += "<li> Enter "+atr+""+price_name[atr]+" Prize.</li>";
            var id = $(this).attr("id");
            $("#"+id).addClass("error");
        }
    });

    return message + '|' + chk;
 }*/

$(function() {
    "use strict";

    //Enable sidebar toggle
    $("[data-toggle='offcanvas']").click(function(e) {
        e.preventDefault();

        //If window is small enough, enable sidebar push menu
        if ($(window).width() <= 992) {
            $('.row-offcanvas').toggleClass('active');
            $('.left-side').removeClass("collapse-left");
            $(".right-side").removeClass("strech");
            $('.row-offcanvas').toggleClass("relative");
        } else {
            //Else, enable content streching
            $('.left-side').toggleClass("collapse-left");
            $(".right-side").toggleClass("strech");
        }
    });

    //Add hover support for touch devices
    $('.btn').bind('touchstart', function() {
        $(this).addClass('hover');
    }).bind('touchend', function() {
        $(this).removeClass('hover');
    });

    /*     
     * Add collapse and remove events to boxes
     */
    $("[data-widget='collapse']").click(function() {
        //Find the box parent        
        var box = $(this).parents(".box").first();
        //Find the body and the footer
        var bf = box.find(".box-body, .box-footer");
        if (!box.hasClass("collapsed-box")) {
            box.addClass("collapsed-box");
            //Convert minus into plus
            $(this).children(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
            bf.slideUp();
        } else {
            box.removeClass("collapsed-box");
            //Convert plus into minus
            $(this).children(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
            bf.slideDown();
        }
    });

    /*
     * INITIALIZE BUTTON TOGGLE
     * ------------------------
     */
    $('.btn-group[data-toggle="btn-toggle"]').each(function() {
        var group = $(this);
        $(this).find(".btn").click(function(e) {
            group.find(".btn.active").removeClass("active");
            $(this).addClass("active");
            e.preventDefault();
        });

    });

    $("[data-widget='remove']").click(function() {
        //Find the box parent        
        var box = $(this).parents(".box").first();
        box.slideUp();
    });


    /* 
     * Make sure that the sidebar is streched full height
     * ---------------------------------------------
     * We are gonna assign a min-height value every time the
     * wrapper gets resized and upon page load. We will use
     * Ben Alman's method for detecting the resize event.
     * 
     **/
    function _fix() {
        //Get window height and the wrapper height
        var height = $(window).height() - $("body > .header").height() - ($("body > .footer").outerHeight() || 0);
        $(".wrapper").css("min-height", height + "px");
        var content = $(".wrapper").height();
        //If the wrapper height is greater than the window
        if (content > height)
            //then set sidebar height to the wrapper
            $(".left-side, html, body").css("min-height", content + "px");
        else {
            //Otherwise, set the sidebar to the height of the window
            $(".left-side, html, body").css("min-height", height + "px");
        }
    }
    //Fire upon load
    _fix();
    //Fire when wrapper is resized
    $(".wrapper").resize(function() {
        _fix();
        fix_sidebar();
    });

    //Fix the fixed layout sidebar scroll bug
    fix_sidebar();

});
function fix_sidebar() {
    //Make sure the body tag has the .fixed class
    if (!$("body").hasClass("fixed")) {
        return;
    }

    //Add slimscroll
    $(".sidebar").slimscroll({
        height: ($(window).height() - $(".header").height()) + "px",
        color: "rgba(0,0,0,0.2)"
    });
}



$(document).ready(function(){
    
    /*Sign in Form */
    /*var login_validator = $("#adminLogin").bind("invalid-form.validate", function() {
        
        var message = "<ul>Please correct the following fields:";
        for (var x=0;x<login_validator.errorList.length;x++)
        {
            message += "<li>" + login_validator.errorList[x].message + "</li>";
        }
        $("#error-validation").html(message+"</ul>");   
    }).validate({
        debug: true,
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        errorContainer: $("#error-validation"),
        rules: {
            "data[Admin][email]": {
                required: true,
                email: true
            },
            "data[Admin][password]": {
                required: true,
                minlength: 6
            }
        },
        messages: {
            'data[Admin][email]': { required: "Enter email address.", email : "Enter valid email address." },
            'data[Admin][password]': { required: "Enter password." , minlength : "Enter at least six characters."}
         },
        errorPlacement: function(error, element) {},
        submitHandler: function(form){
           form.submit();
        }
    });*/
    
    
    /*Reset password form*/
    /*var resetpass_validator = $("#changePassFrm").bind("invalid-form.validate", function() {
        $('#action_msg').css('display','none');
        var message = "<ul>Please correct the following fields:";
        for (var x=0;x<resetpass_validator.errorList.length;x++)
        {
         message += "<li>" + resetpass_validator.errorList[x].message + "</li>";
        }
        $("#error-validation").html(message+"</ul>");   
        }).validate({
        debug: true,
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        errorContainer: $("#error-validation"),
        errorPlacement: function(error, element) { },
        rules: {
            "oldpassword": {
                required: true,
                minlength: 6, 
            },
            "password": {
                required: true,
                minlength: 6,
            },
            "cpassword": {
                required: true,
                minlength: 6,
                equalTo: "#password"
            },
        },
        messages: {
        'oldpassword': { required: "Enter old password."},
        'password': { required: "Enter new password."},
        'cpassword': { required: "Enter confirm password.", equalTo: "New password and confirm password does not match."},
        },
        submitHandler: function(form){
            $('#fadebox').css('display','block');
            form.submit();
        }
    });*/
    
    /* saveCategory FORM */
    var categorySave = $("#frmCategory").bind("invalid-form.validate", function() {
        $('#submit-msg').css('display','none');
        var message = "<ul>Please correct the following fields:";
        for (var x=0;x<categorySave.errorList.length;x++)
        {
            message += "<li>" + categorySave.errorList[x].message + "</li>";
        }
        $("#error-validation").html(message+"</ul>");   
    }).validate({
        debug: true,
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        errorContainer: $("#error-validation"),
        errorPlacement: function(error, element) {},
        rules: {
            "data[User][cat_name]":  {
                required: true,
                minlength: 6,
                maxlength: 100
            },
            "data[User][cat_id]":  {
                required: true,
                minlength: 1,
                maxlength: 11,
                number: true,
            }
        },
        messages: {
            'data[User][cat_name]': { required: "Enter Category name.", minlength: "Name should be of 6 character length" },
            'data[User][cat_id]': {required: "Enter Category Id"}
        },
        submitHandler: function(form){
            $('#fadebox').css('display','block');
            form.submit();
        }
    });
    
    /**Save SubCategory form*/
    var subcategorySave = $("#frmSubCategory").bind("invalid-form.validate", function() {
        $('#submit-msg').css('display','none');
        var message = "<ul>Please correct the following fields:";
        for (var x=0;x<subcategorySave.errorList.length;x++)
        {
            message += "<li>" + subcategorySave.errorList[x].message + "</li>";
        }
        $("#error-validation").html(message+"</ul>");   
    }).validate({
        debug: true,
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        errorContainer: $("#error-validation"),
        errorPlacement: function(error, element) {},
        rules: {
            "data[User][cat_name]":  {
                required: true,
                minlength: 6,
                maxlength: 100
            },
            "data[User][cat_id]":  {
                required: true,
            }
        },
        messages: {
            'data[User][cat_name]': { required: "Enter Category name.", minlength: "Name should be of 6 character length" },
            'data[User][cat_id]': {required: "Please select a parent category"}
        },
        submitHandler: function(form){
            if($("#cat_id").val()=="")
            {
                alert("Please select a parent category");
                return false;
            }
            $('#fadebox').css('display','block');
            form.submit();
        }
    });
    
    
    /**Save User form*/
    var userdataSave = $("#frmUser").bind("invalid-form.validate", function() {
        $('#submit-msg').css('display','none');
        var message = "<ul>Please correct the following fields:";
        for (var x=0;x < userdataSave.errorList.length;x++)
        {
            message += "<li>" + userdataSave.errorList[x].message + "</li>";
        }
        $("#error-validation").html(message+"</ul>");   
    }).validate({
        debug: true,
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        errorContainer: $("#error-validation"),
        errorPlacement: function(error, element) {},
        rules: {
            "data[User][username]":  {
                required: true,
                minlength: 6,
                maxlength: 100
            },
            "data[User][password]":  {
                required: true,
                minlength: 6,
                maxlength: 12
            },
            "data[User][email]": {
                required: true,
                email: true
            }
        },
        messages: {
            'data[User][username]': { required: "Enter valid Username." },
            'data[User][password]': {required: "Enter Password"},
            'data[User][email]' : { required: "Enter valid email id"}
        },
        submitHandler: function(form){
            $('#fadebox').css('display','block');
            form.submit();
        }
    });
    
    
    //Upload CSV validation
    var saveCSV = $("#frmUpload").bind("invalid-form.validate", function() {
        $('#submit-msg').css('display','none');
        var message = "<ul>Please correct the following fields:";
        for (var x=0;x < saveCSV.errorList.length;x++)
        {
            message += "<li>" + saveCSV.errorList[x].message + "</li>";
        }
        $("#error-validation").html(message+"</ul>");   
    }).validate({
        debug: true,
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        errorContainer: $("#error-validation"),
        errorPlacement: function(error, element) {},
        rules: {
            "data[User][csv_file]":  {
                required: true,
                //accept: "csv,xls,xlsx"
            },
        },
        messages: {
            'data[User][csv_file]': { required: "Upload CSV/Excel file." },
        },
        submitHandler: function(form){
            $('#fadebox').css('display','block');
            form.submit();
        }
    });
    
});