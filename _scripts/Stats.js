/*
    Stats.js

    Ivan's stat helper class.

    runs at /*, handles all stats
*/
/*
    sq10 | CI:B0107 | 3/3

    Stats.js -> Heavy update, add all new helpers.
*/
/*
    sq10 | CI:B0109 | 2/2

    Stats.js -> Add timer functionality.
*/
var Stats = new (function(){
    //Simplifies "this" referencing.
    var self = this;

    //Initializes the module, and attaches stat header handlers.
    self.init = function(){
        self.initializeData();
        self.updateStats();
        self.statUpdateInterval = setInterval(self.updateStats, 1000);
        self.addTimerHandlers();
    };

    /*
        Adds timer handlers.
    */
    self.addTimerHandlers = function(){
        var handlers = [
            "#statEnergyBarContainer",
            "#statHealthBarContainer",
            "#statStaminaBarContainer"
        ];
        for (var i=0;i<handlers.length;i++){
            $(handlers[i]).parent().on("mouseover", self.handleBarMouseOver);
            $(handlers[i]).parent().on("mouseout", self.handleBarMouseOut);
        }
    }

    /*
        Initializes data from Stats.phpData
    */
    self.initializeData = function(){
        var ignoreList = ["experienceLevels"];
        for (var i in Stats.phpData){
            if (ignoreList.indexOf(i) > -1){
                continue;
            }
            Stats.data[i] = Stats.phpData[i];
        }
        self.data.experienceLevels = {};
        for (var i=0;i<Stats.phpData.experienceLevels.length;i++){
            var cur = Stats.phpData.experienceLevels[i];
            self.data.experienceLevels[parseInt(cur.id)] = parseInt(cur.experience);
        }
    }

    //Stat data.
    self.data = {};

    /*
        Getters and setters, and adders + removers

        Setters, adders and removers return true if the function finished okay.
    */
    self.getBalance = function(){
        return self.data.balance;
    }
    self.setBalance = function(amt){
        if (amt >= 0){
            self.data.balance = parseInt(amt);
            self.updateStats();
            return true;
        }
        return false;
    }
    self.addBalance = function(amt){
        return self.setBalance(self.getBalance()+amt);
    }
    self.removeBalance = function(amt){
        return self.setBalance(self.getBalance()-amt);
    }

    self.getPremiumBalance = function(){
        return self.data.premiumBalance;
    }
    self.setPremiumBalance = function(amt){
        if (amt >= 0){
            self.data.premiumBalance = parseInt(amt);
            self.updateStats();
            return true;
        }
        return false;
    }
    self.addPremiumBalance = function(amt){
        return self.setPremiumBalance(self.getPremiumBalance()+amt);
    }
    self.removePremiumBalance = function(amt){
        return self.setPremiumBalance(self.getPremiumBalance()-amt);
    }

    self.getHealth = function(){
        return self.data.health;
    }
    self.setHealth = function(amt){
        if (amt >= 0 && amt <= self.data.healthLimit){
            self.data.health = parseInt(amt);
            self.updateStats();
            return true;
        }
        return false;
    }
    self.addHealth = function(amt){
        return self.setHealth(self.getHealth()+amt);
    }
    self.removeHealth = function(amt){
        return self.setHealth(self.getHealth()-amt);
    }

    self.getEnergy = function(){
        return self.data.energy;
    }
    self.setEnergy = function(amt){
        if (amt >= 0 && amt <= self.data.energyLimit){
            self.data.energy = parseInt(amt);
            self.updateStats();
            return true;
        }
        return false;
    }
    self.addEnergy = function(amt){
        return self.setEnergy(self.getEnergy()+amt);
    }
    self.removeEnergy = function(amt){
        return self.setEnergy(self.getEnergy()-amt);
    }

    self.getStamina = function(){
        return self.data.stamina;
    }
    self.setStamina = function(amt){
        if (amt >= 0 && amt <= self.data.staminaLimit){
            self.data.stamina = parseInt(amt);
            self.updateStats();
            return true;
        }
        return false;
    }
    self.addStamina = function(amt){
        return self.setStamina(self.getStamina()+amt);
    }
    self.removeStamina = function(amt){
        return self.setStamina(self.getStamina()-amt);
    }

    self.getExperience = function(){
        return self.data.experience;
    }
    self.addExperience = function(amt){
        self.data.experience += parseInt(amt);
        self.updateStats();
    }
    /*
        End of getters/setters.
    */

    /*
        Element selector map for each stat name.
    */
    self.selectorMap = {
        "balance": "#statBalance",
        "premiumBalance": "#statPremiumBalance",
        "health": "#statHealth",
        "healthLimit": "#statHealthLimit",
        "energy": "#statEnergy",
        "energyLimit": "#statEnergyLimit",
        "stamina": "#statStamina",
        "staminaLimit": "#statStaminaLimit",
        "experience": "#statLevel"
    }

    /*
        Bar element selector map for each stat name
    */
    self.barSelectorMap = {
        "health": "#statHealthBar",
        "energy": "#statEnergyBar",
        "stamina": "#statStaminaBar",
        "experience": "#statLevelBar"
    }

    /*
        Element selector for the experience level number
    */
    self.experienceLevelSelector = "#statExperienceLevel";

    /*
        Updates user stat header.
    */
    self.updateStats = function(){
        var staticStats = [
            "balance",
            "premiumBalance"
        ];
        var barStats = [
            "health",
            "energy",
            "stamina"
        ];
        self.updateExperienceStats();
        self.updateTimers();

        //Update static stats.
        for (var i=0;i<staticStats.length;i++){
            var cur = staticStats[i];
            $(self.selectorMap[cur]).text(self.data[cur]);
        }

        //Update bar stats.
        for (var i=0;i<barStats.length;i++){
            var cur = barStats[i];
            $(self.selectorMap[cur]).text(self.data[cur]);
            $(self.selectorMap[cur+"Limit"]).text(self.data[cur+"Limit"]);
            $(self.barSelectorMap[cur]).css({
                width: Math.round((self.data[cur]/self.data[cur+"Limit"])*100)+"%"
            });
        }
    }

    /*
        Updates user stat header experience.
    */
    self.updateExperienceStats = function(){
        var cur = "experience";
        /*
            sq10 | CI:B0108 | 4/4

            Use experienceLevels.
        */
        while (self.data[cur] >= self.data[cur+"Limit"]){
            self.data[cur+"Level"] += 1;
            self.data[cur+"Current"] = self.data.experienceLevels[self.data[cur+"Level"]];
            self.data[cur+"Limit"] = self.data.experienceLevels[self.data[cur+"Level"]+1];
        }
        var percent = Math.round(
            (
                (self.data[cur]-self.data[cur+"Current"])/
                (self.data[cur+"Limit"]-self.data[cur+"Current"])
            )*100
        )+"%";
        $(self.experienceLevelSelector).text(self.data[cur+"Level"]);
        $(self.selectorMap[cur]).text(percent);
        $(self.barSelectorMap[cur]).css({
            width: percent
        });
    }

    /*
        Element selector map for each stat timer
    */
    self.timerSelectorMap = {
        "health": "#statHealthTimer",
        "energy": "#statEnergyTimer",
        "stamina": "#statStaminaTimer"
    };

    /*
        Updates the bar timers.

        =======
        WARNING
        =======

        This code relies on the fact that a refill rate is below an hour.
        If the refill rate is above an hour, open a ticket to fix this.
    */
    self.updateTimers = function(){
        var timers = [
            "health",
            "energy",
            "stamina"
        ];
        for (var i=0;i<timers.length;i++){
            var cur = timers[i];

            var timeNow = (Date.now()/1000)%3600;
            var timeEnd = self.data[cur+"Refill"]%3600;

            if ((timeEnd-timeNow) <= 0){
                self.data[cur+"Refill"] += self.data[cur+"Rate"];
                timeEnd = self.data[cur+"Refill"]%3600;
                self["add"+cur.toProperCase()](1);
            }
            var timeLeft = new Date((timeEnd-timeNow)*1000);
            $(self.timerSelectorMap[cur]).find(".timer-minutes").text(("0"+String(timeLeft.getMinutes())).slice(-2));
            $(self.timerSelectorMap[cur]).find(".timer-seconds").text(("0"+String(timeLeft.getSeconds())).slice(-2));
        }
    }

    /*
        Handlers for bar mouseovers/outs
    */
    self.handleBarMouseOver = function(e){
      //alert(e.currentTarget.value);
        $(e.currentTarget).addClass("show-timer");
    }
    self.handleBarMouseOut = function(e){
        $(e.currentTarget).removeClass("show-timer");
    }
})();
