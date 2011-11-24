jQuery(document).ready(function(){
			var code;
			var id;
			var index_x;
			var ad_width;
			var ad_height;
			var ad_format;
			var ad_type;
			var color_border;
			var color_bg;
			var color_link;
			var color_text;
			var color_url;
			var client;
			var width;
			var height;
			var format;
			var type;
			var border_;
			var bg;
			var link;
			var text;
			var url;
			var ui_features;
			var features;
			var ad_script;
			var border_str;
			var pal_name = ['Default Google pallete','Open Air','Seaside','Shadow','Blue Mix','Ink','Graphite'];
			var border  = ['#FFFFFF','#FFFFFF','#336699','#000000','#6699CC','#000000','#CCCCCC'];
			var bgcolor = ['#FFFFFF','#FFFFFF','#FFFFFF','#F0F0F0','#003366','#000000','#CCCCCC'];
			var linkcol = ['#0000FF','#0000FF','#0000FF','#0000FF','#FFFFFF','#FFFFFF','#000000'];
			var urlcolor= ['#008000','#008000','#008000','#008000','#AECCEB','#999999','#666666'];
			var textcol = ['#000000','#000000','#000000','#000000','#AECCEB','#CCCCCC','#333333'];
			
			ad_script = '<script type="text/javascript"	src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'
			jQuery("#adtypeselect option[value='" + jQuery('#adtypesel_val').val() + "']").attr('selected', 'selected');
			if (jQuery('#adtypesel_val').val() == 'image') {
				jQuery('#def').css("visibility", "hidden");
				jQuery('#img_only').css("visibility", "visible");
			}			
			
			jQuery("#corner_style option[value='" + jQuery('#corner_style_val').val() + "']").attr('selected', 'selected');
			ui_features = jQuery('#corner_style :selected').val();
			jQuery("#homeAds option[value='" + jQuery('#homeads_val').val() + "']").attr('selected', 'selected');
			jQuery("input[type=radio][value='" + jQuery('#adtype_val').val() + "']").attr('checked', 'checked');
			if (jQuery('#adtype_val').val() == 'linkunit') {
					jQuery('#adtypeselect').attr('disabled', 'disabled');
					jQuery('#def').css("visibility", "hidden");
					jQuery('#img_only').css("visibility", "hidden");
					jQuery('#lnk_unit').css("visibility", "visible");
					jQuery("#link_unit option[value='" + jQuery('#link_unit_val').val() + "']").attr('selected', 'selected');
					ad_format = jQuery('#link_unit :selected').val();		
					index_x = ad_format.indexOf('x');
					ad_width = ad_format.substring(0, index_x);
					ad_height = ad_format.slice(index_x+1);	
			}
			if (jQuery('#def').css('visibility') == 'visible') {
				jQuery("#default option[value='" + jQuery('#default_val').val() + "']").attr('selected', 'selected');
				ad_format = jQuery('#default_val').val();
			}
			
			if (jQuery('#img_only').css('visibility') == 'visible') {
				jQuery("#image_only option[value='" + jQuery('#image_only_val').val() + "']").attr('selected', 'selected');
				ad_format = jQuery('#image_only_val').val();
			}
			jQuery("#pallete option[value='" + jQuery('#pallete_val').val() + "']").attr('selected', 'selected');
			jQuery("#position option[value='" + jQuery('#position_val').val() + "']").attr('selected', 'selected');
			
			
			index_x = ad_format.indexOf('x');
			ad_width = ad_format.substring(0, index_x);
			ad_height = ad_format.slice(index_x+1);
			format = "google_ad_format = \"" + ad_format + "_as\";\n";
			

			curcolor = jQuery('#pallete :selected').val();
			jQuery.each(pal_name, function(i, val) {
				if (curcolor == val){
					jQuery('#Border').val(border[i]);
					jQuery('#Title').val(linkcol[i]);
					jQuery('#Background').val(bgcolor[i]);
					jQuery('#Text').val(textcol[i]);
					jQuery('#URL').val(urlcolor[i]);
					jQuery('#Border').css("background", jQuery('#Border').val());		
					jQuery('#Title').css("background", jQuery('#Title').val());
					jQuery('#Background').css("background", jQuery('#Background').val());
					jQuery('#Text').css("background", jQuery('#Text').val());
					jQuery('#URL').css("background", jQuery('#URL').val());
				}
			});	
			
			jQuery('#Border').val(jQuery('#border_val').val());
			jQuery('#Title').val(jQuery('#title_val').val());
			jQuery('#Background').val(jQuery('#background_val').val());
			jQuery('#Text').val(jQuery('#text_val').val());
			jQuery('#URL').val(jQuery('#url_val').val());
			jQuery('#Border').css("background", jQuery('#Border').val());		
			jQuery('#Title').css("background", jQuery('#Title').val());
			jQuery('#Background').css("background", jQuery('#Background').val());
			jQuery('#Text').css("background", jQuery('#Text').val());
			jQuery('#URL').css("background", jQuery('#URL').val());
			
			
			jQuery('#pallete').change(function(){
				curcolor = jQuery('#pallete :selected').val();
				jQuery.each(pal_name, function(i, val) {
					if (curcolor == val){
					jQuery('#Border').val(border[i]);					
					jQuery('#Title').val(linkcol[i]);					
					jQuery('#Background').val(bgcolor[i]);					
					jQuery('#Text').val(textcol[i]);					
					jQuery('#URL').val(urlcolor[i]);				
					jQuery('#Border').css("background", jQuery('#Border').val());		
					jQuery('#Title').css("background", jQuery('#Title').val());
					jQuery('#Background').css("background", jQuery('#Background').val());
					jQuery('#Text').css("background", jQuery('#Text').val());
					jQuery('#URL').css("background", jQuery('#URL').val());					
					}
				});	         
			});			
			
			jQuery('#adtypeselect').change(function(){
					type_ = jQuery('#adtypeselect :selected').val();
						if ((type_ == 'text_image') || (type_ == 'text')){
							jQuery('#def').css("visibility", "visible");
							jQuery('#img_only').css("visibility", "hidden");
							ad_type = jQuery('#adtypeselect :selected').val();
							ad_format = jQuery('#default :selected').val();		
							index_x = ad_format.indexOf('x');
							ad_width = ad_format.substring(0, index_x);
							ad_height = ad_format.slice(index_x+1);	
						}
						if (type_ == 'image'){
							jQuery('#def').css("visibility", "hidden");
							jQuery('#img_only').css("visibility", "visible");
							ad_type = jQuery('#adtypeselect :selected').val();		
							ad_format = jQuery('#image_only :selected').val();		
							index_x = ad_format.indexOf('x');
							ad_width = ad_format.substring(0, index_x);
							ad_height = ad_format.slice(index_x+1);					
						}
				});
				
				jQuery('#position').change(function(){
					if (jQuery('#position :selected').val() == 'homepostend') {
						jQuery('#homeAds').removeAttr("disabled");
						jQuery("#homeAds option[value='" + jQuery('#homeads_val').val() + "']").attr('selected', 'selected');
					}
					else {
						jQuery('#homeAds').attr('disabled', 'disabled');
						jQuery("#homeAds option[value='1']").attr('selected', 'selected');
					}
				});
					if (jQuery('#position :selected').val() == 'homepostend') {
						jQuery('#homeAds').removeAttr("disabled");
						jQuery("#homeAds option[value='" + jQuery('#homeads_val').val() + "']").attr('selected', 'selected');
					}
					else {
						jQuery('#homeAds').attr('disabled', 'disabled');
						jQuery("#homeAds option[value='1']").attr('selected', 'selected');
					}


			jQuery("#ad_type1").click(function() {			
					jQuery('#adtypeselect').removeAttr("disabled");
					jQuery('#def').css("visibility", "visible");
					jQuery('#lnk_unit').css("visibility", "hidden");
					ad_format = jQuery('#default :selected').val();		
					index_x = ad_format.indexOf('x');
					ad_width = ad_format.substring(0, index_x);
					ad_height = ad_format.slice(index_x+1);
				});
				jQuery("#ad_type2").click(function() {	
					jQuery('#adtypeselect').attr('disabled', 'disabled');
					jQuery('#def').css("visibility", "hidden");
					jQuery('#img_only').css("visibility", "hidden");
					jQuery('#lnk_unit').css("visibility", "visible");								
					ad_format = jQuery('#link_unit :selected').val();		
					index_x = ad_format.indexOf('x');
					ad_width = ad_format.substring(0, index_x);
					ad_height = ad_format.slice(index_x+1);			
				});				
			jQuery('#client_id').val(jQuery('#client_id_val').val());
			
			jQuery('#default').change(function(){
				ad_format = jQuery('#default :selected').val();		
				index_x = ad_format.indexOf('x');
				ad_width = ad_format.substring(0, index_x);
				ad_height = ad_format.slice(index_x+1);
			});	  
			
			jQuery('#image_only').change(function(){
				ad_format = jQuery('#image_only :selected').val();		
				index_x = ad_format.indexOf('x');
				ad_width = ad_format.substring(0, index_x);
				ad_height = ad_format.slice(index_x+1);
				format = "google_ad_format = \"" + ad_format + "_as\";\n";
			});
			
			jQuery('#link_unit').change(function(){
				ad_format = jQuery('#link_unit :selected').val();		
				index_x = ad_format.indexOf('x');
				ad_width = ad_format.substring(0, index_x);
				ad_height = ad_format.slice(index_x+1);
				format = "google_ad_format = \"" + ad_format + "_0ads_al\";\n";
			});
			
			jQuery('#corner_style').change(function(){
				if (jQuery('#corner_style :selected').val() != 'none') {
					ui_features = jQuery('#corner_style :selected').val();
					features = "google_ui_features = \"rc:" + ui_features + "\";\n";
				}
			});

			jQuery(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
			
			function arrows() {
				jQuery('.settings_body').each( function() {
					if ( jQuery(this).css('display') == 'none' ) {
					jQuery(this).prev('.settings_head').removeClass('arrow_up');
					jQuery(this).prev('.settings_head').addClass('arrow_down');
					}
				else if ( jQuery(this).css('display') == 'block' ) {
					jQuery(this).prev('.settings_head').removeClass('arrow_down');
					jQuery(this).prev('.settings_head').addClass('arrow_up');
				}
				});
			}
			jQuery("#pos_num").hide();
			jQuery("#visual").hide();
			jQuery("#donate_menu").hide();
			jQuery(".settings_head").click(function(){
				jQuery(this).next(".settings_body").slideToggle(500);
				if ( jQuery(this).hasClass('arrow_up') ) {
					 jQuery(this).removeClass('arrow_up');
					 jQuery(this).addClass('arrow_down');
				}
				else if ( jQuery(this).hasClass('arrow_down') ) {
					jQuery(this).removeClass('arrow_down');
					jQuery(this).addClass('arrow_up');
				}
			return false;
			});
			arrows();
			
			
			jQuery('#donate').val(jQuery('#donate_val').val());

			jQuery("#Border").focus(function() {
				jQuery('#colorpicker1').farbtastic('#Border');
				jQuery("#colorpicker1").show();
				jQuery("#colorpicker2").hide();
				jQuery("#colorpicker3").hide();
				jQuery("#colorpicker4").hide();
				jQuery("#colorpicker5").hide();
			}).focusout(function () {
				jQuery("#colorpicker1").hide();
			});
			jQuery("#Title").focus(function() {
				jQuery('#colorpicker2').farbtastic( '#Title' );
				jQuery("#colorpicker1").hide();
				jQuery("#colorpicker2").show();
				jQuery("#colorpicker3").hide();
				jQuery("#colorpicker4").hide();
				jQuery("#colorpicker5").hide();
			}).focusout(function () {
				jQuery("#colorpicker2").hide();
			});
			jQuery("#Background").focus(function() {
				jQuery('#colorpicker3').farbtastic( '#Background' );
				jQuery("#colorpicker1").hide();
				jQuery("#colorpicker2").hide();
				jQuery("#colorpicker3").show();
				jQuery("#colorpicker4").hide();
				jQuery("#colorpicker5").hide();
			}).focusout(function () {
				jQuery("#colorpicker3").hide();
			});
			jQuery("#Text").focus(function() {
				jQuery('#colorpicker4').farbtastic( '#Text' );
				jQuery("#colorpicker1").hide();
				jQuery("#colorpicker2").hide();
				jQuery("#colorpicker3").hide();
				jQuery("#colorpicker4").show();
				jQuery("#colorpicker5").hide();
			}).focusout(function () {
				jQuery("#colorpicker4").hide();
			});
			jQuery("#URL").focus(function() {
				jQuery('#colorpicker5').farbtastic( '#URL' );
				jQuery("#colorpicker1").hide();
				jQuery("#colorpicker2").hide();
				jQuery("#colorpicker3").hide();
				jQuery("#colorpicker4").hide();
				jQuery("#colorpicker5").show();
			}).focusout(function () {
				jQuery("#colorpicker5").hide();
			});

			jQuery("#update").click(function () {
				id = jQuery('#client_id').val();
				color_border = jQuery('#Border').val();
				color_link = jQuery('#Title').val();
				color_bg = jQuery('#Background').val();
				color_text = jQuery('#Text').val();
				color_url = jQuery('#URL').val();
				client = '\ngoogle_ad_client = "pub-' + id + '";\n';
				width = 'google_ad_width = ' + ad_width + ';\n';
				height = 'google_ad_height = ' + ad_height + ';\n';
				if (jQuery("[name=adtype]:radio").filter(":checked").val() == 'adunit') {
					ad_type = jQuery('#adtypeselect :selected').val();
					type = 'google_ad_type = "' + ad_type + '";\n';
					format = 'google_ad_format = "' + ad_format + '_as";\n';
				}
				
				if (jQuery("[name=adtype]:radio").filter(":checked").val() == 'linkunit') {
					ad_type = jQuery('#adtypeselect :selected').val();
					type = "";
					format = 'google_ad_format = "' + ad_format + '_0ads_al";\n';
				}
				
				features = "google_ui_features = \"rc:" + ui_features + "\";\n";
				if (jQuery('#corner_style :selected').val() == 'none') {
					features = "";
				}

				border_ = 'google_color_border = "' + color_border + '";\n';
				bg = 'google_color_bg = "' + color_bg + '";\n';
				link = 'google_color_link = "' + color_link + '";\n';
				text = 'google_color_text = "' + color_text + '";\n';
				url = 'google_color_url = "' + color_url + '";\n';	
							
				code = '<script type="text/javascript">' + client + width + height + format + type + border_ + bg + link + text + url + features +'</script>\n' + ad_script;
				jQuery('#mycode').val(code);
			});
		});