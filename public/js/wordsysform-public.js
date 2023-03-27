// (function( $ ) {
// 	'use strict';
// })( jQuery );

const default_msg = 'This is required'

function showMessage(input, message, type) {
	const msg = input.parentNode.querySelector("small");
	msg.innerText = message;
	if (type) {
		input.classList.add("error")
	} else {
		input.classList.remove("error")
	}
	return type;
}

function showError(input, message) {
	return showMessage(input, message, 1);
}

function showSuccess(input) {
	return showMessage(input, "", 0);
}

const validate_wordsysform = (validation,form)=>{

	var total_error = 0;
  
	if(validation){    
	  
		validation.map( (item,i)=>{		
				if( item['type'] ==='text' && form.elements[item['field_name']] ){
				total_error = total_error + validate_text(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='email' && form.elements[item['field_name']] ){
				total_error = total_error + validate_email(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='url' && form.elements[item['field_name']] ){
				total_error = total_error + validate_url(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='tel' && form.elements[item['field_name']] ){
				total_error = total_error + validate_tel(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='number' && form.elements[item['field_name']] ){
				total_error = total_error + validate_number(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='date' && form.elements[item['field_name']] ){
				total_error = total_error + validate_date(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='textarea' && form.elements[item['field_name']] ){
				total_error = total_error + validate_textarea(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='dropdownmenu' && form.elements[item['field_name']] ){
				total_error = total_error + validate_dropdownmenu(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='checkboxes' && form.elements[item['field_name']] ){
				total_error = total_error + validate_checkboxes(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='radiobuttons' && form.elements[item['field_name']] ){
				total_error = total_error + validate_radiobuttons(form.elements[item['field_name']],item)
				}
				else if( item['type'] ==='file' && form.elements[item['field_name']] ){
				total_error = total_error + validate_file(form.elements[item['field_name']],item)
				}  
	 	})
	}
	
	if( total_error == 0	){ 
		return true;
	}
	else{
		return false;
	}
  }
  //======
  const validate_text = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	if (input.value.trim() === "") {
		  return showError(input, message);
	  }
	  return showSuccess(input);
  }
  const validate_email = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	var Text = input.value.trim();
  
	let regexp = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);    
  
	if (Text === "") {
		  return showError(input, message);
	  }
	else if(Text.match(regexp)){	
	  return showSuccess(input);
	}
	else{
	  return showError(input, 'Please enter a valid email address format');
	}	
	  return 0;
  }
  const validate_url = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	var Text = input.value.trim();
  
	let regexp = new RegExp('(https?://)?([\\da-z.-]+)\\.([a-z.]{2,6})[/\\w .-]*/?');    
  
	if (Text === "") {
		  return showError(input, message);
	  }
	else if(Text.match(regexp)){	
	  return showSuccess(input);
	}
	else{
	  return showError(input, 'Please enter a valid URL format');
	}	
	  return 0;
  }
  const validate_tel = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	var Text = input.value.trim();
	let regexp = new RegExp('^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$');    
  
	if (Text === "") {
		  return showError(input, message);
	  }
	else if(Text.match(regexp)){	
	  return showSuccess(input);
	}
	else{
	  return showError(input, 'Please enter a valid Phone Number');
	}	
	  return 0;
  }
  const validate_number = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	const min = (item['min']) ? item['min'] : 0
	const max = (item['max']) ? item['max'] : 0
  
	var Text = input.value.trim();
	var Number = parseInt(Text)
  
	if (Text ==='') {
		  return showError(input, message);
	  }
	else if (Number < min || Number > max && (min >0 && max>0) ) {
		  return showError(input, 'Please enter a number between '+min+','+max);
	  }
	  return showSuccess(input);
  }
  const validate_date = (input,item)=>{ //===
	const message = (item['msg']) ? item['msg'] : default_msg
	if (input.value.trim() === "") {
		  return showError(input, message);
	  }
	  return showSuccess(input);
  }
  const validate_textarea = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	if (input.value.trim() === "") {
		  return showError(input, message);
	  }
	  return showSuccess(input);
  }
  const validate_dropdownmenu = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	if (input.value.trim() === "") {
		  return showError(input, message);
	  }
	  return showSuccess(input);
  }
  const validate_checkboxes = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	if (input.value.trim() === "") {
		  return showError(input, message);
	  }
	  return showSuccess(input);
  }
  const validate_radiobuttons = (input,item)=>{
	const message = (item['msg']) ? item['msg'] : default_msg
	if (input.value.trim() === "") {
		  return showError(input, message);
	  }
	  return showSuccess(input);
  }
  const validate_file = (input,item)=>{ //===
	const message = (item['msg']) ? item['msg'] : default_msg
	if (input.value.trim() === "") {
		  return showError(input, message);
	  }
	  return showSuccess(input);
  }
