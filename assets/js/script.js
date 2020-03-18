jQuery(document).ready(function(){

	/** Pre-loads the screen options **/
	jQuery.post( ajaxurl, {
		action: "load_integration_options"
	}, function( data ) {
		if( data.facebook==true ) {
        	jQuery('#facebook-section').show();
		} else {
        	jQuery('#facebook-section').hide();
		}

		if( data.show_basic_helpers==true ) {
        	jQuery('.copy-clipboard').show();
		} else {
        	jQuery('.copy-clipboard').hide();
		}

		if( data.show_developer_helpers==true ) {
        	jQuery('.php-code-revealer').show();
        	jQuery('.action-hook-revealer').show();
        	jQuery('.action-hook-container').css('background-color','#ebebeb');
        	jQuery('.action-hook-container').css('min-height','28px');
		} else {
        	jQuery('.php-code-revealer').hide();
        	jQuery('.action-hook-revealer').hide();
        	jQuery('.action-hook-container').css('background-color','transparent');
        	jQuery('.action-hook-container').css('min-height','0px');
		}
		console.log(data);
	}, "json");

	jQuery('#facebook-show').change(function() {
		if (jQuery(this).is(':checked')) {
        	jQuery('#facebook-section').show();
        	jQuery.post( ajaxurl, {
        		action: "update_integration_options",
        		facebook_integration: 'yes'
        	});
    	} else {
        	jQuery('#facebook-section').hide();
        	jQuery.post( ajaxurl, {
        		action: "update_integration_options",
        		facebook_integration: 'no'
        	});
    	}
	});

	jQuery('#show-basic-helpers').change(function() {
		if (jQuery(this).is(':checked')) {
        	jQuery('.copy-clipboard').show();
        	jQuery.post( ajaxurl, {
        		action: "update_integration_options",
        		show_basic_helpers: 'yes'
        	});
    	} else {
        	jQuery('.copy-clipboard').hide();
        	jQuery.post( ajaxurl, {
        		action: "update_integration_options",
        		show_basic_helpers: 'no'
        	});
    	}
	});

	jQuery('#show-developer-helpers').change(function() {
		if (jQuery(this).is(':checked')) {
        	jQuery('.php-code-revealer').show();
        	jQuery('.integration-item-unlocked').show();
        	jQuery('.action-hook-revealer').show();
        	jQuery('.action-hook-container').css('background-color','#ebebeb');
        	jQuery('.action-hook-container').css('min-height','28px');
        	jQuery.post( ajaxurl, {
        		action: "update_integration_options",
        		show_developer_helpers: 'yes'
        	});
    	} else {
        	jQuery('.php-code-revealer').hide();
        	jQuery('.integration-item-unlocked').hide();
        	jQuery('.action-hook-revealer').hide();
        	jQuery('.action-hook-container').css('background-color','transparent');
        	jQuery('.action-hook-container').css('min-height','0px');
        	jQuery.post( ajaxurl, {
        		action: "update_integration_options",
        		show_developer_helpers: 'no'
        	});
    	}
	});

	jQuery('.regular-text').select(function(){
		try {
			document.execCommand('copy');
		} catch(e) {
			alert(e);
		}
	});

	jQuery('.php-code-revealer').click(function() {
		prompt("Are you a wordpress developer? Copy this code, and use it in your theme or plugin to get " + jQuery(this).data('description') + ".", jQuery(this).data('code'));
	});

	jQuery('.action-hook-revealer').click(function() {
		prompt("Are you a wordpress developer? Copy this code template, and use it in your theme or plugin to hook custom callable actions along this section." , jQuery(this).data('code'));
	});

	jQuery('.copy-clipboard').click(function(){
		jQuery('#'+jQuery(this).data('target')).focus().select();
	});
	//

});
