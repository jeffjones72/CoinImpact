/*
    Training.js

    runs at /base, handles the "training" tab
*/
window.Training = new (function(){
    //Simplifies "this" referencing.
    var self = this;

    //Initializes the training script.
    self.init = function(){
        $("#training .attack .attackBtn").on("click", handleButtonClick);
    }

    /*
        Handles the training attack button click.

        e: DOMEvent
            the event for the attack button click
    */
    function handleButtonClick(e){
        var amt = e.currentTarget.innerHTML.trim();
        self.attackTarget(amt);
    }

    /*
        Sends the request to the server, querying XP and damage for the amt of attack.

        amt: number
            amount of stamina to use on target
    */
    self.attackTarget = function(amt){
        $.post(
            "/action/training_attack",
            {
                stamina: amt
            },
            function(data){
                try{
                    data = JSON.parse(data);
                }catch(e){
                    window.location.reload(true);
                }
                parseAttackData(amt, data);
                self.lastElementTransition();
                self.scrollDown();
                console.log("Training Dummy!");
                console.log(data);
            }
        ).fail(function(){
            window.location.reload(true);
        });
    }

    /*
        Scrolls the attack log to the bottom.
    */
    self.scrollDown = function(){
        $("#training .trainLog").scrollTop($("#training .trainLog")[0].scrollHeight);
    }

    /*
        Transitions the last element's background color.
    */
    self.lastElementTransition = function(){
        (function(elem){
            //We have a timeout because for some reason,
            //the transition doesn't work if you run it immediately
            //after the element is inserted
            //(that, and it looks nice)
            setTimeout(function(){
                elem.addClass("shown");
            }, 50);
        })($("#training .trainLog .log:last"));
    }

    //Template for the training hit log.
    var logTemplate = "<div class='log'>"+
        "<span class='stamina'>%STAM%</span>"+
            " stamina: "+
        "<span class='damage'>%DAM%</span>"+
            " damage + "+
        "<span class='xp'>%XP%</span>"+
            " XP"+
        "</div>";

    //Template for the training hit error log.
    var errorTemplate = "<div class='log error'>%MSG%</div>";

    /*
        Parses the returned attack data, and modifies the page accordingly.

        stamina: number
            amount of stamina used on target
        data: object
            the data that the server returned
    */
    function parseAttackData(stamina, data){
        if (!data.ok){
            if (data.error == "bad_data"){
                console.log("Training->parseAttackData: Bad data sent, can't attack.")
                $("#training .trainLog").append(
                    errorTemplate.replace("%MSG%", "Client sent bad data, could not attack!")
                );
            }
            else if (data.error == "no_stamina"){
                $("#training .trainLog").append(
                    errorTemplate.replace("%MSG%", "Not enough stamina!")
                );
            }
            else{
                console.log("Training->parseAttackData: Unknown server error.");
                $("#training .trainLog").append(
                    errorTemplate.replace("%MSG%", "An unknown error occured, could not attack!")
                );
            }
            return;
        }
        Stats.removeStamina(stamina);
        Stats.addExperience(data.xp);
        $("#training .trainLog").append(
            logTemplate
                .replace("%STAM%", stamina)
                .replace("%DAM%", data.damage)
                .replace("%XP%", data.xp)
        );
        showDmg(data.damage);
    }

    //Template for the damage number display.
    var dmgTemplate = "<div class='dmgHover dmgHoverID_%ID%'>%DMG%</div>";
    var dmgId = 0;
    
    /*
        Shows an overlay of the damage number on the training target background.

        amt: number
            damage amount to show
    */
    function showDmg(amt){
        var thisDmgId = dmgId++;
        $("#training .trainingBackground").append(
            dmgTemplate
                .replace("%ID%", thisDmgId)
                .replace("%DMG%", amt)
        );

        //Animate the damage number overlay.
        (function(){
            var elem = $("#training .dmgHoverID_"+thisDmgId);
            elem.css({
                bottom: 
                    (
                        150-30+
                        (Math.floor(Math.random()*60))
                    )+"px",
                left: 
                    (
                        ($("#training .trainingBackground").width()/2-40)+
                        (Math.floor(Math.random()*80))
                    )+"px"
            });
            elem.animate(
                {
                    opacity: 0,
                    bottom: "+=75"
                },
                1000,
                function(){
                    elem.remove();
                }
            );
        })();
    }
})();