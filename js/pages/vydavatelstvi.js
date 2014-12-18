/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
        $('.nav').children('.active').removeClass('active');
	 $('.nav').children('.nav-vydavatelstvi').addClass('active'); 
   
    
      $("#vydavatelstvi-edit-submit").click(function(){
	  $("#vydavatelstvi-edit-form").submit();
  });
  
  $('#newVydavatelstvi').button();
  $('#newVydavatelstvi').click(function(){
   
     $("#vydavatelstvi-edit-form").find("input").val(""); 
     $("#vydavatelstvi-edit-form").find("input[name=url]").val("vydavatelstvi/editVydavatelstvi"); 
  });
  
    
    $('.deleteButton').on('click', function() {
      var id_vydav = $(this).attr('data-id');
      BootstrapDialog.show({
	  title: 'Smazat',
	  type: BootstrapDialog.TYPE_DANGER,
	  message: 'Opravdu smazat vydavatelství?',
	  buttons: [{
                label: 'Smazat',
		
		cssClass: 'btn-danger',
		icon: 'fa fa-check',
		
                action: function(dialog) {
                    reload("vydavatelstvi/smazat", "id=" + id_vydav);
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
 
	  var id_vydav = $(this).attr('data-id');
          $.ajax({ 
          url: 'index.php',
          data: 'url=vydavatelstvi/ajax&id=' + id_vydav,
          dataType: 'json',
          success: function(data) 
          {
	  
             $('#nazev').val(data.nazev);
	     $('#id_vydavatelstvi').val(data.id_vydavatelstvi);
             
          }
      });
    
  
  });
    
});
