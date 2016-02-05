/**
 * Adk Portal
 * Version: 3.0
 * Official support: http://www.smfpersonal.net
 * Author: Adk Team
 * Copyright: 2009 - 2014 © SMFPersonal
 * Developers:
 * 		Juarez, Lucas Javier
 * 		Clavijo, Pablo
 *
 * version smf 2.0*
 */

var alturaMaximaDa = 101;
var velocidadDa = 20;
var esperaDa = 120;
var anchuraDa = 100;
var prorrataDa = anchuraDa/alturaMaximaDa;
var cantidadDa = 1;

var alturaMaximaDb = 101;
var velocidadDb = 20;
var esperaDb = 120;
var anchuraDb = 100;
var prorrataDb = anchuraDb/alturaMaximaDb;
var cantidadDb = 1;

var alturaMaximaDc = 101;
var velocidadDc = 20;
var esperaDc = 120;
var anchuraDc = 100;
var prorrataDc = anchuraDc/alturaMaximaDc;
var cantidadDc = 1;

var alturaMaximaDd = 101;
var velocidadDd = 20;
var esperaDd = 120;
var anchuraDd = 100;
var prorrataDd = anchuraDd/alturaMaximaDd;
var cantidadDd = 1;

var alturaMaxima = 101;
var velocidad = 20;
var espera = 120;
var anchura = 100;
var prorrata = anchura/alturaMaxima;
var cantidad = 1;

var alturaMaximaL = 101;
var velocidadL = 20;
var esperaL = 120;
var anchuraL = 100;
var prorrataL = anchuraL/alturaMaximaL;
var cantidadL = 1;

var alturaMaximaC = 101;
var velocidadC = 20;
var esperaC = 120;
var anchuraC = 100;
var prorrataC = anchuraC/alturaMaximaC;
var cantidadC = 1;

var alturaMaximaR = 101;
var velocidadR = 20;
var esperaR = 120;
var anchuraR = 100;
var prorrataR = anchuraR/alturaMaximaR;
var cantidadR = 1;

var alturaMaximaT = 101;
var velocidadT = 20;
var esperaT = 120;
var anchuraT = 100;
var prorrataT = anchuraT/alturaMaximaT;
var cantidadT = 1;

var alturaMaximaB = 101;
var velocidadB = 20;
var esperaB = 120;
var anchuraB = 100;
var prorrataB = anchuraB/alturaMaximaB;
var cantidadB = 1;

function mostrardesignA(esto){
	if(cantidadDa==1 || cantidadDa==alturaMaximaDa){
		incrementoDa = (cantidadDa==1)?velocidadDa:-velocidadDa;
		topeDa = (cantidadDa==1)?alturaMaximaDa:1;
	}	
	cantidadDa+=incrementoDa;
	document.getElementById(esto).style.height=cantidadDa+"%";
	document.getElementById(esto).style.width=parseInt(prorrataDa*cantidadDa)+"%";

	if(cantidadDa!=topeDa){
		setTimeout("mostrardesignA('"+esto+"')",esperaDa);
	}
	if (cantidadDa==topeDa) {
		ocultarDa(esto);
	}
}
function ocultarDa(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById(esto).style.height=1+"px";
	}
}

function mostrardesignB(esto){
	if(cantidadDb==1 || cantidadDb==alturaMaximaDb){
		incrementoDb = (cantidadDb==1)?velocidadDb:-velocidadDb;
		topeDb = (cantidadDb==1)?alturaMaximaDb:1;
	}	
	cantidadDb+=incrementoDb;
	document.getElementById(esto).style.height=cantidadDb+"%";
	document.getElementById(esto).style.width=parseInt(prorrataDb*cantidadDb)+"%";

	if(cantidadDb!=topeDb){
		setTimeout("mostrardesignB('"+esto+"')",esperaDb);
	}
	if (cantidadDb==topeDb) {
		ocultarDb(esto);
	}
}
function ocultarDb(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById(esto).style.height=1+"px";
	}
}

function mostrardesignC(esto){
	if(cantidadDc==1 || cantidadDc==alturaMaximaDc){
		incrementoDc = (cantidadDc==1)?velocidadDc:-velocidadDc;
		topeDc = (cantidadDc==1)?alturaMaximaDc:1;
	}	
	cantidadDc+=incrementoDc;
	document.getElementById(esto).style.height=cantidadDc+"%";
	document.getElementById(esto).style.width=parseInt(prorrataDc*cantidadDc)+"%";

	if(cantidadDc!=topeDc){
		setTimeout("mostrardesignC('"+esto+"')",esperaDc);
	}
	if (cantidadDc==topeDc) {
		ocultarDc(esto);
	}
}
function ocultarDc(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById(esto).style.height=1+"px";
	}
}

function mostrardesignD(esto){
	if(cantidadDd==1 || cantidadDd==alturaMaximaDd){
		incrementoDd = (cantidadDd==1)?velocidadDd:-velocidadDd;
		topeDd = (cantidadDd==1)?alturaMaximaDd:1;
	}	
	cantidadDd+=incrementoDd;
	document.getElementById(esto).style.height=cantidadDd+"%";
	document.getElementById(esto).style.width=parseInt(prorrataDd*cantidadDd)+"%";

	if(cantidadDd!=topeDd){
		setTimeout("mostrardesignD('"+esto+"')",esperaDd);
	}
	if (cantidadDd==topeDd) {
		ocultarDd(esto);
	}
}
function ocultarDd(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById(esto).style.height=1+"px";
	}
}

