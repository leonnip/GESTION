$(document).ready(function(e){
	/*=== TAB INDEX===
	$(".tab_content").hide();
	$("ul.tabs li:first").addClass("active").show();
	$(".tab_content:first").show();

	$("ul.tabs li").click(function(){
		alert('aqui');
		$('.error').hide();
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_content").hide();

		var activeTab = $(this).find("a").attr("href");
		$(activeTab).fadeIn();
		return false;
	});
	*/
	
	//jQuery(".tab_content").hide();
    //jQuery(".tab_content:first").show(); 
	
	
	$("ul.tabs li").click(function() {		
		$('.error').hide();
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tab_content").hide(0);

        var activeTab = $(this).find("a").attr("href");
        $(activeTab).fadeIn(); 
		return false;
    });
	
	
	/*=== GENERAR FACTURA ===*/
	$('#geneararFact').submit(function(){
		var countChecked = function() { 
			var n = $( "input:checked" ).length;
			if(n > 2) { return true; }
			if(n < 2 || n == 0) { alert('Debe seleccionar más de 2 ordenes.'); return false; }
		};
		countChecked();
		$('#genFact').on("click", countChecked);
	});
	
	/*=== PARA EL HEADER DE INICIO ===*/
	$(window).scroll(function(){
		if ($(window).scrollTop() > 0) {
			$("#Header").css({'position': 'fixed', 'top': '0px', 'width': '100%', 'left': '0px', 'border-bottom': '1px solid #fff'});
			$('#auxiliar').css({'display': 'block'});
		} else if ($(window).scrollTop() < 1) {
			$('#Header').css({'position': 'relative'});
			$('#auxiliar').css({'display': 'none'});
		}	
	});
	
	/*===MENU GENERAL ===*/
	$('#Menu ul#Main li a').click(function(){
		$.cookie('tabs', 0);
	});
	/*=== FIN ===*/
	
	/*=== SLIDE JQUERY ===*/
	$('#navigation a').stop().animate({'marginLeft':'-40px'},1000);
		$('#navigation > li').hover(
		function() { $('a',$(this)).stop().animate({'marginLeft':'-2px'},200);
	},
	function () {
		$('a',$(this)).stop().animate({'marginLeft':'-40px'},200);
	});
	
	
	/*=== MOSTRAMOS EL EFECTO CARGANDO CADA VEZ QUE ENVIAMOS UN FORMULARIO ==*/
	$('form.loading').submit(function(){
		$(".contentLoad").show();  
	});
	
	/*=== CON ESTO CARGAMOS SEGUN FORMULARIO ENVIADO  === */
	var index = 0;
	$('form input[type=submit]').click(function(){
		var tab = $(this).attr('alt');
		$.cookie('tabs', tab);
	});
	
	if($.cookie('tabs') >= 0) { index = $.cookie('tabs'); } else { index = 0; };
	jQuery(".tab_content").hide(0);
	jQuery("ul.tabs li").removeClass("active").show();
	jQuery("ul.tabs li:eq("+index+")").addClass("active");
	jQuery(".tab_content:eq("+index+")").show();
	/*=== FIN ===*/
       
	/*=== SELECT ===*/
	jQuery(".chosen-select").chosen();
	jQuery(".chosen-select-deselect").chosen({ allow_single_deselect: true });
	
	/*=== TINYTIPS ICONOS ===*/
	/*JQuery$('img.tTip').tinyTips('black', 'title');*/
	
	/*===SELECCIONAMOS PRODUCTO===*/
	$('#selectProduct').change(function(){
		var id=$("#selectProduct").attr("value");
  		$.get('select-option.inc.php', { idproducto: id },  function(data) {
  			$('#option').html(data);
			console.log(data);
		});
	});
	
	/*=== GUARDAMOS LOS DATOS DE LOS INPUT INDEX EN COOKIES POR ALGUN ERROR ===*/
	$('#formInsertOrder input[type=text]').change(function(){
		var name = $(this).attr('name');
		var valor = $(this).val();
		$.cookie(name, valor);
	});
	$('#formInsertOrder input[type=email]').change(function(){
		var name = $(this).attr('name');
		var valor = $(this).val();
		$.cookie(name, valor);
	});
	
	/*===DESPLEGAMOS CUADRO DE MENSAJE PEDIDO===*/
	$('.caption-control-wrap').click(function(){
		$('._importante').slideToggle(100);
	});
	
	/*=== ENVIAMOS FORMULARIO PARA AÑADIR A CARRITO ===*/
	$('#formSelectProduct').submit(function(){
		$.cookie('tabs', 0);
		var idprod = $('#selectProduct').val();					
		var opt = $('#opcion').val();
		if (opt == 0) {
			alert('Seleccione Opción');
		} else {
			$.ajax({
				type: 'POST',
				url: 'add-to-car.inc.php',
				data: "idproducto="+idprod+"&opcion="+opt,
				success: function(data) {
					if (data == 1) {
						location.reload();
					}
				}, error: function(e){
					console.log(e.message);
				}														
			});
		}
		return false;
	});
	
	/*=== AÑADIMOS PRODUCTOS AL CARRITO MODIFICACION PEDIDO ===*/
	$('#formSelectProductMod').submit(function(){
		var idprod = $('#selectProduct').val();					
		var opt = $('#opcion').val();
		$.ajax({
			type: 'POST',
			url: 'add-to-car-mod.inc.php',
			data: "idproducto="+idprod+"&opcion="+opt,
			success: function(data) {
				if (data == 1) {
					location.reload();
				}
			}, error: function(e){
				console.log(e.message);
			}														
		});
		return false;
	});
	
	/*=== EFECTO TOOLTIP DE PAGINA INDEX===*/
	$("#order a").hover(function(){
		var idprod = $(this).attr('name');
		$("#"+idprod).animate({ height: "toggle"}, 20);
	});
	
	/*=== EFECTO TOOLTIP PARA TODOS LOS LINK ===*/
	$("img[title]").tooltip({
          tip: '.tooltip_customer',
          position: 'top center',
		  offset: [0, 10],
		  delay: 0         
     });
	 
	 $("a[title]").tooltip({
          tip: '.tooltip_customer',
          position: 'top center',
		  offset: [0, 10],
		  delay: 0         
     });
	 
	 
	 /*=== EFECTO TOOLTIP PARA MENU LATERAL SLIDER ===*/
	 $('#navigation a[title]').tooltip({ tip: '.tooltip_slider', position: 'center right', offset: [0,40], delay: 0 });
	
	/*=== EFECTO PARA TALA RESULTADOS SELEC TR ===*/
	$("tbody#dataCustomer tr").click(function() {
		//alert('Sin Datos a Mostrar');
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });

	/*=== BUSCAR CLIENTES INDEX ===*/
	$("#formCustomer").submit(function(){
		if ($('#filtro').val() == "" || $('#criterio').val() == '0') {
			alert('Debe Seleccionar el Criterio de Búsqueda y escribir la palabra clave.');
			$('#filtro').focus(); return false;
		} else {
			$('.contentLoad').show();
			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success: function(data){
					//$("#dataCustomer").html(data);
					$("#resultSearch1").html(data);
					$('.contentLoad').hide();
				}
			});
		}
		return false;
	});
	
	/*PARA EL LOS DETALLES DEL PEDIDO*/
	$('a#LinkVer').click(function(){
		var idorden = $(this).attr('data-id');
		var lineasorden = $(this).attr('name');
		URL = 'detalles-cliente-pedido.php?idorden='+idorden+'&lineaorden='+lineasorden;
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=650,height=640,left = 490,top = 1');");
	});
		
	/*=== BUSCAR CLIENTES PARA ANULAR PEDIDO ===*/
	$("#formCustomerEst").submit(function(){
		if ($('#filtroEst').val() == "" || $('#criterioEst').val() == '0') {
			alert('Debe Seleccionar el Criterio de Búsqueda y escribir la palabra clave...');
			$('#filtroEst').focus(); return false;
		} else {
			$('.contentLoad').show();
			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success: function(data){
					$("#resultSearch2").html(data);
					$('.contentLoad').hide();
				}
			});
		}
		return false;
	});
	
	/*=== BUSCAR CLIENTES PARA APUNTAR IMPORTE PAGADO POR REDUR ===*/
	$("#formCustomerPay").submit(function(){
		if ($('#filtroEst').val() == "" || $('#criterioEst').val() == '0') {
			alert('Debe Seleccionar el Criterio de Búsqueda y escribir la palabra clave....');
			$('#filtroEst').focus(); return false;
		} else {
			$('.contentLoad').show();
			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success: function(data){
					$("#resultSearch1").html(data);
					$('.contentLoad').hide();
				}
			});
		}
		return false;
	});
	
	
	/*=== DESABILITAMOS EL IMPUT EMAIL ===*/
	if($('#sinemail').is(':checked')) { $('input[type=email]#email_payment').attr('disabled', true); $('#email_payment').css('background', '#F2F4F4'); }
	$('#sinemail').click(function(){
		if ($('#sinemail').is(':checked')) {
		      $('input[type=email]#email_payment').attr('disabled', true);
			  $('#email_payment').css('background', '#F2F4F4');
			  $("label.error").fadeOut();	
    	} else {
        	  $('input[type=email]#email_payment').removeAttr('disabled');
			  $('#email_payment').css('background', 'white');
		}
	});
	
	/*=== VALLIDAMOS EN FORMULARIO DE AGREGAR PEDIDO ===*/
	
	$(".formularios").validate({
		rules: {
			name_payment: "required",
			last_name_payment: "required",
			dni_payment: "required",
			phone_payment: "required",			
			email_payment: {
				required: true,
				email: true
			},			
			sexo_payment: {
				required: true,
			},
			address_payment: "required",
			number_payment: "required",
			piso_payment: "required",
			door_payment: "required",
			cp_payment: "required",
			pais_payment: { required: true, },
			city_payment: { required: true, },
			province_payment: { required: true,	},
			_direc_: { required: true, }	
		},
		messages: {
			name_payment: "Obligatorio",
			last_name_payment: "Obligatorio",
			dni_payment: "Obligatorio",
			phone_payment: "Obligatorio",
			email_payment: "No válido",
			address_payment: "Obligatorio",
			number_payment: "*",
			piso_payment: "*",
			door_payment: "*",
			cp_payment: "Obligatorio",
			pais_payment: "Obligatorio",
			sexo_payment: "Obligatorio",
			city_payment: "Obligatorio",
			province_payment: "Obligatorio",
			_direc_: "Obligatorio"
		}
	});
	
	$('#direcciones input[type=radio]').click(function(){
		var IdPais = $(this).attr('data-title');
		$.post("update-carrito.inc.php", { pais: IdPais }).done(function(data) { 
			$('table#order').html(data);
		});
	});
	
	$('#pais_payment').change(function(){
		var IdPais = $(this).val();
		//alert(IdPais);
		$.post("update-carrito.inc.php", { pais: IdPais }).done(function(data) { 
			$('table#order').html(data);
		})
	});
	/*=== FIN VALIDAR FORMULARIO ===*/
	
	
	/*=== VALIDAR CUPON DESCUENTO ===*/
	$('#validar').click(function(){		
		var codDescuento = $('#codDescuento').val();
		if (codDescuento == "") {
			$('#codDescuento').focus();
			return false;
		} else {
			$.post("validate.cupon.inc.php", { CodDescuento: codDescuento }).done(function(data) {
				if (data == 1) {
					$('#invalido').hide(); $('#valido').show();
					$('#validar').hide(); $('#aplicarDescuento').show();
				} else {
					$('#valido').hide(); $('#invalido').show();
					$('#validar').show(); $('#aplicarDescuento').hide();
				}
			});
		}
		return false;
	});
	
	$('#aplicarDescuento').click(function(){
		$.post("aplicate.discount.inc.php", { cod: 'aplicar' }).done(function(data) {
			window.location.reload();
		});
	});
	
	/*=== AÑADIR NUEVA DIRECCIÓN ===*/
	$('#ocultarDir').css('display', 'none');
	$('#nuevaDir').click(function(){
		$('.address').slideDown(10);
		$('#ocultarDir').show();	
		$('#nuevaDir').hide();	
		$('label.error').hide();
		$('#address_payment').removeAttr('disabled'); $('#number_payment').removeAttr('disabled'); $('#piso_payment').removeAttr('disabled'); $('#door_payment').removeAttr('disabled'); $('#cod_payment').removeAttr('disabled'); $('#city_payment').removeAttr('disabled');
		$('table#direcciones input').attr('disabled', true); $('#cp_payment').removeAttr('disabled'); $('#pais_payment').removeAttr('disabled');  $('#province_payment').removeAttr('disabled');
	});
	$('#ocultarDir').click(function(){
		$('.address').slideUp(10);
		$('#ocultarDir').hide();
		$('#nuevaDir').show();		
		$('label.error').hide();
		$('#address_payment').attr('disabled', true); $('#number_payment').attr('disabled', true); $('#piso_payment').attr('disabled', true); $('#door_payment').attr('disabled', true); $('#cod_payment').attr('disabled', true); $('#city_payment').attr('disabled', true)
		$('#province_payment').attr('disabled', true); $('table#direcciones input').removeAttr('disabled');  $('#pais_payment').attr('dosabled', true);  $('#province_payment').attr('dosabled', true);
	});
	
	/*=== SELECCIONAR O DESELECCIONAR CHECKBOX PEDIDOS ===*/
	// Seleccionar Todos      
   	$("A[href='#select_all']").click( function() { 
	    $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").attr('checked', true);
	    return false;        
	});         
	//  Deseleccionar Todos       
	$("A[href='#select_none']").click( function() {
	   	$("#" + $(this).attr('rel') + " INPUT[type='checkbox']").attr('checked', false);
	   	return false;
	});         
	// Invertir Selección       
	$("A[href='#invert_selection']").click( function() {
	    $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").each( function() {
		    $(this).attr('checked', !$(this).attr('checked'));
		});            
		return false;        
	});   
	
	/*=== EXPORTAR A EXCEL FICHEROS ===*/
	$('table#exportExcel button').click(function(){
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				alert(data);
			}
		});		
	});
	
	/*=== BUSCAR PRODUCTOS PARA FACTURACION Y STOCK ===*/
	$('#formBuscar').submit(function(){
		if ($('#producto').val() != ""){
			cadena = $('#producto').val();
			$.post('buscar-productos.inc.php', { dato: cadena }).done(function(data){
				$('#dataCustomerS').html(data);
			});
		} else {
			alert('Debe escribir el producto a buscar.');
		}
		return false;
	});
	
	/*=== BUSCAR PRODUCTOS REFERENCIA Y STOCK ===*/
	$('#formBuscarReferencia').submit(function(){
		if ($('#producto').val() != ""){
			cadena = $('#producto').val();
			$.post('buscar-productos-referencia.inc.php', { dato: cadena }).done(function(data){
				$('#dataCustomerS').html(data);
			});
		} else {
			alert('Debe escribir el producto a buscar.');
		}
		return false;
	});
	
	
	
	/*=== EVENTO KEYUP DE GENERAR DEVOLUCION ===*/
	$( "input#importeDev" ).keyup(function(event) {
		var devolucion = $(this).attr('data-id');
		var importe = $('#importeDev').val();
		alert(importe);
		if (importe == "") {
			$('#submitDev').hide();
		} else {
	  		if ( importe <= devolucion ) {
				$('#submitDev').show();
  			} else {
				$('#submitDev').hide();
			}
		}
	});
	
	/*=== CAMBIAR ESTADOS DE ORDENES CONTRA-REMBOLSO-TARJETA-PAYPAL ===*/  
	$('div#frm form').submit(function(){
		var fielset = $(this).attr('data-title');
		var form = $(this).attr('id');
		if ($("#"+form+" .check").is(':checked')) {
			if ($('#'+form+' #estadoOrder').val() != 0) {
				$('.contentLoad').show();
				$.ajax({
					type: 'POST',
					url: $(this).attr('action'),
					data: $(this).serialize(),
					success: function(data) {
						$('.contentLoad').hide();
						$('fieldset#'+fielset).html(data);											
					}
				});
			} else {
				alert("Seleccione Estado...");
			}
		} else {
			alert("No has seleccionado Ordenes a cambiar estado...");
		}
		return false;
	});
	
	/*=== SELECCION DE FECHAS ===*/
	$('#Fecha1').dateinput({ format: "yyyy-mm-dd"});
	$('#Fecha2').dateinput({ format: "yyyy-mm-dd"});
	
	$('#consultar').click(function(){
		if ($('#Fecha1').val() == "") { $('#Fecha1').focus(); return false; }
		if ($('#Fecha2').val() == "") { $('#Fecha2').focus(); return false; }
		if ($('#Fecha1').val() == "" && $('#Fecha2').val() == "") {								
			$("#Fecha1").dateinput({ format: "yyyy-mm-dd" });
		}
	});
	
	$('#Fecha3').dateinput({ format: "yyyy-mm-dd"});
	$('#Fecha4').dateinput({ format: "yyyy-mm-dd"});
	
	$('#Fecha5').dateinput({ format: "yyyy-mm-dd"});
	$('#Fecha6').dateinput({ format: "yyyy-mm-dd"});	
	
	
	/*=== EXPORT TABLAS EXCEL JQUERY ===*/
	$(".tab_container #btnExport").click(function(e) {
		var num = $(this).attr('alt');
		alert('=>'+num);
		var datas = encodeURIComponent($('#dvData'+num).html());
		$('#values'+num).val(datas);
		$('#excel'+num).submit();
		
    	/*window.open('ficheroExcel.php?' + encodeURIComponent($('#dvData').html()));
	    e.preventDefault();
		return false;*/
	});
	
	/*=== BUSCAMOS ORDENES POR FECHAS
	$('#formPedidosFecha').submit(function(){
		$('.contentLoad').show();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				$('.contentLoad').hide();
				$('#dataCustomerEst').html(data);
			}
		});
		return false;
	});
	*/
	
	/*=== FORMULARIO PARA OBTENER INFORMES DE PEDIDOS ===
	$('#formInformeComp').submit(function(){
		$('.contentLoad').show();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				$('.contentLoad').hide();
				$('#resultSearchCompleto').html(data);
			}
		});
		return false;
	});
	*/
	
	$('button#exportExcelTarjetaSelect').click(function(){
		document.form_cambiar_estadosT.action = 'export-excel-tarjeta-seleccion.inc.php';
		document.form_cambiar_estadosT.submit();
		return false;
	})
	$('button#exportExcelContraSelect').click(function(){
		document.form_cambiar_estadosC.action = 'export-excel-contra-seleccion.inc.php';
		document.form_cambiar_estadosC.submit();
		return false;
	})
	
	/*=== TICK SEPARAR PRODUCTOS LISTADOS ===*/
	$('#dataCustomerL input[type=checkbox]').click(function(){
		$('.contentLoad').show();
		var idoferta = $(this).attr("id");
		if($("#"+idoferta).is(':checked')) {  				 				
			$.get("proceso-modelo-listados.inc.php", {id_oferta: idoferta, activo: 1}, function() { $('.contentLoad').hide(); }); 
		} else {  			 				
			$.get("proceso-modelo-listados.inc.php", {id_oferta: idoferta, activo: 0}, function() { $('.contentLoad').hide();  });   
		}    				
	});
	
	$.get("recibe-parametros2.php", {nombre: "Evandro", edad: "99"}, function(respuesta){
   $("#miparrafo").html(respuesta);
})
	
	/*=== PAGINACION INDEX ===*/
	$('#green').smartpaginator({ totalrecords: 0, recordsperpage: 0, datacontainer: 'resultSearch1', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });	
	//$('#green1').smartpaginator({ totalrecords: 60, recordsperpage: 16, datacontainer: 'divs', dataelement: 'fieldset', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
	
	/*=== PAGINACION CUSTOMER ==*/
	$('#green2').smartpaginator({ totalrecords: 0, recordsperpage: 0, datacontainer: 'resultSearch_Opt', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });	
	$('#green3').smartpaginator({ totalrecords: 0, recordsperpage: 0, datacontainer: 'resultSearch_Opt', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
	
	/*=== PAGINACION COSTES FACTURACION ===*/	
	$('#green4').smartpaginator({ totalrecords: 0, recordsperpage: 0, datacontainer: 'resultSearch1', dataelement: 'tr', initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'green' });
		
	
});