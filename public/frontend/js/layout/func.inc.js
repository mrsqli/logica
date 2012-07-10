/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Comment
 */
function catchEventsLayout() {
    
    /**
     * Refresh Feeds every 10 minutes
     */

    
    
    $('#submit_sponsor').unbind('click');
    $('#submit_sponsor').click(function(){
        sendMsgForSponsor($('#fistName_son').val(),$('#lastName_son').val(),$('#email_son').val());
    });
    //
//    $('#submit_search').unbind('click');
//    $('#submit_search').click(function(){
//        searchcontact($('#search').val(),$('#status').val());
//    });
//        
   
    
        
        
   
    
}


