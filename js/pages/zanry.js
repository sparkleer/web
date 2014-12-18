/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
        $('.nav').children('.active').removeClass('active');
	 $('.nav').children('.nav-zanry').addClass('active'); 
   
    
      $("#zanr-edit-submit").click(function(){
	  $("#zanr-edit-form").submit();
  });
  
  $('#newZanr').button();
  $('#newZanr').click(function(){
   
     $("#zanr-edit-form").find("input").val(""); 
     $("#zanr-edit-form").find("input[name=url]").val("zanry/editZanr"); 
  });
  
    
    $('.deleteButton').on('click', function() {
      var id_zanr = $(this).attr('data-id');
      BootstrapDialog.show({
	  title: 'Smazat',
	  type: BootstrapDialog.TYPE_DANGER,
	  message: 'Opravdu smazat žánr?',
	  buttons: [{
                label: 'Smazat',
		
		cssClass: 'btn-danger',
		icon: 'fa fa-check',
		
                action: function(dialog) {
                    reload("zanry/smazat", "id=" + id_zanr);
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
 
	  var id_zanr = $(this).attr('data-id');
          $.ajax({ 
          url: 'index.php',
          data: 'url=zanry/ajax&id=' + id_zanr,
          dataType: 'json',
          success: function(data) 
          {
	  
             $('#nazev').val(data.nazev);
             
	     $('#id_zanr').val(data.id_zanr);
             
          }
      });
    
  
  });
    
});
