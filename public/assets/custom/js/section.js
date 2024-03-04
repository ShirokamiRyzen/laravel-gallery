document.addEventListener('DOMContentLoaded', function() {
    var urlParams = new URLSearchParams(window.location.search);
    var searchQuery = urlParams.get('query');
    var albumQuery = urlParams.get('album_query');
    
    // Memeriksa apakah terdapat hash dalam URL
    var hash = window.location.hash;
    
    // Cek apakah hash adalah #images
    if (hash === '#images') {
        // Memanggil fungsi scrollToElement dengan parameter 'images'
        setTimeout(function() {
            scrollToElement('images');
        }, 1000);
    }
});

function scrollToElement(elementId) {
    var element = document.getElementById(elementId);
    if (element) {
        // Gulir ke elemen yang dituju dengan efek smooth
        element.scrollIntoView({ behavior: "smooth", block: "start" });
    }
}
