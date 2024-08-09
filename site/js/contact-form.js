window.addEventListener('load', function(){
        
    // this will help you if you have some errors in your code
    window.addEventListener("error", function(eventObj){
        alert("ERROR: " + eventObj.message);
    });

    var contactForm = document.getElementById("contactForm");
    
    // form inputs
    var txtFirstName = document.getElementById("txtFirstName");
    var txtLastName = document.getElementById("txtLastName");
    var txtEmail = document.getElementById("txtEmail");
    var txtComments = document.getElementById("txtComments");

    // validation divs
    var vFirstName = document.getElementById("vFirstName");
    var vLastName = document.getElementById("vLastName");
    var vEmail = document.getElementById("vEmail");
    var vComments = document.getElementById("vComments");

    // event handler for when the form is submitted
    contactForm.addEventListener("submit", function(eventObj){
        if(validateContactForm() == false){
            eventObj.preventDefault();
        }
    });

    // this function should return false if the input is not valid
    // it should return true if the input is valid
    function validateContactForm(){
        var formIsValid = true;

        clearValidationMessages();
        
        // validate the first name entered
        if(txtFirstName.value == ""){
            vFirstName.innerHTML = "Please enter your first name";
            vFirstName.style.display = "block";
            formIsValid = false;
        }

        // validate the last name entered
        if(txtLastName.value == ""){
            vLastName.innerHTML = "Please enter your last name";
            vLastName.style.display = "block";
            formIsValid = false;
        }

        // validate the email entered
        if(txtEmail.value == ""){
            vEmail.innerHTML = "Please enter your email";
            vEmail.style.display = "block";
            formIsValid = false;
        }else if(validateEmailAddress(txtEmail.value) == false){
            vEmail.innerHTML = "The email you entered is not valid";
            vEmail.style.display = "block";
            formIsValid = false;
        }

        // validate the comments entered
        if(txtComments.value == ""){
            vComments.innerHTML = "Please enter some comments";
            vComments.style.display = "block";
            formIsValid = false;
        }else if(containsURL(txtComments.value)){
            vComments.innerHTML = "URLs are not allowed in the comments";
            vComments.style.display = "block";
            formIsValid = false;
        }

        return formIsValid;
    }

    // clears out all the validation messages
    function clearValidationMessages(){
        var divs = document.querySelectorAll(".validation-message");
        for(var x = 0;  x < divs.length; x++){
            divs[x].innerHTML = "";
            divs[x].style.display = "none";
        }
    }

    // checks a string to see if a URL is in it (returns true if the string has a URL in it, false if not)
    function containsURL(str){
        var regExp = /\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i;
        return regExp.test(str);
    }

    // validates an email address (returns true it is valid, false if it is not)
    function validateEmailAddress(email){
        var regExp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regExp.test(email);
    }


});// this is the end of window.addEventListener('load',function(){})