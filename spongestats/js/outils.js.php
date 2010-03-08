// Definition des liens et actions JQuery



$(document).ready(
	function()
	{

		//Onglets
		$("#menu li").each(function()
				{
				$( this ).bind ("click", function()
					{
					$("#menu li").removeClass("ongleton");
					$(this).addClass("ongleton");
					});
				}
			);

		//Themes
		$("#selecteur").change(function() {
		 switchStylestyle($(this).find('option:selected').val());
                return false;
		});
		var c = readCookie('style');

        if (c=null)
			{
			switchStylestyle(c);
			$(this).find('option[@value=\''+c+'\']').attr('selected','selected');
			}
				else
			{
		createCookie('style', '<?php echo $_GET['default_theme']; ?>', 365);
			}

		function switchStylestyle(styleName)
			{
			$('link[@rel*=style][@title]').each(function(i)
				{
						this.disabled = true;
						if (this.getAttribute('title') == styleName) this.disabled = false;
				});
		  createCookie('style', styleName, 365);
			}
		$(".lien_historique").livequery('click',function()
					{

					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.post("includes/"+this.id.split('-')[0]+".php",{order:this.id.split('-')[1],valeur:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
					});
					
	$(".plus_details").livequery('click',function()
					{
					var iddiv = this.id.split('-')[1];
					$("#details-"+iddiv).html('<span class="divloader"></span>');
					$.get("includes/"+this.id.split('-')[0]+".php",{id:this.id.split('-')[1],details:this.id.split('-')[2]},function(txt){$("#details-"+iddiv).html(txt);});
					$("#details-"+iddiv).slideToggle();
					document.getElementById("ajax").style.display='none';
					}
				);

		$(".plus_details_user_agents").livequery('click',function()
				{
				var iddiv = this.id.split('-')[1];
				$("#details-"+iddiv).slideToggle("slow");
				//document.getElementById("ajax").style.display='none';
				}
			);
		$(".plus_details_keywords").livequery('click',function()
				{
				$("#details-evolution").html('<span class="divloader"></span>');
				$.get("includes/plus_details.php",{id:this.id.split('-')[1],details:"keywords",evolution:this.id.split('-')[2]},function(txt){$("#details-evolution").html(txt);});
				document.getElementById("ajax").style.display='none';
				document.getElementById('details').style.display='none';
				}
			);
		// Recherche
	$(".lien_recherche").livequery('click',function()
					{
					var champorder = document.getElementById('order').value;
					var champvaleur = document.getElementById('valeur').value;
					$("#spongestats").html('');
					$('#ajax')
						.ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
	$.post("includes/recherche.php",{order:champorder,valeur:champvaleur},function(txt){$("#spongestats").html(txt);});
					});

	// Menu du haut
	$(".lien_menu").livequery('click',function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					if (this.id.split('-')[0]=="admin_home")
						{
						$.get("includes/admin_home.php",function(txt){
							$("#spongestats").html(txt);
							}
							);
						}
					else
						{
						$.get("includes/"+this.id.split('-')[0]+".php",{annee:this.id.split('-')[1],mois:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
						$.get("includes/archives.php?vue="+this.id.split('-')[0],{mois:this.id.split('-')[1],annee:this.id.split('-')[2]},function(txt){$("#archives").html(txt);});
						}
						return false;
					}
				);

	// Archives
	$(".lien_archive").livequery('click',function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.get("includes/"+this.id.split('-')[0]+".php",{mois:this.id.split('-')[1],annee:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
					});

	// Documentation
	$(".lien_doc").livequery('click',function()
					{
					$("#spongestats").html('');
					var language = $("#default_language").html();
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.get("docs/doc."+language+".html",{mois:this.id.split('-')[1],annee:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
					});
	
		// Home
	$(".lien_home").livequery('click',function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.get("includes/home.php",{mois:this.id.split('-')[1],annee:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
					});
	$(".lien_journee").livequery('click',function()
					{
					var champorder = document.getElementById('order').value;
					var champvaleur = document.getElementById('valeur').value;
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.post("includes/recherche.php",{order:champorder,valeur:champvaleur},function(txt){$("#spongestats").html(txt);});
					});	
	
	// Affiche historique
	$(".lien_historique").livequery('click',function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.post("includes/"+this.id.split('-')[0]+".php",{order:this.id.split('-')[1],valeur:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
					});
	
	// Statistiques annuelles
	$(".lien_annee").livequery('click',function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.get("includes/"+this.id.split('-')[0]+".php",{annee:this.id.split('-')[1]},function(txt){$("#spongestats").html(txt);});
					});
	
	// Statistiques quotidiennes
	$(".lien_jour").livequery('click',function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.get("includes/"+this.id.split('-')[0]+".php",{jour:this.id.split('-')[1],mois:this.id.split('-')[2],annee:this.id.split('-')[3]},function(txt){$("#spongestats").html(txt);});
					}
				);
	
	// Statistiques mensuelles
	$(".lien_mois").livequery('click',function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.get("includes/"+this.id.split('-')[0]+".php",{mois:this.id.split('-')[1],annee:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
					});
	$(".lien_go_auth").unbind('click').livequery('click',function()
					{
					var adminpass=document.getElementById("sps_admin_pass").value;
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.post("includes/admin_home.php",{pass:adminpass},function(txt){$("#spongestats").html(txt);});
					});
		// Purge
	$(".lien_go_purge").livequery('click',function()
					{
					var datestart=document.getElementById('purge').value;
					$("#spongestats").html('');

					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.post("includes/admin_home.php",{purge:1,purgemois:datestart},function(txt){$("#spongestats").html(txt);});
					}
				);
			
	// Change pass
	$(".lien_go_pass").livequery('click',function()
					{
					var passwd1=document.getElementById('pass1').value;
					var passwd2=document.getElementById('pass2').value;
					
					if(passwd1!=passwd2 || passwd1=='' || passwd1.lenght < 6)
						{
						alert("Error \n- Passwords must be the same\n- Passwords must have at least 6 caracters");
						}
					else
						{
						$("#spongestats").html('');
						$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
						$.post("includes/admin_home.php",{passwd:1,password:passwd1},function(txt){$("#spongestats").html(txt);});
						}
					}
				);
				
	// Installation
	
	$(".bouton_verif_parametres").livequery('click',function(){
			$(this).click(
					function()
					{
					var sps_server=document.getElementById('sps_server').value;
					var sps_user=document.getElementById('sps_user').value;
					var sps_pass=document.getElementById('sps_pass').value;
					var sps_base=document.getElementById('sps_base').value;
					var sps_prefix=document.getElementById('sps_prefix').value;
					var sps_admin_pass=document.getElementById('sps_admin_pass').value;
				$.get("action.php",{action:'test_params',sps_server:sps_server,sps_user:sps_user,sps_pass:sps_pass,sps_base:sps_base,db_prefix:sps_prefix,sps_admin_pass:sps_admin_pass},function(txt){$("#test_params").html(txt);});
					});
				}
			);
			
	$("#bouton_install_spongestats").livequery('click',function() {
		

				var sps_server=document.getElementById('sps_server').value;
				var sps_user=document.getElementById('sps_user').value;
				var sps_pass=document.getElementById('sps_pass').value;
				var sps_base=document.getElementById('sps_base').value;
				var db_prefix=document.getElementById('sps_prefix').value;
				var sps_admin_pass=document.getElementById('sps_admin_pass').value;
				$.get("action.php",{action:'install_spongestats',sps_server:sps_server,sps_user:sps_user,sps_pass:sps_pass,sps_base:sps_base,db_prefix:db_prefix,sps_admin_pass:sps_admin_pass},function(txt){$("#installation").html(txt);});
			}
		);
	$("#bouton_verif_parametres").livequery('click',function() {
				var sps_server=document.getElementById('sps_server').value;
					var sps_user=document.getElementById('sps_user').value;
					var sps_pass=document.getElementById('sps_pass').value;
					var sps_base=document.getElementById('sps_base').value;
					var sps_prefix=document.getElementById('sps_prefix').value;
					var sps_admin_pass=document.getElementById('sps_admin_pass').value;
					$.get("action.php",{action:'test_params',sps_server:sps_server,sps_user:sps_user,sps_pass:sps_pass,sps_base:sps_base,db_prefix:sps_prefix,sps_admin_pass:sps_admin_pass},function(txt){$("#test_params").html(txt);});
					
			}
		);
	// Fin des definitions JQuery	
	}
);
