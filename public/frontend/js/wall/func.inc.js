/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Comment
 */
function catchEvents() {
    
    /**
     * Refresh Feeds every 10 minutes
     */

    $('a.filter').unbind('click');
    $('a.filter').click(function(){
        $('a.filter').removeClass('bold');
        $(this).addClass('bold');
        filterPosts(this.id);
    });
    
    $('input.comment_submit').unbind('click');
    $('input.comment_submit').click(function(){
        var element = $(this);
        var Id = element.attr("name");
        addCommentToPost(Id,$('#text_'+Id).val());
    });
    //
    
    $('a.more_comments').unbind('click');        
    $('a.more_comments').bind('click', function() {
        var element = $(this);
        //var Id = element.attr("name");
        element.parents('span').siblings("div.comment_block").toggle();
    });
       
       
    $('a.comment_link').unbind('click');        
    $('a.comment_link').bind('click', function() {
        var element = $(this);
        var Id = element.attr("name");
        $("#diplaycomment"+Id).toggle();
    });
    
    $('a.delete_comment').unbind('click');        
    $('a.delete_comment').bind('click', function() {
 
        deleteComment(this.id)
    });
        
   
    /*
     * Refresh posts
     */        
    var refreshId = setInterval( function()
    {           
        notifyPost();
    }, 10000);
        
        
   
    
}


