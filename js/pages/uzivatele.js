/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
        $('.nav').children('.active').removeClass('active');
    $('.nav').children('.nav-uzivatele').addClass('active'); 
    
  $("#user-edit-submit").click(function(){
	  $("#user-edit-form").submit();
  });
  
  $('#newUser').button();
  $('#newUser').click(function(){
   
     $("#user-edit-form").find("input").val(""); 
     $("#user-edit-form").find("input[name=url]").val("uzivatele/editUzivatel"); 
  });
  
    
    $('.deleteButton').on('click', function() {
      var id_uzivatel = $(this).attr('data-id');
      BootstrapDialog.show({
	  title: 'Smazat',
	  type: BootstrapDialog.TYPE_DANGER,
	  message: 'Opravdu smazat uživatele?',
	  buttons: [{
                label: 'Smazat',
		
		cssClass: 'btn-danger',
		icon: 'fa fa-check',
		
                action: function(dialog) {
                    reload("uzivatele/smazat", "id=" + id_uzivatel);
                }
            }, {
                label: 'Zrušit',
		icon: 'fa fa-close',
		
                action: function(dialog) {
                    dialog.close();
                }
            }]
      });
      
  }); 
  
  
   $(".showUserEditDialog").click(function() {
	  var id_uzivatel = $(this).attr('data-id');
          $.ajax({ 
          url: 'index.php',
          data: 'url=uzivatele/ajax&id=' + id_uzivatel,
          dataType: 'json',
          success: function(data) 
          {
	    
             $('#jmeno').val(data.jmeno);
             $('#prijmeni').val(data.prijmeni);
             $('#login').val(data.login);
	     $('#id_uzivatel').val(data.id_uzivatel);
             
          }
      });
    
  
  });
    
});
