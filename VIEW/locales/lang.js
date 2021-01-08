var translations;

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

    // Load translations
    switch (lang) {
        case 'EN': translations = translations_en;
            break;

        case 'GA':
            break;
    
        default: translations = translations_es;
            break;
    }

    // Get elements to translate
    translate(
        document.getElementsByClassName("i18n")
    );

}

function translate(toTranslate){
    console.log("Call:")
    console.log(toTranslate);
    for(var i = 0; i < toTranslate.length; i++) {
        if(toTranslate[i].hasChildNodes()){
            translate(toTranslate[i].childNodes);
        } else {
            var newText = toTranslate[i].textContent;
            toTranslate[i].textContent = translations[newText];
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