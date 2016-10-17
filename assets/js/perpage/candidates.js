function toggleCandidates() {
	$(".data").hide().each(function() {
		var line = $(this);
		
		var force = 0;
		
		if ($("button[value=candidate]").hasClass("active") && line.hasClass("candidate")) force++;
		else if ($("button[value=substitute]").hasClass("active") && line.hasClass("substitute")) force++;
		else if ($("button[value=representative]").hasClass("active") && line.hasClass("representative")) force++;

		if ($("button[value=male]").hasClass("active") && line.hasClass("male")) force++;
		else if ($("button[value=female]").hasClass("active") && line.hasClass("female")) force++;

		if ($("button[value=all-answered]").hasClass("active") && line.hasClass("all-answered")) force++;
		else if ($("button[value=some-answered]").hasClass("active") && line.hasClass("some-answered")) force++;
		else if ($("button[value=none-answered]").hasClass("active") && line.hasClass("none-answered")) force++;

		
		if (force == 3) {
			line.show();
		}
	});
	
	$(".found_persons").text($(".data:visible").length);
}

$(function() {
	$("#positions,#sexes,#contacted").on("click", "button", function() {
		$(this).toggleClass("active");
		
		toggleCandidates();
	});
});