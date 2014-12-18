/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function reload(url_tag, params) {
    var url = location.protocol + '//' + location.host + location.pathname;   
		    
    url += '?url=' + url_tag + '&' + params;
		   
    window.location.href = url;
 }
 
 
$(document).ready(function() {
   $('[data-toggle=tooltip]').tooltip({container: 'body'}) ;
    $(":submit").button();
   
	
   $("#user-settings-edit-submit").click(function(){
	  $("#user-settings-edit-form").submit();
   });
   
      $(".showUserSettingsEditDialog").click(function() {
	  var id_uzivatel = $(this).attr('data-id');
	  
          $.ajax({ 
          url: 'index.php',
          data: 'url=uzivatele/ajax&id=' + id_uzivatel,
          dataType: 'json',
          success: function(data) 
          {
	    
             $('#user-jmeno').val(data.jmeno);
             $('#user-prijmeni').val(data.prijmeni);
             $('#user-login').val(data.login);
	     $('#user-id_uzivatel').val(id_uzivatel);
             
          }
      });
    
  
  });

});