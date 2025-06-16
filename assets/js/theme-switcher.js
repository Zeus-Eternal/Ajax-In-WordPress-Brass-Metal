(function(){
    function applyTheme(theme){
        if(theme === 'auto'){
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        document.body.dataset.theme = theme;
    }

    applyTheme(ajaxinwp_theme.theme);

    if(ajaxinwp_theme.theme === 'auto'){
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(){
            applyTheme('auto');
        });
    }
})();
