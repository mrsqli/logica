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

    $('a.filtercontact').unbind('click');
    $('a.filtercontact').click(function(){
        filterContact(this.id);
    });
    
   
        
        
   
    
}


