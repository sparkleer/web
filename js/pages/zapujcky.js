/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
        $('.nav').children('.active').removeClass('active');
    $('.nav').children('.nav-zapujcky').addClass('active');
    
    $('.infoButton').click(function() {
	var id_knihy = $(this).attr("data-id");
	reload("knihy/hledat&search%5Bknihy.id_kniha%5D=" + id_knihy,"");
    });
    
    $('.vypujcenoSelect').on('change', function(){
	var id_knihy = $(this).attr("data-id-knihy");
	var id_user = $(this).attr("data-id-user");
	var status = $(this).val();
	if (status == 1) {
	   $('#deleteButton-'+id_user+'-'+id_knihy).attr("disabled", true);
	} else {
	   $('#deleteButton-'+id_user+'-'+id_knihy).removeAttr("disabled"); 
	}
	
	
	$.ajax({ 
          url: 'index.php', 
          data: 'url=zapujcky/changeStatus&id_kniha='+id_knihy+'&id_user='+id_user+'&status='+status,
          dataType: 'json',
          success: function(data)
          {
	      $('#datetimeVypujceni-'+id_user+'-'+id_knihy).html("<small class='"+data.color+"'>"+data.datetime+"</small>");
	      
	      $('#statusBar-'+id_user+'-'+id_knihy).removeClass("red orange green");
	      $('#statusBar-'+id_user+'-'+id_knihy).addClass(data.color);
	    
          }
      });
    });
    
    $('.deleteButton').on('click', function() {
      var id_kniha = $(this).attr('data-id');
	  var id_user = $(this).attr('data-id-user');
      BootstrapDialog.show({
	  title: 'Smazat',
	  type: BootstrapDialog.TYPE_DANGER,
	  message: 'Opravdu smazat rezervaci?',
	  buttons: [{
                label: 'Smazat',
		
		cssClass: 'btn-danger',
		icon: 'fa fa-check',
		
                action: function(dialog) {
                    reload("zapujcky/smazat", "id=" + id_kniha+"&user_id="+id_user);
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
    
});
