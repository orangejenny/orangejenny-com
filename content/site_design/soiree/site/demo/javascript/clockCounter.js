
/* class clockCounter
 * 
 * creates a counter for distance traveled and miles traveled 
 * */
var clockCounter = function() {
	this.clockVal = 60;
	this.minuteVal = 45;
	this.mileVal = 0;
	
	this.updateClock();
	
	
};

clockCounter.prototype = {

	// Updates the clock 1 tick every second
	updateClock : function(){		
		var that = this;	
		this.clockVal--;
		if(this.clockVal == 0){
			this.clockVal = 60;	
			this.minuteVal--;
		}
			
		var newClock = "<span>4:"+this.minuteVal+":" + this.clockVal + "</span>"; 
	
		if($("#clockDiv").length > 0){
			$("#clockDiv").get(0).innerHTML = newClock;	
			
			setTimeout(function(){
				that.updateClock();
				
			}, 1000);
		}
		
	},
	
	// Updates the 1s column	
	updateMileCounter : function(){
		var that = this;	
		var newMile = "<span>" + this.mileVal + "</span>"; 
	
		if($("#mileDiv").length > 0){
			$("#mileDiv").get(0).innerHTML = newMile;	
			
			setTimeout(function(){
				that.updateMileCounter();
			}, 5000);
		}
		this.mileVal = this.mileVal + 0.1;
		this.mileVal = Math.round(this.mileVal *10)/10;
	}
};



