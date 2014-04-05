var soiree_process = function() {
	/* Initializes class variables */
	this.initializePage();
};

soiree_process.prototype = {	
	
	initializePage : function() {
		$("#header_image").click(function(){
			window.location = 'index.html';
		});
		
		$("#footer").html("BID S2010, A3. Human-Computer Interaction Institute. Carnegie Mellon University");
		
		
		
		//$.getScript('NFLightBox/js/NFLightBox.js', function() {
			  
		//	});
		
		jQuery.each(jQuery.browser, function(i) {
			   if($.browser.msie){
				   $("#header").css("height", "100px");
			   }
		});
		
	},
	
	loadImageBox : function() {
		$(function() {
      		 var settings = { containerResizeSpeed: 350
           };
           $('#gallery a').lightBox(settings);
       });	
	}

	
};