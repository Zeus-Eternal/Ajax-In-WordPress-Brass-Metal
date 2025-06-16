(function(){
    document.addEventListener('error', function(event){
        var target = event.target;
        if(target.tagName === 'IMG' && !target.dataset.fallbackLoaded){
            target.dataset.fallbackLoaded = 'true';
            target.src = ajaxinwp_params.fallbackImage;
        }
    }, true);
})();
