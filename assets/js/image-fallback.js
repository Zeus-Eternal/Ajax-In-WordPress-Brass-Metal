(function(){
    function setFallback(img){
        if(img.dataset.fallbackLoaded){
            return;
        }
        img.dataset.fallbackLoaded = 'true';
        img.src = ajaxinwp_params.fallbackImage;
    }

    window.addEventListener('error', function(event){
        var target = event.target;
        if(target.tagName === 'IMG'){
            setFallback(target);
        }
    }, true);

    document.querySelectorAll('img').forEach(function(img){
        img.addEventListener('error', function(){
            setFallback(this);
        });
    });
})();
