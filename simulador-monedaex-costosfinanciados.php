<?php include('includes/header.php') ?>
<style> 
table#table {
    max-width: 100% !important;
    display: inline-table !important;
}

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

$(window).bind("load",function(){
	var textbox_G6=$("#txt_E3").autoNumeric('init');
	var textbox_G6=$("#txt_E6").autoNumeric('init');
    var monto_opera=$("#monto_opera").autoNumeric('init');
    var trm_dia=$("#trm_dia").autoNumeric('init');
});

$(document).ready(function() {
    hidderesults();
    document.getElementById( 'txt_E10' ).disabled = 'true';
 });

 function hidderesults()
 {
     document.getElementById('resultados').style.display = "none";
 }

 function showresults()
 {
     document.getElementById('resultados').style.display="block";
 }

 $(function(){
     $('#txt_E3').autoNumeric('init');
     $('#txt_E6').autoNumeric('init');
 });

function formatNumber(num)
{
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}



/*
*calcular  moneda extranjera
*/
    function calcularMoneda()
    {
        var monto_opera = $("#monto_opera").autoNumeric('get');
        var trm_dia     = $("#trm_dia").autoNumeric('get');
        var tipo        = $("#txt_E8").val();
        var tra_ex      = $("#tra_ex").val();
        var openCop     = 0 ;
        var iva         = 0.19;
        var papel       = 5300;
        var ivaCom      = 0;
        var porCom      = 0;
        var valCom      = 0;
        var costot      = 0;
        var  iva1       = 0;
        // no grupo y grupo van en USD
        var NOGRUPO     = 25;
        var GRUPO       = 10;
        var ivagrupo    = 0;
        var ivanogrupo  = 0;
        var subtotgrupo = 0;
        var nuevocostot = 0;
        var gmfgrupo    = 0;
        var vtucom      = 0;
        var vtuiva      = 0;
        var vtuswift    = 0;
        var vtupor      = 0;


        if( monto_opera <= 0 || isNaN(monto_opera) )
        {
            alert("Por favor ingrese el monto");
            return;
        }

        if( trm_dia <= 0 || isNaN(trm_dia) )
        {
            alert("Por favor ingrese la TRM");
            return;
        }
        
        if (tipo == "PREFINANCIACIONEX")
        {
        	openCop      = monto_opera * trm_dia;
            iva1         = papel * iva;
            porCom       = 0.0015;
            valCom       = openCop * porCom;
            ivaCom       = valCom * iva;
            // valor comision operacion en cop * porcentaje comision para bancoldex cero
            // iva comision, valor comision por el iva
            console.log("iva comision:"+ivaCom);
            $("#ivaCom").html("$ " +formatNumber(ivaCom.toFixed(2))); 
            // valor comisiones, Solo para bancoldex
            // papeleria  + iva
            $("#valorComi").html("$ " + formatNumber( (ivaCom + papel + iva1 + valCom).toFixed(2) ) );
            // gmf = valor comision*4/1000
            $("#gmf").html("$ " +formatNumber( ( ( ( ivaCom + papel + iva1 + valCom) * 4 ) / 1000 ).toFixed(2) ) );
            $("#valCom").html("$ " +formatNumber(valCom.toFixed(2)));
            $("#porComision").html((porCom*100)+" %");
            $("#iva1").html("$ " +formatNumber(iva1));
            $("#montoop").html("$ " +formatNumber(monto_opera));
            $("#trmdia").html("$ " +formatNumber(trm_dia));
            $("#valorCop").html("$ " +formatNumber(openCop));
            //costo total de la operacion valor com + gmf
            costot = (ivaCom + papel + iva1 + valCom) + ( ( ( ivaCom + papel + iva1 + valCom) * 4 ) / 1000 );
            $("#costot").html("$ " + formatNumber(costot.toFixed(2)) );
            $("#papel").html("$ " + formatNumber(papel));
            $("#tipoban").html(tra_ex);
            
            if( tra_ex ==  "NO GRUPO")
            { 
                // trm del dia  *  no grupo
                $("#msgSwif").html("$ " + formatNumber( (NOGRUPO * trm_dia) ));
                //iva
                ivanogrupo  = iva * (NOGRUPO * trm_dia);
                // subtotal = iva  + swift
                subtotgrupo = ivanogrupo  + (NOGRUPO * trm_dia);
                $("#cosiva").html("$ " +formatNumber(ivanogrupo));
                $("#costotal").html("$ " +formatNumber(subtotgrupo));
                // subtotal +gmf
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
                //   subtotal grupo + gmfgrupo + costo total operacion
                nuevocostot = subtotgrupo + gmfgrupo + costot;
             
                vtucom      = papel + valCom;
                // iva del cero por bancoldex
                vtuiva      = iva1 + ivanogrupo + ivaCom;
                vtuswift    = ( NOGRUPO * trm_dia );
                vtupor      = ( vtucom / openCop ) + ( vtuiva / openCop ) + ( vtuswift / openCop );
                vtupor      = vtupor * 100;
              

                $("#gmfmoneda").html("$ " +formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " +formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " +formatNumber(vtucom.toFixed(2)));
                $("#vtuiva").html("$ " +formatNumber(vtuiva.toFixed(2)));
                $("#vtuswift").html("$ " +formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");


            }else if( tra_ex ==  "GRUPO"){
            	ivagrupo    = iva * ( GRUPO * trm_dia );
            	subtotgrupo = ivagrupo  + ( GRUPO * trm_dia );
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
            	nuevocostot = subtotgrupo + gmfgrupo + costot;
            	vtucom      = papel + valCom;
            	vtuiva      = iva1  + ivagrupo + ivaCom;
            	vtuswift    = ( GRUPO * trm_dia );
            	vtupor      = ( vtucom / openCop) + ( vtuiva / openCop ) + ( vtuswift / openCop );
            	vtupor      = vtupor * 100;

                $("#msgSwif").html("$ " + formatNumber( ( GRUPO * trm_dia ) ));
                $("#cosiva").html("$ " +formatNumber( ivagrupo ));
                $("#costotal").html("$ " +formatNumber(subtotgrupo));
                $("#gmfmoneda").html("$ " +formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " +formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " +formatNumber(vtucom.toFixed(2)));
                $("#vtuiva").html("$ " +formatNumber(vtuiva.toFixed(2)));
                $("#vtuswift").html("$ " +formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");
            }

        }


        if (tipo == "GIRODFINANCIADO")
        {
        	openCop      = monto_opera * trm_dia;
            iva1         = papel * iva;
            porCom       = 0.0025;
            valCom       = openCop * porCom;
            ivaCom       = valCom * iva;
            // valor comision operacion en cop * porcentaje comision para bancoldex cero
            // iva comision, valor comision por el iva
            $("#ivaCom").html("$ " +formatNumber(ivaCom.toFixed(2)));
            // valor comisiones, Solo para bancoldex
            // papeleria  + iva
            $("#valorComi").html("$ " + formatNumber( (ivaCom + papel + iva1 + valCom).toFixed(2) ) );
            // gmf = valor comision*4/1000
            $("#gmf").html("$ " +formatNumber( ( ( ( ivaCom + papel + iva1 + valCom) * 4 ) / 1000 ).toFixed(2) ) );
            $("#valCom").html("$ " +formatNumber(valCom.toFixed(2)));
            $("#porComision").html((porCom*100)+" %");
            $("#iva1").html("$ " +formatNumber(iva1));
            $("#montoop").html("$ " +formatNumber(monto_opera));
            $("#trmdia").html("$ " +formatNumber(trm_dia));
            $("#valorCop").html("$ " +formatNumber(openCop));
            //costo total de la operacion valor com + gmf
            costot = (ivaCom + papel + iva1 + valCom) + ( ( ( ivaCom + papel + iva1 + valCom) * 4 ) / 1000 );
            $("#costot").html("$ " + formatNumber(costot.toFixed(2)) );
            $("#papel").html("$ " + formatNumber(papel));
            $("#tipoban").html(tra_ex);
            
            if( tra_ex ==  "NO GRUPO")
            { 
                // trm del dia  *  no grupo
                $("#msgSwif").html("$ " + formatNumber( (NOGRUPO * trm_dia) ));
                //iva
                ivanogrupo  = iva * (NOGRUPO * trm_dia);
                // subtotal = iva  + swift
                subtotgrupo = ivanogrupo  + (NOGRUPO * trm_dia);
                $("#cosiva").html("$ " +formatNumber(ivanogrupo));
                $("#costotal").html("$ " +formatNumber(subtotgrupo));
                // subtotal +gmf
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
                //   subtotal grupo + gmfgrupo + costo total operacion
                nuevocostot = subtotgrupo + gmfgrupo + costot;
             
                vtucom      = papel + valCom;
                // iva del cero por bancoldex
                vtuiva      = iva1 + ivanogrupo + ivaCom;
                vtuswift    = ( NOGRUPO * trm_dia );
                vtupor      = ( vtucom / openCop ) + ( vtuiva / openCop ) + ( vtuswift / openCop );
                vtupor      = vtupor * 100;
              

                $("#gmfmoneda").html("$ " +formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " +formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " +formatNumber(vtucom.toFixed(2)));
                $("#vtuiva").html("$ " +formatNumber(vtuiva.toFixed(2)));
                $("#vtuswift").html("$ " +formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");



            }else if( tra_ex ==  "GRUPO"){
            	ivagrupo    = iva * ( GRUPO * trm_dia );
            	subtotgrupo = ivagrupo  + ( GRUPO * trm_dia );
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
            	nuevocostot = subtotgrupo + gmfgrupo + costot;
            	vtucom      = papel + valCom;
            	vtuiva      = iva1  + ivagrupo + ivaCom;
            	vtuswift    = ( GRUPO * trm_dia );
            	vtupor      = ( vtucom / openCop) + ( vtuiva / openCop ) + ( vtuswift / openCop );
            	vtupor      = vtupor * 100;

                $("#msgSwif").html("$ " + formatNumber( ( GRUPO * trm_dia ) ));
                $("#cosiva").html("$ " +formatNumber( ivagrupo ));
                $("#costotal").html("$ " +formatNumber(subtotgrupo));
                $("#gmfmoneda").html("$ " +formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " +formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " +formatNumber(vtucom.toFixed(2)));
                $("#vtuiva").html("$ " +formatNumber(vtuiva.toFixed(2)));
                $("#vtuswift").html("$ " +formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");
            }

        }


        if (tipo == "CAPITALTRABAJO")
        {
        	openCop      = monto_opera * trm_dia;
            iva1         = papel * iva;
            porCom       = 0.005;
            valCom       = openCop * porCom;
            ivaCom       = valCom * iva;
            // valor comision operacion en cop * porcentaje comision para bancoldex cero
            // iva comision, valor comision por el iva
            $("#ivaCom").html("$ " + formatNumber(ivaCom.toFixed(2)));
            // valor comisiones, Solo para bancoldex
            // papeleria  + iva
            $("#valorComi").html("$ " + formatNumber( (ivaCom + papel + iva1 + valCom).toFixed(2) ) );
            // gmf = valor comision*4/1000
            $("#gmf").html("$ " + formatNumber( ( ( ( ivaCom + papel + iva1 + valCom) * 4 ) / 1000 ).toFixed(2) ) );
            $("#valCom").html("$ " + formatNumber(valCom.toFixed(2)));
            $("#porComision").html((porCom*100)+" %");
            $("#iva1").html("$ " + formatNumber(iva1));
            $("#montoop").html("$ " + formatNumber(monto_opera));
            $("#trmdia").html("$ " + formatNumber(trm_dia));
            $("#valorCop").html("$ " + formatNumber(openCop));
            //costo total de la operacion valor com + gmf
            costot = (ivaCom + papel + iva1 + valCom) + ( ( ( ivaCom + papel + iva1 + valCom) * 4 ) / 1000 );
            $("#costot").html("$ " +  formatNumber(costot.toFixed(2)) );
            $("#tipoban").html(tra_ex);
            $("#papel").html("$ " + formatNumber(papel));
            
            if( tra_ex ==  "NO GRUPO")
            { 
                // trm del dia  *  no grupo
                $("#msgSwif").html("$ " + formatNumber( (NOGRUPO * trm_dia) ));
                //iva
                ivanogrupo  = iva * (NOGRUPO * trm_dia);
                // subtotal = iva  + swift
                subtotgrupo = ivanogrupo  + (NOGRUPO * trm_dia);
                $("#cosiva").html("$ " + formatNumber(ivanogrupo));
                $("#costotal").html("$ " + formatNumber(subtotgrupo));
                // subtotal +gmf
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
                //   subtotal grupo + gmfgrupo + costo total operacion
                nuevocostot = subtotgrupo + gmfgrupo + costot;
             
                vtucom      = papel + valCom;
                // iva del cero por bancoldex
                vtuiva      = iva1 + ivanogrupo + ivaCom;
                vtuswift    = ( NOGRUPO * trm_dia );
                vtupor      = ( vtucom / openCop ) + ( vtuiva / openCop ) + ( vtuswift / openCop );
                vtupor      = vtupor * 100;
              

                $("#gmfmoneda").html("$ " + formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " + formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " + formatNumber(vtucom));
                $("#vtuiva").html("$ " + formatNumber(vtuiva));
                $("#vtuswift").html("$ " + formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");


            }else if( tra_ex ==  "GRUPO"){
            	ivagrupo    = iva * ( GRUPO * trm_dia );
            	subtotgrupo = ivagrupo  + ( GRUPO * trm_dia );
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
            	nuevocostot = subtotgrupo + gmfgrupo + costot;
            	vtucom      = papel + valCom;
            	vtuiva      = iva1  + ivagrupo + ivaCom;
            	vtuswift    = ( GRUPO * trm_dia );
            	vtupor      = ( vtucom / openCop) + ( vtuiva / openCop ) + ( vtuswift / openCop );
            	vtupor      = vtupor * 100;

                $("#msgSwif").html("$ " +  formatNumber( ( GRUPO * trm_dia ) ));
                $("#cosiva").html("$ " + formatNumber( ivagrupo ));
                $("#costotal").html("$ " + formatNumber(subtotgrupo));
                $("#gmfmoneda").html("$ " + formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " + formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " + formatNumber(vtucom));
                $("#vtuiva").html("$ " + formatNumber(vtuiva));
                $("#vtuswift").html("$ " + formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");
            }

        }

        if( tipo == "BANCOLDEX" )
        {
            openCop      = monto_opera * trm_dia;
            ivaCom       = iva * papel;
            porCom       = 0;

            
            // valor comision operacion en cop * porcentaje comision para bancoldex cero
            // iva comision, valor comision por el iva
            $("#ivaCom").html("$ " + formatNumber(0));
            // valor comisiones, Solo para bancoldex
            // papeleria  + iva
            $("#valorComi").html("$ " + formatNumber( (ivaCom + papel) ) );
            // gmf = valor comision*4/1000
            $("#gmf").html("$ " +  formatNumber(( ( ( ivaCom + papel) * 4 ) / 1000 ).toFixed(2) ) );
            $("#valCom").html("$ " + formatNumber(valCom));
            $("#porComision").html( porCom + "%" );
            $("#iva1").html("$ " + formatNumber(ivaCom));
            $("#montoop").html("$ " + formatNumber(monto_opera));
            $("#trmdia").html("$ " + formatNumber(trm_dia));
            $("#valorCop").html("$ " + formatNumber(openCop));
            //costo total de la operacion valor com + gmf
            costot = (ivaCom + papel) + ( ( ( ivaCom + papel) * 4 ) / 1000 );
            $("#costot").html("$ " +  formatNumber(costot.toFixed(2)) );
            $("#papel").html("$ " + formatNumber(papel));
            $("#tipoban").html(tra_ex);
            
            if( tra_ex ==  "NO GRUPO")
            { 
                // trm del dia  *  no grupo
                $("#msgSwif").html("$ " +  formatNumber( (NOGRUPO * trm_dia) ));
                //iva
                ivanogrupo  = iva * (NOGRUPO * trm_dia);
                // subtotal = iva  + swift
                subtotgrupo = ivanogrupo  + (NOGRUPO * trm_dia);
                $("#cosiva").html("$ " + formatNumber(ivanogrupo));
                $("#costotal").html("$ " + formatNumber(subtotgrupo));
                // subtotal +gmf
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
                //   subtotal grupo + gmfgrupo + costo total operacion
                nuevocostot = subtotgrupo + gmfgrupo + costot;
                // vtucomisiones para bancoldex solamente
                vtucom      = papel;
                // iva del cero por bancoldex
                vtuiva      = 0  + ivanogrupo + ivaCom;
                vtuswift    = ( NOGRUPO * trm_dia );
                vtupor      = ( vtucom / openCop ) + ( vtuiva / openCop ) + ( vtuswift / openCop );
                vtupor      = vtupor * 100;
              

                $("#gmfmoneda").html("$ " + formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " + formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " + formatNumber(vtucom));
                $("#vtuiva").html("$ " + formatNumber(vtuiva));
                $("#vtuswift").html("$ " + formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");


            }else if( tra_ex ==  "GRUPO"){
            	ivagrupo    = iva * ( GRUPO * trm_dia );
            	subtotgrupo = ivagrupo  + ( GRUPO * trm_dia );
                gmfgrupo    = ( subtotgrupo * 4 ) / 1000;
            	nuevocostot = subtotgrupo + gmfgrupo + costot;
            	vtucom      = papel;
            	vtuiva      = 0  + ivagrupo + ivaCom;
            	vtuswift    = ( GRUPO * trm_dia );
            	vtupor      = ( vtucom / openCop) + ( vtuiva / openCop ) + ( vtuswift / openCop );
            	vtupor      = vtupor * 100;

                $("#msgSwif").html( "$ " + formatNumber( ( GRUPO * trm_dia ) ));
                $("#cosiva").html("$ " + formatNumber( ivagrupo ));
                $("#costotal").html("$ " + formatNumber(subtotgrupo));
                $("#gmfmoneda").html("$ " + formatNumber(gmfgrupo.toFixed(2)));
                $("#cosopera").html("$ " + formatNumber(nuevocostot.toFixed(2)));
                $("#vtucom").html("$ " + formatNumber(vtucom));
                $("#vtuiva").html("$ " + formatNumber(vtuiva));
                $("#vtuswift").html("$ " + formatNumber(vtuswift));
                $("#vtupor").html(vtupor.toFixed(2)+"%");
            }
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
</script>
</head>
<body>
    <div class="simulador_final_heading">
        <h3>Simulador CDT Portafolio</h3>
        <p>

        </p>
    </div>
    <div class="simulador_final_input col-md-5">
        <!--<h3 class="simulador_final_h3"><!--Ingrese los siguientes datos--</h3>-->
        <div class="clr"></div>
        <div class="simulador_final_input_inner 23">  
            <table border="3" width="97%" height="30%" align="left" class="tbl subproducto_libre">
                <tr>
                    <td>Tipo de Operacion</td>
                     <td><select id="txt_E8" name="E8" class="textbox" >
                            <option value="BANCOLDEX">BANCOLDEX</option>
                            <option value="CAPITALTRABAJO">CAPITAL DE TRABAJO</option>
                            <option value="GIRODFINANCIADO">GIRO DIRECTO FINANCIADO</option>
                            <option value="PREFINANCIACIONEX">PREFINANCIACION DE EXPORTACIONES</option>
                        </select>
                    </td>
         
                </tr>
                <tr>
                    <td>Monto de la operacion en USD</td>
                    <td><input type="text" id="monto_opera" name="monto_opera" data-a-sign="$ " data-a-dec="," data-a-sep="." placeholder="$" OnClick = "hidderesults()"/></td>
                </tr>
                <tr>
                    <td>TRM DIA</td>
                    <td><input type="text" id="trm_dia" name="trm_dia" data-a-sign="$ " data-a-dec="," data-a-sep="." placeholder="$" OnClick = "hidderesults()"/></td>
                    </td>
                </tr>
 			    <tr>
					<td>Para Transferencia al Exterior</td>
					 <td><select id="tra_ex" name="E8" class="textbox" >
                     <option value="NO GRUPO">NO GRUPO</option>
                     <option value="GRUPO">GRUPO</option>
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
          <p>Costos P Financiados</p>
        </div>
        <p class="tit">Definición</p>
        <p>En esta funcionalidad se observa el comportamiento simulado que surte del valor de una transferencia en una moneda igual o diferente al dólar convertida a pesos colombianos.</p>
        <br>        
        <p>Esta proyección se limita a efectuar la conversión de divisas y el cálculo correspondiente a pesos, independientemente de la operación de comercio exterior que la origine, lo cual no se contempla en el suministro de esta proyección.</p>
        </div>
    </div>
     <div class="col-md-7 simulador_final_output simulador_cdt" style="display: none;" id="resultados">
      <div class="row">
        <div class="col-md-11">
          <div class="fondo-azul">
            <div class="titsimu">
              <p>Costos P Financiados</p>
            </div>
           
                    <div class="row">
                    	<div class="col-md-12 sub">
                    		<p class="tit">Cartera en Moneda Extranjera</p>
                    	</div>
                    	<div class="row">
	                        <div class="col-md-6">
	                        	<div class="first_col">Monto de la Operacion en USD</div>
	                        </div>
	                        <div class="col-md-6">
	                        	<div id="montoop" name="F20" class="textbox"></div>
	                        </div>	                        
	                    </div>
	                    <div class="row">
	                        <div class="col-md-6">
	                        	<div class="first_col">TRM DIA</div>
	                        </div>
	                        <div class="col-md-6">
	                        	<div id="trmdia" name="F21" class="textbox"></div>
	                        </div>
	                    </div>
	                    <div class="row">
		                    <div class="col-md-6">
		                    	<div class="first_col">VALOR OPERACIÓN EN COP</div>
		                    </div>
		                    <div class="col-md-6">
		                    	<div id="valorCop" name="valorCop" class="textbox"></div>
		                    </div>
	                    </div>
	                    <div class="row">
		                      <div class="col-md-6">
		                      	<div class="first_col">% COMISIÓN</div>
		                      </div>
		                      <div class="col-md-6">
		                      	<div id="porComision" name="porComision" class="textbox"></div>
		                      </div>
	                    </div>
	                    <div class="row">
		                    <div class="col-md-6">
		                    	<div class="first_col">VALOR COMISIÓN</div>
		                    </div>
		                    <div class="col-md-6">
		                    	<div id="valCom" name="valCom" class="textbox"></div>
		                    </div>
	                    </div>
	                    <div class="row">
		                    <div class="col-md-6">
		                    	<div class="first_col">IVA COMISIÓN ADMINISTRATIVA</div>
		                    </div>
		                    <div class="col-md-6">
		                    	<div id="ivaCom" name="ivaCom" class="textbox"></div>
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
	                      	<div class="first_col">IVA</div>
	                      </div>
	                      <div class="col-md-6">
	                      	<div id="iva1" name="iva1" class="textbox"></div>
	                      </div>
	                    </div>
	                    <div class="row">
	                      <div class="col-md-6">
	                      	<div class="first_col">VALOR TOTAL + IVA%</div>
	                      </div>
	                      <div class="col-md-6">
	                      	<div id="valorComi" name="valorComi" class="textbox"></div>
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
	                      	<div id="costot" name="costot" class="textbox"></div>
	                      </div>
	                    </div>
	                    <div class="col-md-12 sub">
                    		<p class="tit">PARA TRANSFERENCIA AL EXTERIOR</p>
                    	</div>
                    	 <div class="row">
			                <div class="col-md-6">
			                	<div class="first_col">TIPO BANCO</div>
			                </div>
			                <div class="col-md-6">
			                	<div id="tipoban" name="tipoban" class="textbox"></div>
			                </div>
			             </div>
		               <div class="row">
		                 <div class="col-md-6">
		                 	<div class="first_col">MENSAJE SWIFT</div>
		                 </div>
		                 <div class="col-md-6">
		                 	<div id="msgSwif" name="msgSwif" class="textbox"></div>
		                 </div>
		               </div>
               			<div class="row">
                      <div class="col-md-6">
                      	<div class="first_col">IVA</div>
                      </div>
                      <div class="col-md-6">
                      	<div id="cosiva" name="cosiva" class="textbox"></div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-md-6">
                      	<div class="first_col">SUB total</div>
                      </div>
                      <div class="col-md-6">
                      	<div id="costotal" name="costotal" class="textbox"></div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        	<div class="first_col">GMF</div>
                        </div>
                         <div class="col-md-6">
                         	<div id="gmfmoneda" name="gmfmoneda" class="textbox"></div>
                         </div>
                    </div>
                    <div class="row">
                     <div class="col-md-6">
                     	<div class="first_col">NUEVO COSTO TOTAL OPERACIÓN</div>
                     </div>
                         <div class="col-md-6">
                         	<div id="cosopera" name="cosopera" class="textbox"></div>
                         </div>
                    </div>
                       <div class="col-md-12 sub">
                    		<p class="tit">INFORMACION VTU</p>
                    	</div>
                    	  <div class="row">
                   <div class="col-md-6">
                   	<div class="first_col">VTU Comisiones</div>
                   </div>
                    <div class="col-md-6">
                    	<div id="vtucom" name="vtucom" class="textbox"></div>
                    </div>
               </div>
               <div class="row">
                               <div class="col-md-6">
                               	<div class="first_col">VTU iva</div>
                               </div>
                    <div class="col-md-6">
                    	<div id="vtuiva" name="vtuiva" class="textbox"></div>
                    </div>
               </div>
               <div class="row">
                               <div class="col-md-6">
                               	<div class="first_col">VTU SWIFT</div>
                               </div>
                    <div class="col-md-6">
                    	<div id="vtuswift" name="vtuswift" class="textbox"></div>
                    </div>
               </div>
               <div class="row">
                    <div class="col-md-6">
                    	<div class="first_col">VTU %</div>
                    </div>
                    <div class="col-md-6">
                    	<div id="vtupor" name="vtupor" class="textbox"></div>
                    </div>
               </div>
          
            </div>            
          </div>
        </div>       
      </div>
  </div>
    <?php include('includes/footer.php')?>