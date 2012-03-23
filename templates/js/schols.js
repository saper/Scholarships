

$(document).ready(function() {
	$('#dump').hide();
	$('#showfulldump').click(function() {
		$('#dump').toggle();
		return false;
	});
	$('#rankingstoggle').click(function() {
		$('#rankingitems').toggle();
		return false;
	});
});
