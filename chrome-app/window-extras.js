var WindowExtras = {};

WindowExtras.mainWebview = null;

WindowExtras.init = function(){
    WindowExtras.mainWebview = document.getElementById('the_webview');
    WindowExtras.mainWebview.setAttribute('src', 'http://heyflat.com/themes/note/index.html');
}

window.addEventListener('load', WindowExtras.init, false);