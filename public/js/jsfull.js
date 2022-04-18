$(document).ready(function () {
    aos_init();
});

$(window).on('load', function() {
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
    
})

function link(end){
    $('#fullpage').addClass('pgfull_2')    
    setTimeout(function(){ window.location.href = end;}, 50);
    
}
function aos_init() {
    AOS.init({
    // duration: 100,
    easing: "ease-in-out", 
    once: true
    });
}


