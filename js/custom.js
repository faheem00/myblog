/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//Backend JS

    var q;
    var z;
 $.ajaxSetup({
        data: {
            csrf_test_name: $.cookie('csrf_cookie_name')
        }
});

function isBlank(str) {
    return (!str || /^\s*$/.test(str));
}

function loadniceditor(data){
    if(data == "1"){
    new nicEditor(
        {
            fullPanel : true,
            iconsPath : 'http://localhost/myblog/js/nicEditorIcons.gif',
            buttonList : ['fontSize','bold','italic','underline','strikeThrough','subscript','superscript','html','image']
        }
        ).panelInstance('tinymce1');
    }
    else if(data == "2"){
        new nicEditor(
        {
            fullPanel : true,
            iconsPath : 'http://localhost/myblog/js/nicEditorIcons.gif',
            buttonList : ['fontSize','bold','italic','underline','strikeThrough','subscript','superscript','html','image']
        }
        ).panelInstance('tinymce2');
    }
}

function newpostclick(){ //Function when new post is being clicked
    loadniceditor(1);
        
        //For tags
        $('.bootstrap-tagsinput').tagsinput();
        
        //For typeahead
        $('input[placeholder=Tags]').typeahead({
            name: 'tags',
            prefetch: 'backend/typeahead',
            limit: 10
        });
        
        $("#psubmit").click(function(e) {
            if (isBlank($(".nicEdit-main").text()) || isBlank($("#ptitle").val())) {
                $('#psubmit').popover('show');
                setTimeout(function() {
                    $('#psubmit').popover('destroy')
                }
                , 5000);
                //alert("There is no content in the text field or in the title field!");
                e.preventDefault();
            }
            else
                $("#psubmit").submit();
        });
}

function oldpostclick(){ //Function when old post is being clicked
            var x;
            var y;
            loadniceditor(2);
            //Load the niceditor perfectly
            $('.nicEdit-panelContain').parent().width('100%');
            $('.nicEdit-panelContain').parent().next().width('100%');
            $('.nicEdit-main').width('98%');
            $('.bootstrap-tagsinput').tagsinput();
            $('input[placeholder=Tags]').typeahead({
            name: 'tags',
            prefetch: 'backend/typeahead',
            limit: 10
            });
            $(".fa-trash-o").click(function() { //Post delete function
                x = $(this).parents("tr").attr("data-id"); //tr element, post id
                y = $(this).parents("tr"); //tr element for removing or editing
            });

            $("#postdelete").click(function() { //Delete a post
                console.log("clicked");
                $.post('backend/deletepost',
                        {
                            "pid": x
                        }, function(data) {
                    if (data == "success") { //If post has been successfully deleted
                        $("#deleteModal").modal('toggle');
                        y.remove();
                        $("#deletesuccess").show();
                    }
                });
            });
            
            function editclick() {  //Post data getting function
                $(".fa-edit").click(function() {
                    x = $(this).parents("tr").attr("data-id"); //tr element, post id
                    y = $(this).parents("tr"); //tr element for removing or editing
                    $.post('backend/editpost',
                            {
                                "pid": x
                            }, function(data) {
                        console.log(data);
                        var json = $.parseJSON(data);
                        $("#etitle").val(json.edit_title);
                        $(".nicEdit-main").html(json.edit_content);
                        $("#ptags").tagsinput('add', json.edit_tags);
                    }
                    );
                });
            }
             
            function deleteclick(){  //Post edit function
            $("#esubmit").click(function() {
                $.post('backend/editpost',
                        {
                            "post_id": x,
                            "post_title": $("#etitle").val(),
                            "post_content": $(".nicEdit-main").html(),
                            "tags_name": $("#ptags").val()
                        }, function(data) {
                    if (data !== "") { //If post has been successfully edited
                        y.replaceWith(data);
                        $("#editModal").modal('toggle');
                        $("#editsuccess").show();
                    }
                }
                );
            });
            }
            
            editclick();
            deleteclick();
            
            function pagination(){ //Pagination function
            $(".page a").click(function(e) {
                $.get($(this).attr('href'),function(data) {
                            html = $.parseJSON(data);
                            $("tbody").html(html.echo);
                            $(".page").children().remove();
                            $(".page").append(html.pagination);
                            pagination();
                            editclick();
                            deleteclick();
                        });
                 e.preventDefault();
                });
        }
        pagination();
         
}

