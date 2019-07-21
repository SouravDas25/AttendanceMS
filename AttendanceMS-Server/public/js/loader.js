function MY_Page_onLoad(loading, loaded) 
{
    if(!loading || !loaded)return;
    if(document.readyState === 'complete'){
        return loaded();
    }
    loading();
    if (window.addEventListener) {
        window.addEventListener('load', loaded, false);
    }
    else if (window.attachEvent) {
        window.attachEvent('onload', loaded);
    }
};

MY_Page_onLoad( function()
{
    document.getElementById('loader').style.display = "block";
}, function ()
{
    document.getElementById('loader').style.display = "none";
    document.getElementById('page-body').style.display = "block";
});



function Disable_my_click_load_wait()
{
    document.getElementById('loader').style.display = "none";
    document.getElementById('page-body').style.display = "block";
}

function MY_click_load_wait()
{
    document.getElementById('loader').style.display = "block";
    document.getElementById('page-body').style.display = "none";
}


