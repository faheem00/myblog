<!DOCTYPE html>
<html>
    <head>
        <title>Faheem's Blog</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css">
        <script src="<?php echo base_url(); ?>js/jquery-2.0.3.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>    
        <script src="<?php echo base_url(); ?>js/jquery.cookie.js"></script>  
        <script src="<?php echo base_url(); ?>js/moment.min.js"></script>  
        <script src="<?php echo base_url(); ?>js/livestamp.min.js"></script> 
        <script src="<?php echo base_url(); ?>js/custom.js"></script>
    </head>
    <body>
        <header>
            <div class="text-center">Welcome to Faheem's Blog</div>
            <div style="position:absolute;right:0;top:0;"><a href="backend" style="text-decoration: none;font-size: 1em;">View Backend</a></div>
        </header> <!-- End of Header -->
        <div class="container">
            <div class="row">
                <div class="col-md-3" style='position: relative'>
                        <div class='row blogstack'>
                            <?php foreach ($postdata as $datalist): ?>
                            <a class='col-md-12' data-id="<?php echo $datalist->post_id; ?>"><?php echo $datalist->post_title; ?></a>
                            <?php endforeach; ?>
                        </div>
                </div> <!-- End of sidebar contents -->
                
                <div id="postbody" class="col-md-9">
                    
                </div>
            </div>
        </div>
        <script>
        //Declare disqus variables
        var disqus_shortname,disqus_identifier,disqus_url,dsq;
        $(document).ready(function() {  
        //Initial
        $(".blogstack a:first").addClass("active");
        function showblogpost(z,x){ //Passing post id to controller
            $.post('blog/getid',
                    {
                        "post_id" : z
                    },
                    function(){
                        $("#postbody").load('blog/showblogpost',function(){ //Load blog post    
                         $("#loadcomments").click(function(){
                              if(x == "1" || typeof DISQUS == "undefined") {
                            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                             // Required - Replace example with your forum shortname
                            disqus_shortname = 'faheemsblog'
                            disqus_identifier = 'blog_' + z;
                            disqus_url = "http://faheemsblog.info/blog_" + z; 
                            /* * * DON'T EDIT BELOW THIS LINE * * */
                            dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                            }
                              else if(x=="2"){
                                DISQUS.reset({
                                reload: true,
                                config: function () {  
                                this.page.identifier = 'blog_' + z;
                                this.page.url = "http://faheemsblog.info/blog_" + z;  
                             }
                           });
                              }
                              $(this).remove(); //Remove comment button
                         })

                            
                        $(".likebutton").click(function(){ //If like button is being clicked
                            id = $(".blogstack a[class='col-md-12 active']").attr("data-id");
                                $.post('blog/insertlike',{
                                    'post_id' : id
                                },function(data){
                                    $(".postlike").text(data);
                                    $(".likebutton").parent("div").remove();
                                    $(".postlike").parent("div").append("<div class='row text-info'>Liked</div>");
                                });
                         });
                         
                         $("img").addClass("img-responsive");
                            
                        }); 
                    });
        }
        
        showblogpost($(".blogstack a:first").attr("data-id"),1); //Show the initial post
        
        //After initial
        $(".blogstack a").click(function(){ //On clicking a post title
        if($(this).hasClass("active")) return; //If clicked on current post, do nothing.
        var z = $(this).attr("data-id"); //data-id of a post
        showblogpost(z,2);
        $(".blogstack a[class='col-md-12 active']").removeClass("active"); //Remove current active class
        $(this).addClass("active"); //Add active class
        });
        
        });
        
        </script>
    </body>
</html>