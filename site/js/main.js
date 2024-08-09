window.addEventListener('load', function(){
	
    var btnNav = document.getElementById("mobile-nav-button");
 
    btnNav.addEventListener('click', function(){
        var mainNav = document.getElementById("main-nav");
        var styleObj = window.getComputedStyle(mainNav);
        var currentDisplay = styleObj.display;
        //alert(display);
        if(currentDisplay == "none"){
            mainNav.style.display = "block";
        }else{
            mainNav.style.display = "none";
        }

    });
});