(function(){
    const img = document.querySelector('.custom-logo-link img');
    if(!img || typeof ajaxinwp_logo === 'undefined') return;

    function updateLogo(){
        const theme = document.body.dataset.theme;
        if(theme === 'dark' && ajaxinwp_logo.dark){
            img.src = ajaxinwp_logo.dark;
        } else if(ajaxinwp_logo.light){
            img.src = ajaxinwp_logo.light;
        }
    }

    new MutationObserver(updateLogo).observe(document.body, { attributes:true, attributeFilter:['data-theme'] });
    document.addEventListener('DOMContentLoaded', updateLogo);
})();
