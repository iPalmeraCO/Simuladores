<?php include('includes/header.php') ?>

<style type="text/css">
  .fondo-azul {
  display: inline-block;
  width: 100%;
}

.first_col, .textbox {
      color: #22a6e9;
}

.sub {
  padding-left: 0px;
  margin-bottom: 10px;
    margin-top: 10px;
}

.tit {
  font-weight: bold;
}

</style>
<script type="text/javascript">
Number.prototype.formatMoney = function(c, d, t){
  var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
$(window).bind("load",function(){
	var textbox_G6 = $("#txt_E3").autoNumeric('init');
	var textbox_G6 = $("#txt_E6").autoNumeric('init');
    var monto      = $("#monto").autoNumeric('init');
    $("#TRM").autoNumeric('init');   
});

   $(document).ready(function() {
        hidderesults();
        document.getElementById( 'txt_E10' ).disabled = 'true';
    });

    function hidderesults(){
        document.getElementById('resultados').style.display="none";
    }

    function showresults(){
        document.getElementById('resultados').style.display="block";
	}

	$(function() {
        $('#txt_E3').autoNumeric('init');
        $('#txt_E6').autoNumeric('init');
    });

function cambiarSelect()
{
   var op = $("#divisa").val();
   var html = '';
   var html2 = '';
   if(op == "DIVISAACOP")
   {
      html +='<option value="USD">USD</option>';
      html +='<option value="CHF">CHF</option>';
      html +='<option value="CAD">CAD</option>';
      html +='<option value="GBP">GBP</option>';
      html +='<option value="EUR">EUR</option>';
      html +='<option value="JPY">JPY</option>';
      html +='<option value="SEK">SEK</option>';
      html +='<option value="CNY">CNY</option>';
      html +='<option value="MXN">MXN</option>';
       // $("#divisacop").show();
       // $("#copdivisa").hide();
       $("#montoen").html("USD"); 
      html2 +='<option value="COP">COP</option>';
   }else {
      html +='<option value="COP">COP</option>';
       // $("#divisacop").hide();
       // $("#copdivisa").show();
       $("#montoen").html("COP"); 
      html2 += '<option value="USD">USD</option>';
      html2 += '<option value="CHF">CHF</option>';
      html2 += '<option value="CAD">CAD</option>';
      html2 += '<option value="GBP">GBP</option>';
      html2 += '<option value="EUR">EUR</option>';
      html2 += '<option value="JPY">JPY</option>';
      html2 += '<option value="SEK">SEK</option>';
      html2 += '<option value="CNY">CNY</option>';
      html2 += '<option value="MXN">MXN</option>';
   }
   $('#divOrigen').html(html);
   $('#divgiro').html(html2);
}

function cambiardivisa()
{
    $("#montoen").html($("#divOrigen").val());
}

function cambiardivisa2()
{
    $("#montoen").html("COP");
}
/*
* Calcular giro o reintegro
*/

function getArraygiros(operacion, consulta, divisa_giro, montocop, resulgiro)
{
  var valor    = 0;
  if (operacion == 'GIRO') {
    if (consulta == 'COPADIVISA') {
      if (divisa_giro =="COP") {
        valor = montocop * parseFloat(buscardivgir(divisa_giro));
        return valor;
      }else if (divisa_giro  == "USD") {
        valor = montocop / parseFloat(buscardivgir(divisa_giro));
        return valor;
      }else if( divisa_giro == "CAD" || divisa_giro == "CHF" || divisa_giro == "JPY" || divisa_giro == "SEK" || divisa_giro == "CNY" || divisa_giro == "MXN"){
        valor  =  resulgiro * parseFloat(buscardivgir(divisa_giro)); 
        return valor;
      }else if (divisa_giro == "GBP" || divisa_giro == "EUR") {
        valor  =  resulgiro / parseFloat(buscardivgir(divisa_giro));
        return valor;
      }
    }else{
      if (divisa_giro =="COP" || divisa_giro  == "USD") {
        valor = montocop * parseFloat(buscardivgir(divisa_giro));
        return valor;
      }else if (divisa_giro  == "CAD" || divisa_giro == "CHF" || divisa_giro == "JPY" || divisa_giro == "SEK" || divisa_giro == "CNY" || divisa_giro == "MXN") {
        valor = (montocop/parseFloat(buscardivgir(divisa_giro)))*parseFloat(buscardivgir('USD'));
        return valor;
      }else if (divisa_giro  == "GBP" || divisa_giro == "EUR") {
        valor = (montocop*parseFloat(buscardivgir(divisa_giro)))*parseFloat(buscardivgir('USD'));
        return valor;
      }
    }
  }else{
    if (consulta == 'COPADIVISA') {
      if (divisa_giro =="COP") {
        valor = montocop * parseFloat(buscardivreint('USD'));
        return valor;
      }else if (divisa_giro  == "USD") {
        valor = montocop / parseFloat(buscardivreint('COP'));
        return valor;
      }else if (divisa_giro  == "CAD" || divisa_giro == "CHF" || divisa_giro == "JPY" || divisa_giro == "SEK" || divisa_giro == "CNY" || divisa_giro == "MXN") {
        valor = resulgiro * parseFloat(buscardivreint(divisa_giro));
        return valor;
      }else if (divisa_giro == "GBP" || divisa_giro == "EUR") {
        valor  =  resulgiro / parseFloat(buscardivreint(divisa_giro));
        return valor;
      }
    }else{
      if (divisa_giro =="COP") {
        valor = montocop * parseFloat(buscardivgir(divisa_giro));
        return valor;
      }else if (divisa_giro  == "USD") {
        valor = montocop * parseFloat(buscardivreint('COP'));
        return valor;
      }else if (divisa_giro  == "CAD" || divisa_giro == "CHF" || divisa_giro == "JPY" || divisa_giro == "SEK" || divisa_giro == "CNY" || divisa_giro == "MXN") {
        valor = (montocop/parseFloat(buscardivreint(divisa_giro)))*parseFloat(buscardivreint('COP'));
        return valor;
      }else if (divisa_giro  == "GBP" || divisa_giro == "EUR") {
        valor = (montocop*parseFloat(buscardivreint(divisa_giro)))*parseFloat(buscardivreint('COP'));
        return valor;
      }
    }
  }
}

function getArraygiro()
{
   var arrGiro = [
   	  "COP:1",
   	  "USD:2892.62",
   	  "CAD:1.2504",
   	  "CHF:0.9539",
   	  "GBP:1.3578",
      "EUR:1.2090",
      "JPY:106.93",
      "SEK:8.5125",
      "CNY:6.5479",
      "MXN:17.92"
    ];
    return arrGiro;
}
function getArrayreintegro()
{
   var arrReintegro = [
      "COP:2857",
      "USD:1",
      "CAD:1.3547",
      "CHF:1.0333",
      "GBP:1.2533",
      "EUR:1.1160",
      "JPY:115.84",
      "SEK:9.2219",
      "CNY:7.0935",
      "MXN:19.41"
    ];
    
    return arrReintegro;
}

function buscardivreint(val)
{
	var arr = getArrayreintegro();
	var res = 0;
	for( i = 0; i < arr.length; i++ )
	{
        if(val == arr[i].split(":")[0])
        {
          return res = arr[i].split(":")[1];
        }
	}
}
function buscardivgir(val)
{
  var arr = getArraygiro();
  var res = 0;
  for( i = 0; i < arr.length; i++ )
  {
        if(val == arr[i].split(":")[0])
        {
          return res = arr[i].split(":")[1];
        }
  }
}

function calcularMoneda()
{
	//montoresen
	var tipo        = $("#txt_E8").val();
	var tipocons    = $("#divisa").val();
	var divigiro    = $("#divgiro").val();
  var divorigen   = $("#divOrigen").val();
	var monto       = $("#monto").autoNumeric('get');
	var arrgiro     = getArraygiro();
  var arrReintegro= getArrayreintegro();
	var resgirodiv  = 0;
	var resulgiro   = 0;
	var tipoban     = 0;
	var papel       = 5300;
	var trm         = 0;
	var gmf         = 0;
	var costotgiro  = 0;
	var vtucom      = 0;
	var vtuswift    = 0;
	var vtupor      = 0;
	var iva         = 0.19;
	var grupogiro   = 15;
	var nogrupogiro = 25;
  var gruporeintegro   = 10;
  var nogruporeintegro = 20;
  var montoencop  = 0;

	if( monto <= 0 || isNaN(monto) )
	{
      alert("Por favor Ingrese el Monto");
      return;
	}else{
    monto = parseFloat(monto);
  }
  tipoban = $("#tipo").val();  // tipo de operación
  trm     = $("#TRM").autoNumeric('get');  // valor TRM
  if (tipoban == 'GRUPO') {
    tipobanlabel = 'GRUPO';
  }else{
    tipobanlabel = 'NO GRUPO';
  }
	if( tipo == "GIRO" )
	{
    // yjol
    $("#textmsnswift").html('COMISIÓN MENSAJE SWIFT');
    $("#labelvtucomision").html('VTU COMISIÓN MENSAJE SWIFT');
    $('#tipodivisa2').html('DIVISA GIRO');
		// tipo de consulta
        if( tipocons  == "COPADIVISA")
        {  
          
          $('#textmonto1').html('MONTO OPERACIÓN EN USD');
           $("#trmonto").show();	// monto en usd
           tipoban = $("#tipo").val();  // tipo de operación
           trm     = $("#TRM").autoNumeric('get');  // valor TRM
           
           if( trm <= 0 || isNaN(trm) )
           {
           	  alert("Por favor Ingrese la TRM");
           	  return;
           }else{
              trm = parseFloat(trm);
           }

           resgirodiv = buscardivgir(divigiro);
           $("#montoresen").html("MONTO EN COP");
           // $("#montofirst").html(numberToCurrency(monto));
           $("#montofirst").html('$ '+monto.formatMoney(2, ',', '.'));
           $("#divresgiro").html(divigiro);
           
           resulgiro = parseFloat(monto) / parseFloat(arrgiro[1].split(":")[1] );  // monto en usd
           //console.log(arrgiro[0].split(":")[1]);
           //console.log(getArraygiros(divigiro,monto));

           // $("#monusd").html( numberToCurrency( resulgiro.toFixed(2) ) );
           $("#monusd").html('$ '+resulgiro.formatMoney(2, ',', '.'));
           if (divigiro == 'USD') {
            $("#trmonto2").hide();
          }else{
            $("#montogirores").html( "MONTO OPERACIÓN EN " + divigiro );
            $("#trmonto2").show();
          }
           // $("#montoenres1").html( numberToCurrency( getArraygiros(tipo,tipocons,divigiro,monto, resulgiro).toFixed(2) ));
           montoenress = getArraygiros(tipo,tipocons,divigiro,monto, resulgiro);
           $("#montoenres1").html('$ '+montoenress.formatMoney(2, ',', '.'));

           $("#tipobanco").html(tipobanlabel);
           $("#resTRM").html( numberToCurrency(trm) );
           var montoencop = monto;

           // estaba lo de grupo
           
        }else {
          $("#montoresen").html("MONTO EN "+divorigen);
          if (divorigen == 'USD') {
            resulgiro = parseFloat(monto) * parseFloat(arrgiro[1].split(":")[1] );
            // $("#monusd").html( numberToCurrency( resulgiro.toFixed(2) ) );
            $("#monusd").html('$ '+resulgiro.formatMoney(2, ',', '.'));
            $('#textmonto1').html('MONTO OPERACIÓN EN COP');
            $("#trmonto").show();
            $("#trmonto2").hide();
          }else{
            $('#textmonto1').html('');
            $("#trmonto").hide();
            $("#montogirores").html( "MONTO OPERACIÓN EN " + divigiro );
            $("#trmonto2").show();
            // resulgiro = (parseFloat(monto)/parseFloat(buscardivgir(divorigen))) * parseFloat(arrgiro[1].split(":")[1] );
          }
          
          // montoenress = getArraygiros(tipo,tipocons,divigiro,monto, resulgiro);
          montoenress = getArraygiros(tipo,tipocons,divorigen,monto, resulgiro);
          var montoencop = montoenress;
          $("#montoenres1").html('$ '+montoenress.formatMoney(2, ',', '.'));
          // $("#montoenres1").html('$ '+montoencop.formatMoney(2, ',', '.'));
          // $("#montofirst").html(numberToCurrency(monto));
          $("#montofirst").html('$ '+monto.formatMoney(2, ',', '.'));
          $("#divresgiro").html(divigiro);
          console.log("entro:"+divigiro); 
          $("#tipobanco").html(tipobanlabel);
           $("#resTRM").html( numberToCurrency(trm) ); 
        }

        if( tipoban == "GRUPO" )
           {
               //grupogiro*trm;
               // $("#msgSwif").html(numberToCurrency( grupogiro * trm ) );
               var comisionmsgswif = grupogiro * trm;
               var ivamensajesw = iva * comisionmsgswif;
               var ivacomision = iva * papel;
               var totalcomisiones = papel + comisionmsgswif;
               var comimasiva = (papel + comisionmsgswif) + (ivamensajesw + ivacomision);
               var copcomiiva = montoencop+comimasiva;
               $("#msgSwif").html('$ '+(comisionmsgswif).formatMoney(2, ',', '.'));
               $("#ivamsgSwif").html('$ '+(ivamensajesw).formatMoney(2, ',', '.'));
               // $("#papel").html( numberToCurrency(papel) );
               $("#papel").html('$ '+papel.formatMoney(2, ',', '.'));
               $("#ivapapel").html('$ '+(ivacomision).formatMoney(2, ',', '.'));
               // $("#totcom").html( numberToCurrency( papel + ( grupogiro * trm ) ) );
               
               $("#totcom").html('$ '+(totalcomisiones).formatMoney(2, ',', '.'));
               $("#totaliva").html('$ '+(ivamensajesw + ivacomision).formatMoney(2, ',', '.'));
               
               $("#totalcomisioniva").html('$ '+comimasiva.formatMoney(2, ',', '.'));
               
               $("#copcomiiva").html('$ '+(copcomiiva).formatMoney(2, ',', '.'));

               gmf        = ( copcomiiva *  4 ) / 1000;
               costotgiro = gmf + totalcomisiones ;
               // vtucom     = papel  + gmf;
               // vtuswift   = grupogiro * trm; 
               vtucomiswift  = comisionmsgswif + papel;
               // vtupor     = ( costotgiro / monto ) + ( vtuswift / monto ) ;
               vtupor     = totalcomisiones/(montoencop+totalcomisiones);

               // $("#gmf").html(numberToCurrency(  gmf   ) );
               $("#gmf").html('$ '+gmf.formatMoney(2, ',', '.'));
               // $("#cosgirotot").html(numberToCurrency(costotgiro) );
               $("#cosgirotot").html('$ '+costotgiro.formatMoney(2, ',', '.'));
               // $("#vtucom").html(numberToCurrency(vtucom));
               // $("#vtucom").html('$ '+vtucom.formatMoney(2, ',', '.'));
               // $("#vtuswift").html(numberToCurrency(vtuswift));
               $("#vtucomiswift").html('$ '+vtucomiswift.formatMoney(2, ',', '.'));
               $("#vtupor").html( (vtupor * 100).toFixed(4) + " %");


           }else if( tipoban == "NOGRUPO" ){
                var comisionmsgswif = nogrupogiro * trm;
               var ivamensajesw = iva * comisionmsgswif;
               var ivacomision = iva * papel;
               var totalcomisiones = papel + comisionmsgswif;
               var comimasiva = (papel + comisionmsgswif) + (ivamensajesw + ivacomision);
               var copcomiiva = montoencop+comimasiva;
               // $("#msgSwif").html(numberToCurrency( nogrupogiro * trm ) );
               $("#msgSwif").html('$ '+(comisionmsgswif).formatMoney(2, ',', '.'));
               $("#ivamsgSwif").html('$ '+(ivamensajesw).formatMoney(2, ',', '.'));
               // $("#papel").html( numberToCurrency(papel) );
               $("#papel").html('$ '+papel.formatMoney(2, ',', '.'));
               $("#ivapapel").html('$ '+(ivacomision).formatMoney(2, ',', '.'));
               // $("#totcom").html( numberToCurrency( papel + ( nogrupogiro * trm ) ) );
               $("#totcom").html('$ '+(totalcomisiones).formatMoney(2, ',', '.'));
               $("#totaliva").html('$ '+(ivamensajesw + ivacomision).formatMoney(2, ',', '.'));
               
               $("#totalcomisioniva").html('$ '+comimasiva.formatMoney(2, ',', '.'));
               
               $("#copcomiiva").html('$ '+(copcomiiva).formatMoney(2, ',', '.'));
               

                gmf        = ( copcomiiva *  4 ) / 1000;
               costotgiro = gmf + totalcomisiones ;
               // vtucom     = papel  + gmf;
               // vtuswift   = grupogiro * trm; 
               vtucomiswift  = comisionmsgswif + papel;
               // vtupor     = ( costotgiro / monto ) + ( vtuswift / monto ) ;
               vtupor     = totalcomisiones/(montoencop+totalcomisiones);

               // $("#gmf").html(numberToCurrency(  gmf   ) );
               $("#gmf").html('$ '+gmf.formatMoney(2, ',', '.'));
               // $("#cosgirotot").html(numberToCurrency(costotgiro) );
               $("#cosgirotot").html('$ '+costotgiro.formatMoney(2, ',', '.'));
               // $("#vtucom").html(numberToCurrency(vtucom));
               // $("#vtucom").html('$ '+vtucom.formatMoney(2, ',', '.'));
               // $("#vtuswift").html(numberToCurrency(vtuswift));
               $("#vtucomiswift").html('$ '+vtucomiswift.formatMoney(2, ',', '.'));
               $("#vtupor").html( (vtupor * 100).toFixed(4) + " %");

           }
      
      $('#mostrarcomisiones').show();
	}else{
    // yeison
    $("#tipobanco").html(tipobanlabel);
    $("#resTRM").html( numberToCurrency(trm) );
    $("#textmsnswift").html('ORDEN DE PAGO RECIBIDA');
    $("#labelvtucomision").html('VTU COMISIÓN ORDEN DE PAGO RECIBIDA');
    if (tipoban == 'GRUPO') {
      girobank = gruporeintegro;
    }else{
      girobank = nogruporeintegro;
    }
    var ordenpago = girobank * trm;
    var ivamensajesw = iva * ordenpago;
    var ivacomision = iva * papel;
    var totalcomisiones = papel + ordenpago;
    var comimasiva = (papel + ordenpago) + (ivamensajesw + ivacomision);
    if( tipocons  == "COPADIVISA"){
      montoenress = monto;
    }else{
      montoenress = getArraygiros(tipo,tipocons,divorigen,monto, resulgiro);
    }
    var montoencop = montoenress;
    var copcomiiva = montoencop+comimasiva;
    $("#msgSwif").html('$ '+(ordenpago).formatMoney(2, ',', '.'));
    $("#ivamsgSwif").html('$ '+(ivamensajesw).formatMoney(2, ',', '.'));
    $("#papel").html('$ '+papel.formatMoney(2, ',', '.'));
    $("#ivapapel").html('$ '+(ivacomision).formatMoney(2, ',', '.'));
    $("#totcom").html('$ '+(totalcomisiones).formatMoney(2, ',', '.'));
    $("#totaliva").html('$ '+(ivamensajesw + ivacomision).formatMoney(2, ',', '.'));
    $("#totalcomisioniva").html('$ '+comimasiva.formatMoney(2, ',', '.'));
    $("#copcomiiva").html('$ '+(copcomiiva).formatMoney(2, ',', '.'));
    gmf  = ( comimasiva *  4 ) / 1000;
    costotgiro = gmf + totalcomisiones;
    vtucomiswift  = ordenpago + papel;
    vtupor     = totalcomisiones/(montoencop+totalcomisiones);
    $("#gmf").html('$ '+gmf.formatMoney(2, ',', '.'));
    $("#cosgirotot").html('$ '+costotgiro.formatMoney(2, ',', '.'));
    $("#vtucomiswift").html('$ '+vtucomiswift.formatMoney(2, ',', '.'));
    $("#vtupor").html( (vtupor * 100).toFixed(4) + " %");



    $('#tipodivisa2').html('DIVISA REINTEGRO');
    $("#montoresen").html("MONTO EN "+divorigen);
    $("#montofirst").html('$ '+monto.formatMoney(2, ',', '.'));
    $("#divresgiro").html(divigiro);
    if( tipocons  == "COPADIVISA"){
      $("#trmonto").show();
      $('#textmonto1').html('MONTO OPERACIÓN EN USD');
      resulgiro = parseFloat(monto) / parseFloat(arrReintegro[0].split(":")[1] );  // monto en usd
      $("#monusd").html('$ '+resulgiro.formatMoney(2, ',', '.'));
      if (divigiro == 'USD') {
        $("#trmonto2").hide();
      }else{
        $("#montogirores").html( "MONTO OPERACIÓN EN " + divigiro );
        $("#trmonto2").show();
      }
      // montoenress = getArraygiros(tipo,tipocons,divigiro,monto, resulgiro);
      $("#montoenres1").html('$ '+montoenress.formatMoney(2, ',', '.'));
    }else{
      if (divorigen == 'USD') {
            resulgiro = parseFloat(monto) * parseFloat(arrReintegro[0].split(":")[1] );  // monto en usd
            $("#monusd").html('$ '+resulgiro.formatMoney(2, ',', '.'));
            $('#textmonto1').html('MONTO EN OPERACIÓN EN COP');
            $("#trmonto").show();
            $("#trmonto2").hide();
          }else{
            $('#textmonto1').html('');
            $("#trmonto").hide();
            $("#montogirores").html( "MONTO EN OPERACIÓN EN " + divigiro );
            $("#trmonto2").show();
          }
          // montoenress = getArraygiros(tipo,tipocons,divigiro,monto, resulgiro);
          montoenress = getArraygiros(tipo,tipocons,divorigen,monto, resulgiro);
          $("#montoenres1").html('$ '+montoenress.formatMoney(2, ',', '.'));
    }
    // $("#vtucom").html('$ '+(0).formatMoney(2, ',', '.'));
    // $("#vtuswift").html('$ '+(0).formatMoney(2, ',', '.'));
    // $("#vtupor").html( ((0) * 100).toFixed(4) + " %");

    $('#mostrarcomisiones').show();
  }
    
    $('#resultados').show();
    $('#textsimu').hide();
}

    function calculateFormula6()
    {
        var E3=$("#txt_E3").autoNumeric('get'); 		//var E3=$("#txt_E3").val();
        E3=parseFloat(E3);

		if (E3 <= 0 || isNaN(E3)){
			alert("Por favor ingrese el monto")	;
			return;
		};


        var E6=$("#txt_E6").autoNumeric('get'); 		//var E3=$("#txt_E3").val();
        E6=parseFloat(E6);

		if (E6 <= 0 || isNaN(E6)){
			alert("Por favor ingrese el valor de la posición inicial")	;
			return;
		};

        var E8=$("#txt_E8").val();
        var result=tasa(E3,E8)

        $("#txt_E10").val(result.toFixed(2) + '%');
        result=parseFloat(result);
        var E12=$("#txt_E12").val();
        var E14=$("#txt_E14").val();

        //-------------- ~~~~~~~~~~~~~!!!!!!!  code for Second table  !!!!!!!!!~~~~~~~~~~~~~~  -----------------------//

        var E18=E8;
        $("#txt_E18").html(E18+' '+'Meses');
        var G18=E8;
        $("#txt_G18").html(G18+' '+'Meses');
        var a=(E6*25)/100;
        var F20=a+E6;
        F20=numberToCurrency(F20);
        $("#txt_F20").html(F20);
        var b=(E6*41)/100;
        var F21=b+E6;
        F21=numberToCurrency(F21);
        $("#txt_F21").html(F21);
        var c=(E6*56)/100;
        var F22=c+E6;
        F22=numberToCurrency(F22);
        $("#txt_F22").html(F22);
        var d=(E6*81)/100;
        var F23=d+E6;
        F23=numberToCurrency(F23);
        $("#txt_F23").html(F23);
        var e=(E6*40)/100;
        var H20=e+E6;
        var H20=numberToCurrency(H20);
        $("#txt_H20").html(H20);
        var f=(E6*55)/100;
        var H21=f+E6;
        var H21=numberToCurrency(H21);
        $("#txt_H21").html(H21);
        var g=(E6*80)/100;
        var H22=g+E6;
        var H22=numberToCurrency(H22);
        $("#txt_H22").html(H22);
        var J20=$("#txt_J20").val();
        J20=parseFloat(J20);
        var J21=$("#txt_J21").val();
        J21=parseFloat(J21);
        var J22=$("#txt_J22").val();
        J22=parseFloat(J22);
        var J23=$("#txt_J23").val();
        J23=parseFloat(J23);
        var K20=0;
        if(E12=='NO')
        {
            K20=0;
        }
        else
        {
            K20=0.05;
        }
        $("#txt_K20").html(K20.toFixed(2)+'%');
        K20=parseFloat(K20);

        var L20=0;
        if(E14=='NO')
        {
            L20=0;
        }
        else
        {
            L20=0.10;
        }
        $("#txt_L20").html(L20.toFixed(2)+'%');
        L20=parseFloat(L20);

        var N20=result+J20+K20+L20;

        $("#txt_N20").html(N20.toFixed(2)+'%');
        var N21=result+J21+K20+L20;
        $("#txt_N21").html(N21.toFixed(2)+'%');
        var N22=result+J22+K20+L20;
        $("#txt_N22").html(N22.toFixed(2)+'%');
        var N23=result+J23+K20+L20;
        $("#txt_N23").html(N23.toFixed(2)+'%');
        $('#txt_L97').html('');

        if($('#txt_E12').val()=='SI')
        {
            $('#txt_L97').append('<ul class="green_bullet_list no_margin_left"><li>El cliente podrá tener alguno o los dos seguros  contratados antes de la  constitución del CDT o en el mes de la constitución del CDT y el sistema validara que este activo durante la vigencia del titulo. Si lo contrata después ya NO cuenta.</li></ul>');
        }

        if (E8==6 && $('#txt_E14').val() == "SI" )
        {
            if($('#txt_E12').val()=='SI'){
                $('#txt_L97').append('<ul class="green_bullet_list no_margin_left"><li>El abono de nómina debe ser mínimo por 3 meses. No necesariamente consecutivos.</li></ul>');
            }
            else{
                $('#txt_L97').append('<ul class="green_bullet_list no_margin_left"><li>El abono de de nómina debe ser mínimo por 3 meses. No necesariamente consecutivos.</li></ul>');
            }
        }
        if(E8==12 && $('#txt_E14').val() == "SI")
        {
            if($('#txt_E12').val()=='SI'){
                $('#txt_L97').append('<ul class="green_bullet_list no_margin_left"><li>El abono de nómina debe ser mínimo por 6 meses. No necesariamente consecutivos</li></lu>');
            }
            else{
                $('#txt_L97').append('<ul class="green_bullet_list no_margin_left"><li>El abono de nómina debe ser mínimo por 6 meses. No necesariamente consecutivos</li></ul>');
            }
        }
        if($('#txt_E12').val()=='SI'){
            $('#txt_L97').append('</lu>');
        }

		showresults();


        // asignar los valores para enviar
        $("#vtuSim").val(0)
        //se busca monto para asignarle el valor
        $("#monto").val($("#txt_E3").val());

        // tasa
        $("#tasa").val($("#txt_E10").val());

        $("#plazo").val($("#txt_E8").val())

        /*para al tabla */
        var x = $("#labelsimulacion");
        x.val("Cual debe ser la posición final;Saldo medio mínimo de 6 Meses;Saldo medio máximo de;Puntos adicionales por:;Tasa de interés final");

        var t   = $("#table td");
        var res = "";
        for ( i = 0; i < t.length ; i++ )
        {
          res += t[i].innerText + ";"
        }
        $("#valoresimulacion").val(res);
        //
    }

		function volver(){
		$('#resultados').hide();
		document.getElementById("txt_E3").value="";
		document.getElementById("txt_E6").value="";
		document.getElementById("txt_E10").value="";
	}
	function add_field_text(val)
	{
		if (val!="")
			{
				val=val.replace(' %','');
				val = val+" %";
				document.getElementById("txt_E10").value=val;
			}
	}
  function comisionesservicio(val){
    if (val == 'GIRO') {
      // $('#tipogiro').show();
      // $('#valtrm').show();
      $('#tipodivisa').html('Divisa Giro');
      // $('#divisa').html('<option value="COPADIVISA">DE COP a DIVISA</option>');
    }else{
      // $('#tipogiro').hide();
      // $('#valtrm').hide();
      $('#tipodivisa').html('Divisa Reintegro');
      // $('#divisa').html('<option value="DIVISAACOP">DE DIVISA A COP</option>');
    }
    cambiarSelect();
  }
</script>
</head>
<body>
    <div class="simulador_final_heading">
        <h3>Simulador CDT Portafolio</h3>
        <p>

        </p>
    </div>
    <div class="simulador_final_input">
        <!--<h3 class="simulador_final_h3"><!--Ingrese los siguientes datos--Ingrese los siguientes datos:</h3>-->
        <div class="clr"></div>
        <div class="simulador_final_input_inner">
            <table border="3" width="97%" height="30%" align="left" class="tbl subproducto_libre">
                <tr>
                    <td>Tipo de Operación</td>
                     <td><select id="txt_E8" name="E8" class="textbox" onchange="comisionesservicio(this.value)">
                            <option value="GIRO">GIRO</option>
                            <option value="REINTEGRO">REINTEGRO</option>
                        </select>
                    </td>
         
                </tr>
                <tr>
                      <td>Tipo de Consulta</td>
                     <td><select id="divisa" name="divisa" class="textbox" onchange="cambiarSelect();">
                            <option value="COPADIVISA">DE COP a DIVISA</option>
                            <option value="DIVISAACOP">DE DIVISA A COP</option>
                        </select>
                    </td>
                </tr>
                <tr id="divisacop">
                          <td>Divisa Origen</td>
                     <td><select id="divOrigen" name="divOrigen" class="textbox" onchange="cambiardivisa();" >
                            <option value="COP">COP</option>
                        </select>
                    </td>
                </tr>
                <!-- <tr id="copdivisa" style="display:block;">
                          <td>Divisa Origen</td>
                     <td><select id="divOrigen" name="divOrigen" class="textbox" onblur="cambiardivisa2();" >
                            <option value="COP">COP</option>
                            
                        </select>
                    </td>
                </tr> -->
 			    <tr>
			      <td>Monto en <span id="montoen">COP</span></td>
                  <td>
                  <input type="text" id="monto" name="monto" data-a-sign="$ " data-a-dec="," data-a-sep="." placeholder="$" onclick="hidderesults()">
                  </td>
                <tr id="divisadegiro">
                <td><span id="tipodivisa">Divisa Giro</span></td>
                   <td><select id="divgiro" name="divgiro" class="textbox" >
                            <option value="USD">USD</option>
                            <option value="CHF">CHF</option>
                            <option value="CAD">CAD</option>
                            <option value="GBP">GBP</option>
                            <option value="EUR">EUR</option>
                            <option value="JPY">JPY</option>
                            <option value="SEK">SEK</option>
                            <option value="CNY">CNY</option>
                            <option value="MXN">MXN</option>
                        </select></td>
                </tr>
                <tr id="tipogiro">
                   <td>TIPO BANCO </td>
                   <td><select id="tipo" name="tipo" class="textbox" >
                             <option value="GRUPO">GRUPO</option>
                            <option value="NOGRUPO">NO GRUPO</option></select>
                            </td>
                </tr>
                <tr id='valtrm'>
                   <td>TRM </td>
                   <td><input type="text" id="TRM" name="TRM" data-a-sign="$ " data-a-dec="," data-a-sep="." placeholder="$" ></td>
                </tr>

                <tr>
                    <td>
                        <center>
                          <img class="imgsim" src="/wp-content/plugins/simulator-on-pages/images/icono-dinero.png">
                        </center>
                        <div class="btnsimu">
                          <button type="button" onClick="calcularMoneda()">Calcular</button>
                        </div>                        
                    </td>
                </tr>

            </table>
        </div>
    </div>
    <div class="col-md-5" id="textsimu" style="width: 42%; margin-left: 30px;">
      <div class="fondo-azul">
        <div class="titsimu">
          <p>Giros-Reintegros</p>
        </div>
        <p class="tit">Definición</p>
        <p>Esta simulación imita de manera proyectada el cálculo de los costos por comisiones, gastos administrativos y tributarios que generarían un giro al exterior en divisas internacionales, indicando el valor en pesos que asumiría el consumidor financiero.</p>
        <br>
        <p>La simulación no contempla información alusiva a la operación de comercio exterior que la origina y es un dato de referencia sin ser una negociación de divisas en línea.</p>
        <br>        
        </div>
    </div>

    <div class="col-md-7 simulador_final_output simulador_cdt" style="display: none;" id="resultados">
      <div class="row">
        <div class="col-md-11">
          <div class="fondo-azul">
            <div class="titsimu">
              <p>Giros-Reintegros</p>
            </div>
            <div class="col-md-12 sub">
                <p class="tit">CONVERSOR DE DIVISAS</p>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="first_col" id="montoresen">Monto en </div>
                </div>
                <div class="col-md-6">
                  <div id="montofirst" name="montofirst" class="textbox"></div>
                </div>                
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="first_col"><span id="tipodivisa2"></span></div>
                </div>
                <div class="col-md-6">
                  <div id="divresgiro" name="divresgiro" class="textbox"></div>
                </div>
            </div>
            <div class="row" id="trmonto">
              <div class="col-md-6"><div class="first_col" id="textmonto1"></div>
             </div>
              <div class="col-md-6">
                <div id="monusd" name="monusd" class="textbox"></div>
              </div>
            </div>
            <div class="row" id="trmonto2">
              <div class="col-md-6">
                <div class="first_col" id="montogirores">MONTO EN </div>
              </div>
              <div class="col-md-6">
                <div id="montoenres1" name="montoenres1" class="textbox"></div>
              </div>
            </div>
            <div id="mostrarcomisiones">       
              <div class="col-md-12 sub">
                  <p class="tit">COMISIONES DE SERVICIO</p>
              </div>
              <div class="row">
                  <div class="col-md-6">
                    <div class="first_col">TIPO BANCO</div>
                  </div>
                  <div class="col-md-6">
                    <div id="tipobanco" name="tipobanco" class="textbox"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6">
                    <div class="first_col">TRM</div>
                  </div>
                  <div class="col-md-6">
                    <div id="resTRM" name="resTRM" class="textbox"></div>
                  </div>
              </div>
              <div class="row">
                    <div class="col-md-6">
                      <div id="textmsnswift" class="first_col"></div>
                    </div>
                    <div class="col-md-6">
                      <div id="msgSwif" name="msgSwif" class="textbox"></div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-6">
                      <div class="first_col">IVA MENSAJE SWIFT</div>
                    </div>
                    <div class="col-md-6">
                      <div id="ivamsgSwif" name="ivamsgSwif" class="textbox"></div>
                    </div>
              </div>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">COMISIÓN ADMINISTRATIVA</div>
                   </div>
                   <div class="col-md-6">
                    <div id="papel" name="papel" class="textbox"></div>
                   </div>
              </div>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">IVA COMISIÓN ADMINISTRATIVA</div>
                   </div>
                   <div class="col-md-6">
                    <div id="ivapapel" name="ivapapel" class="textbox"></div>
                   </div>
              </div>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">TOTAL COMISIONES</div>
                   </div>
                   <div class="col-md-6">
                    <div id="totcom" name="totcom" class="textbox"></div>
                   </div>
              </div>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">TOTAL IVA</div>
                   </div>
                   <div class="col-md-6">
                    <div id="totaliva" name="totaliva" class="textbox"></div>
                   </div>
              </div>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">TOTAL COMISIÓN + IVA</div>
                   </div>
                   <div class="col-md-6">
                    <div id="totalcomisioniva" name="totalcomisioniva" class="textbox"></div>
                   </div>
              </div>
              <p style="margin: 10px"></p>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">MONTO OPERACIÓN EN COP + TOTAL COMISIÓN + IVA</div>
                   </div>
                   <div class="col-md-6">
                    <div id="copcomiiva" name="copcomiiva" class="textbox"></div>
                   </div>
              </div>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">GMF</div>
                   </div>
                   <div class="col-md-6">
                    <div id="gmf" name="gmf" class="textbox"></div>
                   </div>
              </div>
              <div class="row">
                   <div class="col-md-6">
                    <div class="first_col">COSTO TOTAL OPERACIÓN</div>
                   </div>
                   <div class="col-md-6">
                    <div id="cosgirotot" name="cosgirotot" class="textbox"></div>
                   </div>
              </div>
            </div>
             <div class="col-md-12 sub">
                <p class="tit">Información VTU</p>
            </div>
           <div class="row">
              <div class="col-md-6">
                <div id="labelvtucomision" class="first_col"></div>
              </div>
              <div class="col-md-6">
                <div id="vtucomiswift" name="vtucomiswift" class="textbox"></div>
              </div>
           </div>
<!-- 
           <div class="row">
              <div class="col-md-6">
                <div class="first_col">VTU SWIFT</div>
              </div>
              <div class="col-md-6">
                <div id="vtuswift" name="vtuswift" class="textbox"></div>
              </div>
           </div> -->
           <div class="row">
              <div class="col-md-6">
                <div class="first_col">VTU % </div>
              </div>
                <div class="col-md-6">
                  <div id="vtupor" name="vtupor" class="textbox"></div>
                </div>
           </div>
          </div>
        </div>       
      </div>
  </div>
    <?php include('includes/footer.php') ?>