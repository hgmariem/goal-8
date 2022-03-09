function killCopy(e){
    return false;
}

function reEnable(){
    return true;
}

document.onselectstart = new Function("return false");
if (window.sidebar){
    document.onmousedown = killCopy;
    document.onclick = reEnable;
}

// Prevent press F12
$(document).keydown(function(e){
    if (e.keyCode == 123) {
        e.preventDefault();
    }

	

});