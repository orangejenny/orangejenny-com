var soiree = function() {
	/* Initializes class variables */
	
};

soiree.prototype = {	
	
	loadHeader : function(step){
		var that = this;
		$('#header').load('html/header.html', function(){
			if (step > 0) {
				$("#headerNavImage").html("<img src='images/site_step" + step + ".png' />");
				$("#headerNavImage").mousemove(function(e) {
					var imageStep = that.getImageStepPos(e, this);
					if (imageStep != step) {
						$(this).css("cursor", "pointer");
					}
					else {
						$(this).css("cursor", "auto");
					}
				});
				$("#headerNavImage").click(function(e) {
					var imageStep = that.getImageStepPos(e, this);
					if (imageStep != step) {
						if (imageStep == 1) {
							window.location = "addDishes.html";
						}
						else if (imageStep == 2) {
							window.location = "preparation.html";
						}
						else {
							window.location = "cookMeal.html";
						}
					}
				});
				
			}
		});
	},
	
	getImageStepPos : function(e, obj) {
		var rv = 0;
		var siteWidth = 900;
		var imageWidth = 280;
		var xPos = e.pageX - obj.offsetLeft - (siteWidth / 2 - imageWidth / 2);
		if (xPos < imageWidth / 3) {
			rv = 1;
		}
		else if (xPos < imageWidth * 2 / 3) {
			rv = 2;
		}
		else {
			rv = 3;
		}
		return rv;
	},
	
	loadFooter : function(){
		$('#footer').load('html/footer.html');
	},
	
	loadSummary : function(page, step){
		
		var that = this;
		this.pageStep = page;
		this.StepStep = step;
		
		
		$('#soireeSummary').load('html/soireeSummary.html', function(){

		if(step == "step1"){
			document.getElementById("progressBtn").href="preparation.html";	
			document.getElementById("progressBtn").innerHTML="Shopping List  &raquo;";	
		} else if(step == "step2"){
			document.getElementById("progressBtn").href="cookMeal.html";
			document.getElementById("progressBtn").innerHTML="Start Cooking!";
			$("#recipeNameRow4").show();
		}
		
//		that.createSteps(that.pageStep, that.StepStep);
		});
		
	},
	
	convertFractions : function(expr) {
	
		$(expr).each(function() {
			var text = $(this).html();
			text = text.replace(/([0-9]+)\/([0-9]+)/, "<sup>$1</sup>&frasl;<sub>$2</sub>");
			$(this).html(text);
		});
	
	},
	
	loadProfileStars : function() {

		var max_stars = 5;

		$(".starRating").each(function() {
			var rating = $(this).text();
			var star = "<div style='float:left;width:16px;height:16px;background-image:url(css/ui.stars.gif);background-position:0 -32px;'></div>";
			var html = "<span style='font-size:1px;'>" + rating + "</span><span style='margin-left:10px;'></span>";
			for (var i = 0 ; i < max_stars; i++) {
				html = star + html;
			}
			$(this).html(html);
			setStarRating($(this), rating, true);
		});
		
		$(".starRating div").click(function() {
			var newRating = $(this).parent().children("div").index($(this)) + 1;
			$(this).parent().children("span:first").text(newRating);
			setStarRating($(this).parent(), newRating, true);
		});
		
		$(".starRating div").hover(function() {
			var newRating = $(this).parent().children("div").index($(this)) + 1;
			setStarRating($(this).parent(), newRating, false);
		}, function() {
			var oldRating = $(this).parent().children("span:first").text();
			setStarRating($(this).parent(), oldRating, true);
		});
		
		function setStarRating(div, rating, permanent) {
			div.children("div").each(function(i) {
				if (i <= rating - 1) {
					if (permanent) {
						$(this).css("background-position", "0 -48px");
					}
					else {
						$(this).css("background-position", "0 -64px");
					}
				}
				else {
					$(this).css("background-position", "0 -32px");
				}
			});
			if ($(".starRating").index(div) == 0) {
				var star_descriptions = ["I can't boil water!", "I can cook pasta", "I can follow a recipe", "I like to experiment", "Master chef"];
			}
			else {
				var star_descriptions = ["Bare", "Minimal", "Average", "Well-stocked", "Professional"];
			}
			div.children("span:last").html(star_descriptions[rating - 1]);
		}

	},
	
	createSteps : function(returnPage, step){
		
		 this.returnPage = returnPage;
		 this.step = step;
		 var that = this;
		 
		
		$('#createStepWrapper').load('html/createStep.html', function(){
			
			
			
//			if(that.step == "step1"){
//				document.getElementById("progressImage").src="images/step1.png";	
//
//			} else if(that.step == "step2"){
//				document.getElementById("progressImage").src="images/step2.png";
//			
//			} else if(that.step == "step3"){
//				document.getElementById("progressImage").src="images/step3.png";
//			}
			
		});
		
		
		
	},
		
	loadDialog : function(){
			$("#dialog").dialog({
		      bgiframe: true, autoOpen: false, height: 300, width:400, modal: true
		    });
	},
	
	datePicker : function(){
		$("#datepicker").datepicker();	
		//$("#datepicker").dialog( { dialogClass: 'createSoireeDialog' } );
	},
	
	makeTabs : function(){
		$("#addDishesTabs").tabs({ fx: { opacity: 'toggle' } });	
	},
	
	showListOption : function(id){
		$("#" + id).css("display","block");
	},
	
	hideListOption : function(id){
		$("#" + id).css("display","none");
		
	
	},
	hideRow : function(id){
		$("#" + id).remove();
		
		var shoppingList = $("#shoppingList");
		if(shoppingList){
			this.updateShoppingList();
		}
		this.updateSummaryStats();
	},
	
	highlightField : function(id){
		$("#" + id).addClass("editable");
		
	},
	unhighlightField : function(id){
		$("#" + id).removeClass("editable");
		
	},
	
	editField : function(id){
		$("#" + id).add("input");
	},
	
	addRecipe : function(recipeName, id){
		$("<li id='recipeNameRow" + id + "'> <div  class='recipeListSumWrapper'><div class='recipeListSumWrapper'  onmouseover='soiree.showListOption(\"recipeName" + id + "\");' onmouseout='soiree.hideListOption(\"recipeName" + id + "\");'><span class='fl'>" + recipeName + "</span><span class='summRecX' id='recipeName" + id + "' onclick='soiree.hideRow(\"recipeNameRow" + id + "\")'></span></div></li>").appendTo("#recipeListUl");
		$("#" + id).html("Dish Added");
		
		this.updateSummaryStats();
	},
	
	updateShoppingList : function(){
		var that = this;
		this.turnOnLoader("shoppingListInner", "shoppingLoader");
		var t=setTimeout("$('#shoppingListInner').css('opacity', '1'); $('#shoppingLoader').css('display', 'none')",1000);
	},
	
	updateSummaryStats : function(){
		this.turnOnLoader("nutritionStatsInner", "nutritionStatsLoader");
		var t=setTimeout("$('#nutritionStatsInner').css('opacity', '1'); $('#nutritionStatsLoader').css('display', 'none')",1000);
		
		this.turnOnLoader("prepStatsInner", "prepStatsLoader");
		var t2=setTimeout("$('#prepStatsInner').css('opacity', '1'); $('#prepStatsLoader').css('display', 'none')",1000);
	},
	
	turnOnLoader : function(div, loader){
		$("#" + div).css("opacity", "0.2");
		$("#" + loader).css("display", "block");
	},
	
	loadGnatt : function(){
		$(".hoverRow").hover(function() {
          $(this).children("td").addClass('rowHighlight');
			
        }, function() {
          $(this).children("td").removeClass('rowHighlight');
        
        });
		
		$(".hoverRow").click(function(){
			var thisRowIndex = this.rowIndex;
			
			var allRows = $(".hoverRow");
			$(allRows).children("td").removeClass('rowClicked');
			
			$("#stepView .selected").removeClass("selected");
			$("#stepGroup" + thisRowIndex).addClass("selected");
			  
			$("#stepGroup" + thisRowIndex).addClass("selected")
						
			$(this).children("td").addClass('rowClicked');
			
			$("#printButtonWrapper").animate({
			    opacity: 1
			  }, 250);
		});
		
	},
	
	goToNextStep : function (){
		$("#stepView .selected").each(function() {
			$(this).removeClass("selected");
			$(this).next(".stepViewWrapper").addClass("selected");
		});
	},
	
	toggleStepWrapper : function(){
		if(document.getElementById("currentStepWrapperDiv").style.display == "none"){
			document.getElementById("currentStepWrapperDiv").style.display = "block";
			document.getElementById("cookArrow").src="images/arrow2.png";
		} else{
			
			document.getElementById("currentStepWrapperDiv").style.display = "none";
			document.getElementById("cookArrow").src="images/arrow.png";
		}
		
	},
	
	showCheckBox : function(id){
		$(id).children(".stepCheck").css("display", "block");		
	},
	hideCheckBox : function(id){
		$(id).children(".stepCheck").css("display", "none");		
	},
	
	strikeThrough : function(id){
		
		if(id.checked == true){
			$(id).parent(".stepCheck").parent(".stepViewElem").children(".stepViewLeft").css("text-decoration", "line-through");
			$(id).parent(".stepCheck").parent(".stepViewElem").children(".stepViewLeft").css("color", "#b1b1b1");
		} else {
			$(id).parent(".stepCheck").parent(".stepViewElem").children(".stepViewLeft").css("text-decoration", "none");
			$(id).parent(".stepCheck").parent(".stepViewElem").children(".stepViewLeft").css("color", "#333333");	
		}
		
	}
	
	
};