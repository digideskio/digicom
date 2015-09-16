/**
 * @version 	1.0.0
 * @package 	Com DigiCOm
 * @author 		ThemeXpert
 * @copyright 	Copyright (c) 2006 - 2014 ThemeXpert Ltd. All rights reserved.
 * @license 	GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
imgpath = "";
/* Media manager*/

function elFinderUpdate(fieldID, value) {
  var fileNameIndex = value.lastIndexOf("/") + 1;
  var filename = value.substr(fileNameIndex);
  filename = filename.replace(/\.[^/.]+$/, "");
  var filenameID = fieldID.replace("url", "name");

  jQuery('#' + fieldID).val(value);
  jQuery('#' + filenameID).val(filename);
  if (typeof SqueezeBox !== 'undefined' && jQuery.isFunction(SqueezeBox)) {
  	SqueezeBox.close();
  } else {
	 parent.document.getElementById('sbox-btn-close').click()
  }
}

function changeValidity(){
  var validityType = jQuery('#jform_price_type').chosen(),
      lengthBox = jQuery('#jform_expiration_length'),
      lengthType = jQuery('#jform_expiration_type');
  if(validityType.val() != 0){
    lengthBox.removeClass('disabled').removeAttr('disabled').attr('required','false');
    lengthType.attr('disabled', false).trigger("liszt:updated");
  }else{
    lengthBox.addClass('disabled').attr('disabled','disabled').attr('required','true');
    lengthType.attr('disabled', true).trigger("liszt:updated");
  }
}

function openModal(a){
  fileInput = jQuery(a).prev();
  SqueezeBox.open('index.php?option=com_digicom&view=filemanager&tmpl=component&folder='+imgpath+'&layout=modal&fieldID='+fileInput.attr('id'),{handler:'iframe',size:{x:800,y:450}});
}

function changeOrderStatus(id,task,index,val){

  var f = document.adminForm, i, cbx,
    cb = f[id],
    status = f['orderstatus'+index];
    if (cb) {
        for (i = 0; true; i++) {
            cbx = f['cb'+i];
            if (!cbx)
                break;
            cbx.checked = false;
        } // for
        cb.checked = true;
    }

    if(status){
      orderstatus = f['orderstatus'+index];
      for (i = 0; true; i++) {
          cbx = f['orderstatus'+i];
          if (!cbx)
              break;
          cbx.value = '';
      } // for
      status.value = val;

    }

    f.boxchecked.value = 1;

    submitbutton(task);
}

function showProperTemplateEmail(type, e){
	//console.log(type);return;
  e.preventDefault();

  var templateType = jQuery('#jform_'+ type +'_email_type').chosen(),
		htmlTemplate = jQuery('.email_template_digicom.email_template_digicom_'+type+'_html'),
		rawTemplate = jQuery('.email_template_digicom.email_template_digicom_'+type+'_raw');
	//console.log(templateType.val());
	if(templateType.val() == 'html'){
		htmlTemplate.parent().parent().show();
		rawTemplate.parent().parent().hide();
	}else{
		htmlTemplate.parent().parent().hide();
    rawTemplate.parent().parent().show();
	}
}

jQuery(document).ready(function() {

  jQuery('.email_template_digicom').parent().parent().hide();

  /* Load the value from localStorage*/
  if (typeof(Storage) !== "undefined")
  {
    var toggleDigiSidebar = localStorage.getItem('digisidebar',false);
    if(toggleDigiSidebar == 'true'){
      jQuery("body").addClass("sidebar-collapse");
    }else{
      jQuery("body").removeClass("sidebar-collapse");
      localStorage.setItem('digisidebar', false);
    }
  }

  jQuery("a[href*=#togglesidebar]").click(function(e) {

    if(jQuery("body").hasClass("sidebar-collapse")) {
      /* if it's closed, then remove class and open it */
      jQuery("body").removeClass("sidebar-collapse");

      // Load the value from localStorage
      if (typeof(Storage) !== "undefined")
      {
        /* Set the last selection in localStorage */
        localStorage.setItem('digisidebar', false);
      }
    } else {
      /* as its open, close it */
      jQuery("body").addClass("sidebar-collapse");

      // Load the value from localStorage
      if (typeof(Storage) !== "undefined")
      {
        /* Set the last selection in localStorage */
        localStorage.setItem('digisidebar', true);
      }
    }

  });

	jQuery("#toggle_settings").toggle(function (e) {
		e.preventDefault();
		jQuery("body").addClass("sidebar-right-collapse");
	}, function () {
		jQuery("body").removeClass("sidebar-right-collapse");
	});

	jQuery('body').click(function(){
		if(jQuery('body').hasClass('sidebar-right-collapse')){
			jQuery('body').removeClass('sidebar-right-collapse');
		}
	});

  /* Toggle toolbar button*/
  var btnSelector = jQuery('#toolbar-publish,#toolbar-unpublish,#toolbar-trash,#toolbar-delete,#toolbar-edit');

  jQuery("input[type='checkbox']").change( function(){

    if(!jQuery("input[type='checkbox']").is(':checked')){
     btnSelector.removeClass('in');
    }else{
     btnSelector.addClass('in');
    }

    jQuery('input[name="checkall-toggle"]').change( function(){
      if( this.checked){
        btnSelector.removeClass('in');
        btnSelector.addClass('in');
      }else{
       btnSelector.removeClass('in');
      }
    });
  });

  /* onclick edit alias of product*/
  jQuery('a#digicom-edit-alias').click(function () {
    jQuery('#digicom-product-alias').hide();
    jQuery('#digicom-product-alias-edit').show(function(){
      jQuery('#jform_alias').focus();
    });
  });

  jQuery('input[id=jform_alias]').focusout(function() {
    jQuery('#digicom-product-alias').text(jQuery('#jform_alias').val());
    jQuery('#digicom-product-alias').show();
    jQuery('#digicom-product-alias-edit').hide();
  });

	var sortable = {
		update: function (event, ui) {
			//create the array that hold the positions...
		  var order = [];
			//loop trought each li...
		   jQuery('#digicom_item_files_items .container tr').each( function(e) {
			jQuery(this).find('input[id^=files_ordering_]').val(jQuery(this).index());

		  });
		}
	};
	jQuery('.repeat').each(function() {
		jQuery(this).repeatable_fields({
			'sortable_options': sortable
		});
	});



});
