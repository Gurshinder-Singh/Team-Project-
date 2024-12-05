// External script file: navbar.js

let prevScrollpos = window.pageYOffset;
let debounce;

window.onscroll = function() {
    clearTimeout(debounce);

    debounce = setTimeout(function() {
        let currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            document.getElementById("navbar").style.top = "0";
        } else {
            document.getElementById("navbar").style.top = "-50px";
        }
        prevScrollpos = currentScrollPos;
    }, 100); // Adjust the debounce delay as necessary
};
