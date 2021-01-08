//Funciones de JS para cambiar y establecer el idioma de los textos 

var traduccion;

//si no se env´ia idioma el idioma por defecto es ES
function setLang(lang =''){

//comprobamos si recibimos un idioma por parámetro, si no lo recibimos le damos el valor a lang
if(lang==''){
	//Si hay cookie de idioma se le establece ese idioma
    if (getCookie('lang') != '') {
      lang = getCookie('lang');
    }else{ 	
    //si no hay idioma se establece español por defecto
    	lang='ES';
    }

  }
//establecemos cookie de idioma o la cambiamos segun el idioma nuevo
setCookie('lang',lang,1);

//cargamos el array de idiomas segun el idioma seleccionado
switch(lang){
	case 'ES' : 
	traduccion=arrayES;
	break;
	case 'EN' :
	traduccion=arrayEN;
	break;
	case 'GA' :
	traduccion=arrayGA;
	break;
	default:
	traduccion=arrayES;
	break;
}


   // se recorre el array de traducciones buscando coincidencias una por una
   for(var clave in traduccion) {
		//si un elemento del array de traducciones está en el DOM se guarda en el array elementos
  		//es un array ya que al ser clase puede haber varias coincidencias
 		var elementos = document.getElementsByClassName(clave);
    //recogemos todas las etiquetas y las guardamos en un array
    var etiquetas =document.getElementsByTagName('LABEL');
    //y guardamos tambien todos los inputs para cambiarles los placeholders
    var inputs = document.getElementsByTagName('input');

      for (var elem in elementos) {
      //Se recorre el nuevo array y se colocan en el DOM los textos
        elementos[elem].innerHTML = traduccion[clave];
      }

      for(var i=0;i<etiquetas.length;i++){
        //recorremos todas las etiquetas, si el atributo For de la etiqueta coincide con la clave,
        // se sustitulle el texto de la etiqueta por el texto traducido
        if(etiquetas[i].htmlFor==clave){
         etiquetas[i].innerHTML=traduccion[clave];
        }
      }
      for(var i=0;i<inputs.length;i++){
        //Recorremos todos los inputs, si el atributo placeholder coincide con la clave,
        // se sustituye el texto del placeholder por el del texto traducido.
        if(inputs[i].placeholder==clave){
         inputs[i].placeholder=traduccion[clave];
        }
      }
	}
}

//funcion para establecer el valor de la cookie
function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

//funcion para establecer el valor de la cookie
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function cambiarLang(lang){
    setCookie('lang',lang,5);
   window.location.reload(true);
}