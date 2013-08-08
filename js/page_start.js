function change_watchlist(releases_id)
{	
	if($("#releases_" + releases_id).hasClass("disabled"))
	{
		$.post('ajax.php',{action: 'remove_from_watchlist', releases_id: releases_id},function(data){
			if(data.error == '')
			{
				$('#watchlist_releases_' + releases_id).remove();
				$("#releases_" + releases_id).removeClass("disabled",true);
				$("#releases_" + releases_id).removeClass("add_to_watchlist");
				$("#releases_" + releases_id).removeClass("remove_from_watchlist");
				$("#releases_" + releases_id).addClass("add_to_watchlist");
			}
			else alert_glow(data.error,'error');
		},'json');
	}
	else
	{
		$.post('ajax.php',{action: 'add_to_watchlist', releases_id: releases_id},function(data){
			if(data.error == '')
			{
				$('#watchlist').append(data.html);
				sortlist('watchlist');
				$("#releases_" + releases_id).addClass("disabled",true);
				$("#releases_" + releases_id).removeClass("add_to_watchlist");
				$("#releases_" + releases_id).removeClass("remove_from_watchlist");
				$("#releases_" + releases_id).addClass("remove_from_watchlist");
			}
			else alert_glow(data.error,'error');
		},'json');
		
	}

}

function change_cat1()
{
	$('#cat2').html('');
	$('#cat3').html('');

	$.post('ajax.php',{action: 'get_cat2', cat1: $('#cat1 select').val()},function(data){
		if(typeof data != 'object')
			data = $.parseJSON(data);
		if(data.error == '')
		{
			if(data.cat1 != 'all')
			{
				$('#cat2').html(data.select);
			}
		}
		else alert(data.error);
	});
}


$(function() {
	$('#cat1 select').change(change_cat1);
});