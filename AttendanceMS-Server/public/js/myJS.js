function reload_page_if_loaded_from_cache()
{
    if(!!window.performance && window.performance.navigation.type === 2)
    {
        window.location.reload();
    }
}

function ordinal_suffix_of(i) {
    var j = i % 10,
        k = i % 100;
    if (j == 1 && k != 11) {
        return i + "st";
    }
    if (j == 2 && k != 12) {
        return i + "nd";
    }
    if (j == 3 && k != 13) {
        return i + "rd";
    }
    return i + "th";
}

function round(num,dec)
{
	num = Math.round(num+'e'+dec)
	return Number(num+'e-'+dec)
}
