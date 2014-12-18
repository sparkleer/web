/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
        $('.nav').children('.active').removeClass('active');
	 $('.nav').children('.nav-autori').addClass('active'); 
   
    
      $("#autor-edit-submit").click(function(){
	  $("#autor-edit-form").submit();
  });
  
  $('#newAutor').button();
  $('#newAutor').click(function(){
   
     $("#autor-edit-form").find("input").val(""); 
     $("#autor-edit-form").find("input[name=url]").val("autori/editAutor"); 
  });
  
    
    $('.deleteButton').on('click', function() {
      var id_uzivatel = $(this).attr('data-id');
      BootstrapDialog.show({
	  title: 'Smazat',
	  type: BootstrapDialog.TYPE_DANGER,
	  message: 'Opravdu smazat autora?',
	  buttons: [{
                label: 'Smazat',
		
		cssClass: 'btn-danger',
		icon: 'fa fa-check',
		
                action: function(dialog) {
                    reload("autori/smazat", "id=" + id_uzivatel);
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
 
	  var id_autor = $(this).attr('data-id');
          $.ajax({ 
          url: 'index.php',
          data: 'url=autori/ajax&id=' + id_autor,
          dataType: 'json',
          success: function(data) 
          {
	  
             $('#jmeno').val(data.jmeno);
             $('#prijmeni').val(data.prijmeni);
	     $('#id_autor').val(data.id_autor);
             
          }
      });
    
  
  });
    
});
