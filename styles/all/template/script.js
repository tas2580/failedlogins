phpbb.addAjaxCallback('failedlogins.remove', function(data) {
	if(!data.S_USER_WARNING)
	{
		$('#failedlogins').remove();
	}
});