/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//Backend JS

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

$(document).ready(function() { 
        
        //Initial load of backend
        $("#backend").load('backend/backendc #newpostdiv', function() {
        loadniceditor(1);

        $('.bootstrap-tagsinput').tagsinput(); //For tags

        $("#psubmit").click(function(e) { //Function when new post is being submitted
            if (isBlank($(".nicEdit-main").text()) || isBlank($("#ptitle").val())){
                $('#tinymce1').popover('show');
                    setTimeout(function() {
                        $('#tinymce1').popover('destroy')
                    }
                    , 5000);         
                //alert("There is no content in the text field or in the title field!");
                e.preventDefault();
            }
            else
                $("#psubmit").submit();
        });
    });
    
    //Add remove active class
    $("ul li").click(function() {
        $(this).siblings("[class=active]").removeClass("active");
        $(this).addClass("active");
    })
    
    //Loading new post after clicks
    $("#newpost").click(function() {
        $("#backend").load('backend/backendc #newpostdiv', function() {
            loadniceditor(1);
            
            $('.bootstrap-tagsinput').tagsinput(); //For tags
            
            $("#psubmit").click(function(e) { //Function when new post is being submitted
                if (isBlank($(".nicEdit-main").text()) || isBlank($("#ptitle").val())) {
                    $('#tinymce1').popover('show');
                    setTimeout(function() {
                        $('#tinymce1').popover('destroy')
                    }
                    , 5000);
                    //alert("There is no content in the text field or in the title field!");
                    e.preventDefault();
                }
                else
                    $("#psubmit").submit();
            });
        });
    });
    
    //Loading old post after click
    $("#oldpost").click(function() {
        $("#backend").load('backend/backendc #oldpostdiv', function(){
            var x;
            var y;
            loadniceditor(2);
            //Load the niceditor perfectly
            $('.nicEdit-panelContain').parent().width('100%');
            $('.nicEdit-panelContain').parent().next().width('100%');
            $('.nicEdit-main').width('98%');
            $('.bootstrap-tagsinput').tagsinput();
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

});