function mostrar(esto){
	if(cantidad==1 || cantidad==alturaMaxima){
		incremento = (cantidad==1)?velocidad:-velocidad;
		tope = (cantidad==1)?alturaMaxima:1;
	}	
	cantidad+=incremento;
	document.getElementById(esto).style.height=cantidad+"%";
	document.getElementById(esto).style.width=parseInt(prorrata*cantidad)+"%";

	if(cantidad!=tope){
		setTimeout("mostrar('"+esto+"')",espera);
	}
	if (cantidad==tope) {
		ocultar(esto);
	}
}
function ocultar(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById(esto).style.height=1+"px";
	}
}
function mostrarleft(esto){
	if(cantidadL==1 || cantidadL==alturaMaximaL){
		incrementoleft = (cantidadL==1)?velocidadL:-velocidadL;
		topeL = (cantidadL==1)?alturaMaximaL:1;
	}	
	cantidadL+=incrementoleft;
	document.getElementById(esto).style.height=cantidadL+"%";
	document.getElementById(esto).style.width=parseInt(prorrataL*cantidadL)+"%";
	document.getElementById('left_cols_expan').src = smf_adk_url + "images/colapse.gif";
	if(cantidadL!=topeL){
		setTimeout("mostrarleft('"+esto+"')",esperaL);
	}
	if (cantidadL==topeL) {
		ocultarleft(esto);
	}
}
function ocultarleft(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById('left_cols_expan').src = smf_adk_url + "images/expand.gif";
		document.getElementById(esto).style.height=1+"px";
	}
}
function mostrarcenter(esto){
	if(cantidadC==1 || cantidadC==alturaMaximaC){
		incrementocenter = (cantidadC==1)?velocidadC:-velocidadC;
		topeC = (cantidadC==1)?alturaMaximaC:1;
	}	
	cantidadC+=incrementocenter;
	document.getElementById(esto).style.height=cantidadC+"%";
	document.getElementById(esto).style.width=parseInt(prorrataC*cantidadC)+"%";
	document.getElementById('center_cols_expan').src = smf_adk_url + "images/colapse.gif";
	if(cantidadC!=topeC){
		setTimeout("mostrarcenter('"+esto+"')",esperaC);
	}
	if (cantidadC==topeC) {
		ocultarcenter(esto);
	}
}
function ocultarcenter(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById('center_cols_expan').src = smf_adk_url + "images/expand.gif";
		document.getElementById(esto).style.height=1+"px";
	}
}
function mostrarright(esto){
	if(cantidadR==1 || cantidadR==alturaMaximaR){
		incrementoright = (cantidadR==1)?velocidadR:-velocidadR;
		topeR = (cantidadR==1)?alturaMaximaR:1;
	}	
	cantidadR+=incrementoright;
	document.getElementById(esto).style.height=cantidadR+"%";
	document.getElementById(esto).style.width=parseInt(prorrataR*cantidadR)+"%";
	document.getElementById('right_cols_expan').src = smf_adk_url + "images/colapse.gif";
	if(cantidadR!=topeR){
		setTimeout("mostrarright('"+esto+"')",esperaR);
	}
	if (cantidadR==topeR) {
		ocultarright(esto);
	}
}
function ocultarright(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById('right_cols_expan').src = smf_adk_url + "images/expand.gif";
		document.getElementById(esto).style.height=1+"px";
	}
}
function mostrartop(esto){
	if(cantidadT==1 || cantidadT==alturaMaximaT){
		incrementotop = (cantidadT==1)?velocidadT:-velocidadT;
		topeT = (cantidadT==1)?alturaMaximaT:1;
	}	
	cantidadT+=incrementotop;
	document.getElementById(esto).style.height=cantidadT+"%";
	document.getElementById(esto).style.width=parseInt(prorrataT*cantidadT)+"%";
	document.getElementById('top_cols_expan').src = smf_adk_url + "images/colapse.gif";
	if(cantidadT!=topeT){
		setTimeout("mostrartop('"+esto+"')",esperaT);
	}
	if (cantidadT==topeT) {
		ocultartop(esto);
	}
}
function ocultartop(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById('top_cols_expan').src = smf_adk_url + "images/expand.gif";
		document.getElementById(esto).style.height=1+"px";
	}
}
function mostrarbottom(esto){
	if(cantidadB==1 || cantidadB==alturaMaximaB){
		incrementobottom = (cantidadB==1)?velocidadB:-velocidadB;
		topeB = (cantidadB==1)?alturaMaximaB:1;
	}	
	cantidadB+=incrementobottom;
	document.getElementById(esto).style.height=cantidadB+"%";
	document.getElementById(esto).style.width=parseInt(prorrataB*cantidadB)+"%";
	document.getElementById('bottom_cols_expan').src = smf_adk_url + "images/colapse.gif";
	if(cantidadB!=topeB){
		setTimeout("mostrarbottom('"+esto+"')",esperaB);
	}
	if (cantidadB==topeB) {
		ocultarbottom(esto);
	}
}
function ocultarbottom(esto){
	if ((document.getElementById(esto).style.height==1+"%") && (document.getElementById(esto).style.width==0+"%")){
		document.getElementById('bottom_cols_expan').src =  smf_adk_url + "images/expand.gif";
		document.getElementById(esto).style.height=1+"px";
	}
}
