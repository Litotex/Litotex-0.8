function countTextSigns(object, name, max){
	if (max > 0){
		display = document.getElementById(name + 'TextCounter');
		display.innerHTML = object.value.length + '/' + max;
		if(object.value.length > max){
			display.style['color'] = 'red';
		} else {
			display.style['color'] = '';
		}
	}
}
function countTextSignsSubmit(params){
	object = params[0];
	name = params[1];
	max = params[2];
	message = params[3];
	if (max > 0){
		if(object.value.length > max){
			alert(message);
		}
	}
}
