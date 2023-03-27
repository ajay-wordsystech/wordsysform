
(function( $ ) {
	'use strict';

	$(".checkall").click(function () {
		$('input:checkbox').not(this).prop('checked', this.checked);
	});

})( jQuery );

function openTab(evt, cityName) {
	var i, tabcontent, tablinks;
  
	// Get all elements with class="tabcontent" and hide them
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
	  tabcontent[i].style.display = "none";
	}
  
	// Get all elements with class="tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
	  tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
  
	// Show the current tab, and add an "active" class to the button that opened the tab
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " active";
}

function confirm_delete(url) {
	
    let modal = document.getElementById("deleteModal");
	modal.style.display = "block";
	
    let close = document.querySelectorAll('.wordsysform-close-1')
	for (var i = 0; i < close.length; ++i) {		
		close[i].onclick = function() {		
			modal.style.display = "none";
		}
	}

	let action = document.querySelector(".wordsysform-action-1");
	action.onclick = function() {		
		location.href = url
	}

	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
}
function confirm_apply(url) {

	let form = document.getElementById('wordsysform-form-list')
	let frm_action = document.getElementById('frm_action')
	
	let checkall_item = document.querySelectorAll('.checkall-item:checked')		
	
	if( checkall_item.length > 0 && frm_action.value ){
	
		let modal = document.getElementById("applyModal");
		modal.style.display = "block";
		
		let close = document.querySelectorAll('.wordsysform-close-2')
		for (var i = 0; i < close.length; ++i) {		
			close[i].onclick = function() {		
				modal.style.display = "none";
			}
		}		

		let action = document.querySelector(".wordsysform-action-2");
		action.onclick = function() {		
			form.submit()
		}

		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}

	}
}
function view_wordsysform_data(id,url){
	var $wpq = jQuery.noConflict();
	let data = {
		contact_id:id,
		action:'contact_data'
	};       
	$wpq.ajax({
	type: "post",
	dataType: "json",
	url: url,   
	data: data, 
	success: function(response){

		$wpq('#wordsysform-view').html(response.success_message)
		let modal = document.getElementById("viewDataModal");
		modal.style.display = "block";
		
		let close = document.querySelector(".wordsysform-close-3");
		close.onclick = function() {		
			modal.style.display = "none";
		}
		
	}
	});   
}


// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}