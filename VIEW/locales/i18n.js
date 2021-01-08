function setLang(lang = null) {

    // Check lang
    if(lang == null){
        
        // Set lang from cookie if possible; if not ES by default
        if (getCookie('lang') != '') {
          lang = getCookie('lang');
        }else{
            lang='ES';
        }
    
    }

    // Reset lang cookie
    setCookie('lang', lang, 1);

    // Translate to lang
    switch (lang) {
        case 'EN': translate(translations_en);
            break;

        case 'GA':
            break;
    
        default: translate(translations_es);
            break;
    }
}

function translate(translations){
    
    // Iterate over keys
    for(var key in translations) {
        
        // Get by class
        var elements = document.getElementsByClassName(key);

        // Get inputs: placeholders need to be translated
        var inputs = document.getElementsByTagName('input');

        for (var elem in elements) {
            // Se recorre el nuevo array y se colocan en el DOM los textos
            elements[elem].innerHTML = translations[key];
        }
  
        // Iterate over inputs and if necessary translate placeholder
        for(var i = 0; i < inputs.length; i++){
        
            if(inputs[i].placeholder == key){
                inputs[i].placeholder = translations[key];
            }
        }
    }
}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

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