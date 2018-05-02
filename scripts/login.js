//Checks to see if the username and password match.
function check(form)/*function to check userid & password*/
{
	/*the following code checkes whether the entered userid and password are matching*/
	//if(form.username.value == "admin" && form.password.value == "admin")
	var result = false;
	jQuery.ajax({
	    type: "POST",
	    url: 'scripts/pass.php',
	    dataType: 'json',
	    data: {functionname: 'login', user_name: form.username.value, pass_word: form.password.value},

	    success: function (obj, textstatus) {
	    	if( !('error' in obj) ) {
				window.sessionStorage.setItem('user_type', 'logged_in');
				console.log(window.sessionStorage.getItem('user_type'));
				window.location.href="index.html"; 
			}
			else {
				console.log(obj.error);
			}
		}
    });
}

//Allows for enter to submit the form
var elem = document.getElementById("password");
elem.addEventListener("keyup", function(event) {
	event.preventDefault()
	if (event.keyCode === 13) {
		check(document.getElementById("login"));
	}
});