$(document).ready(function() { 
        
        //Initial load of backend
        $("#backend").load('backend/backendc #newpostdiv', function() {
            newpostclick();
    });
    
    //Add remove active class
    $("ul li").click(function() {
        $(this).siblings("[class=active]").removeClass("active");
        $(this).addClass("active");
    })
    
    //Loading new post after clicks
    $("#newpost").click(function() {
        $("#backend").load('backend/backendc #newpostdiv', function() {
            newpostclick();
        });
    });
    
    //Loading old post after click
    $("#oldpost").click(function() {
        $("#backend").load('backend/backendc #oldpostdiv', function(){
               oldpostclick();
        });
    });
    
    //Loading other people's blog list after click
    $("#viewotherblog").click(function(){
        $("#backend").load('backend/otherblog #otherblogdiv', function(){
            
        });
    });
    
    $("#logout").click(function(){
        $.post('backend/logout',function(){
            window.location.replace('login');
        });
    });
    
    $("#viewblog").click(function(){
            window.location.replace('blog');
    });
    $("#editprofle").click(function() {
        $("#backend").load('backend/editprofile #editprofilediv', function() {
            function showhide() {
                $('button').click(function() {                  
                    if ($(this).html() == "Cancel"){
                        z = $(this).parent().parent().parent();
                        q = z.siblings().find('button');
                        $('button').prop('disabled', false)
                    }
                    else if ($(this).html() == "Edit"){
                        z = $(this).parent().parent();
                        q = z.siblings().find('button');
                        $('button').not(q).prop('disabled', true);
                    }
                    z.addClass('hide');
                    z.siblings().removeClass('hide');
                    //showhide();
                });
            }
            showhide();
            $('.profileview input').bootstrapSwitch(); //Bootstrap switch enable or disable public blog view
            $('.profileview input').on('switch-change', function(e, data) {
                        value = data.value;
                        $.post('backend/setprofiledata',{fieldname:'profileview',value:value});
            });
            //File upload
            $('#fileupload').ajaxfileupload({
                'action': 'backend/uploadpic',
                'params': {
                    csrf_test_name: $.cookie('csrf_cookie_name')
                },
                maxSize : 2048,
                'onComplete': function(response) {
                    //response = $.parseJSON(JSON.stringify(response));
                    console.log(response);
                    var alerttype, alertmessage;
                    if(response.success === false){ 
                        alerttype = 'alert-danger';
                        alertmessage = "<strong>File was not uploaded!</strong> " + response.message;
                    }
                    else if(response.success === true){
                        alerttype = 'alert-success';
                        alertmessage = "<strong>File upload successful!</strong> <a href='" + response.file_link + "' class='alert-link'>Click here</a> to see your brand new profile pic!";                        
                    }
                    console.log('custom handler for file:');
                     $(this).parent().append("<div class='alert " + alerttype + " col-md-12 fade in' style='clear:both'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" + alertmessage + "</div>");
                     if(response.success === true)$(this).remove();
                    setTimeout(function() {
                        $('.alert').slideUp(1500);
                    }, 5000);
                    setTimeout(function() {
                        $('.alert').remove();
                    }, 6000);
                    //alert(JSON.stringify(jsonresponse));
                },
                'onStart': function() {
                   
                },
                'onCancel': function() {
                    console.log('no file selected');
                }
            });       
            //Reading image file
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.avatar').attr({'src' : e.target.result,'style' : 'width:200px;height:200px;'});
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
            function readFILE(){
            $("[type=file]:last").change(function() {
                readURL(this);
                readFILE();
            });
            }
            readFILE();
            //Datepicker plugin
            $(".datepicker").datepicker({ 
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true
            });
            //Jquery bootstrap validation plugin
                $(function() {
                $(".altedit").not("[type=submit]").jqBootstrapValidation();
            }
            );
            //Editing all the profile data
            $('input[type=button]').click(function(){
                if($("[role=alert]").length == 1) return;
                q = $(this).parent().parent().parent().find('.profiledata').val();
                if(q == ""){
                    var temp = $(this).parent().parent().parent().find('.profiledata');
                    if(temp.children('input').attr('name') != "gender"){
                    alert("You cannot save an empty data!");
                    return;
                    }
                }
                z = $(this).parent().parent().parent().attr('class');
                var fieldname;
                switch(z){
                    case "usernamehide":
                        fieldname = 'username';
                        break;
                    case "fullnamehide":
                        fieldname = 'fullname';
                        break;
                    case "emailhide":
                        fieldname = 'email';
                        break;
                    case "genderhide":
                        fieldname = 'gender';
                        q = $('input[name=gender]:checked').val();
                        break;
                    case "dobhide":
                        fieldname = 'dob';
                        break;
                    case "biohide":
                        fieldname = 'bio';
                        break;
                }
                var a =  $(this).parent().parent().parent();
                var b = a.siblings().not('alert').find('.placeholder');
                $.post('backend/setprofiledata',{
                    fieldname : fieldname,
                    value : q
                },function(json){
                    var data = $.parseJSON(json);
                    if(data.echo == "true"){
                        $('button').prop('disabled', false);
                        a.addClass('hide');
                        a.siblings().removeClass('hide');
                        b.html(data.value);
                        a.parent().append("<div class='alert alert-success col-md-6 col-md-offset-2 fade in' style='clear:both'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Congrats! You have successfully saved your configuration</div>");
                        setTimeout(function() {
                            $('.alert').slideUp(1500);
                        }, 5000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 1);
                    }
                   else if(data.echo == 'false') console.log('balam pichkari');
                });
            });
        });
    });
});

