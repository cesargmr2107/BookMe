

function addField(form, name, value){
	var input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    form.appendChild(input);
}

function crearform(name, method){
	var form = document.createElement('form');
	document.body.appendChild(formu);
    form.name = name;
    form.method = method;
    form.action = 'index.php';   
}

function sendForm(form, check){
	if (check){
		form.submit();
	}
	else{
		return false;
	}

}
