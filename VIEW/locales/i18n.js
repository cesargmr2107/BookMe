var translations;

function setLang(lang = null) {

    // Get cookie and select lang; if already translated return
    var cookie = getCookie('lang');
    if(lang === null){
        lang = (cookie == '') ? 'ES' : cookie;
    }else if(lang === cookie){
        return;
    }

    // Reset lang cookie
    setCookie('lang', lang, 1);

    // Select translation lang
    switch (lang) {
        case 'EN': translations = translations_en;
            break;

        case 'GL': translations = translations_gl
            break;
    
        default: translations = translations_es;
            break;
    }

    // Translate elements
    translate(translations);

    // Reload calendar if necessary
    if(typeof calendar !== 'undefined'){
        var locale = lang.toLowerCase();
        calendar.setOption('locale', locale);
    }

    // Reload graph if necessary
    if(typeof statsChart !== 'undefined'){
        statsChart.data.labels[0] = translations["i18n-availableHours"];
        statsChart.data.labels[1] = translations["i18n-unavailableHours"];
        statsChart.update();
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