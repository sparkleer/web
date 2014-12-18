/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    
    $('.nav').children('.active').removeClass('active');
    $('.nav').children('.nav-knihy').addClass('active');
    
    $('.disabled-part input,.disabled-part textarea, .disabled-part select').attr('disabled', true);
    
    $('.rezervovatDialog').on('click', function() {
      var id_kniha = $(this).attr('data-id');
      BootstrapDialog.show({
	  title: 'Potvrdit rezervaci',
	  type: BootstrapDialog.TYPE_INFO,
	  message: 'Opravdu si přejete zarezervovat tuto knihu?',
	  buttons: [{
                label: 'Ano',
		
		cssClass: 'btn-info',
		icon: 'fa fa-check',
		
                action: function(dialog) {
                    reload("knihy/rezervovat", "id=" + id_kniha);
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
  
  $("#search-submit").click(function(){
	  $("#search-form").submit();
  });
  
  $("#delete-filter").click(function() {
	  reload("knihy/seznam", "");
  })
  
  $(".infoDialog").click(function() {
	  var id_kniha = $(this).attr('data-id');
          $.ajax({ 
          url: 'index.php', 
          data: 'url=knihy/ajax&id=' + id_kniha,
          dataType: 'json',
          success: function(data)
          {
	      
             $('#nazev_knihy').val(data.nazev_knihy);
             $('#rok').val(data.rok);
            
             $('#isbn').val(data.isbn);
             $('#pocet_na_sklade').val(data.pocet);
             $('#vydani').val(data.vydani);
             $('#id_kniha').val(id_kniha);
             $('#jazyk').val(data.jazyk);
	     
	     // ---------- Autori
	     $("#autor").html('');
	     $.each(data.autori, function(key, value) {   
		  $('#autor').append($('<option>', {value:value.id_autor, text:value.jmeno + ' ' + value.prijmeni}));  
		  
		   if (value.selected != null) {
		       $('#autor').find('option[value='+value.id_autor+']').attr("selected", true);
		   }
   
	     });
	     // ---------- Zanry
	     $("#zanr").html('');
	     $.each(data.zanry, function(key, value) {   
		  $('#zanr').append($('<option>', {value:value.id_zanr, text:value.nazev}));  

	     });

	     $('#zanr').find('option[value='+data.zanr+']').attr("selected", true);

	     // --------- Vydavatelstvi
	     $("#vydavatelstvi").html('');
	     $.each(data.vydavatelstvi, function(key, value) {   
		  $('#vydavatelstvi').append($('<option>', {value:value.id_vydavatelstvi, text:value.nazev}));  

	     });

	     $('#vydavatelstvi').find('option[value='+data.vydavatelstvi+']').attr("selected", true);

          }
      });
  });
  
      $('.smazatButton').on('click', function() {
      var id_kniha = $(this).attr('data-id');
      BootstrapDialog.show({
	  title: 'Smazat',
	  type: BootstrapDialog.TYPE_DANGER,
	  message: 'Opravdu smazat knihu?',
	  buttons: [{
                label: 'Smazat',
		
		cssClass: 'btn-danger',
		icon: 'fa fa-check',
		
                action: function(dialog) {
                    reload("knihy/smazat", "id=" + id_kniha);
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
  
   $('#newBook').button();
   $('#newBook').click(function(){
   
     $("#book-edit-form").find("input").val(""); 
     $("#book-edit-form").find("input[name=url]").val("knihy/editKniha"); 
     $("#book-edit-form").find("select").html(''); 
     
     $.ajax({ 
          url: 'index.php', 
          data: 'url=knihy/ajax&id=-1',
          dataType: 'json',
          success: function(data)
          {
	     
	     // ---------- Autori
	     $.each(data.autori, function(key, value) {   
		  $('#autor').append($('<option>', {value:value.id_autor, text:value.jmeno + ' ' + value.prijmeni}));  
		  
		
	     });
	     // ---------- Zanry
	     $.each(data.zanry, function(key, value) {   
		  $('#zanr').append($('<option>', {value:value.id_zanr, text:value.nazev}));  

	     });

	    
	     // --------- Vydavatelstvi
	     $("#vydavatelstvi").html('');
	     $.each(data.vydavatelstvi, function(key, value) {   
		  $('#vydavatelstvi').append($('<option>', {value:value.id_vydavatelstvi, text:value.nazev}));  

	     });

	    
          }
      });
     
  });
  
  $("#book-edit-submit").click(function(){
      $("#book-edit-form").submit();
  });
  
    
});
