// Copyright (C) 2012 Down Range Games, Inc
// 1084 Paloma Rd., Monterey, CA 93940

//toProperCase
// "word" -> "Word"
String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

function init() {
    init_data();
    d = new Date();
    /*console.log("The current time is: " + d.getTime());
     console.group("Base stats")
     console.log("Health last refilled %d seconds ago.", health_refill);
     console.log("A total of %d seconds have passed since the last refill.  A total of %d points are available.", local_now - health_refill, Math.floor((local_now - health_refill) / 180));
     console.log("Energy last refilled %d seconds ago.", energy_refill);
     console.log("A total of %d seconds have passed since the last refill.  A total of %d points are available.", local_now - energy_refill, Math.floor((local_now - energy_refill) / 300));
     console.log("Stamina last refilled %d seconds ago.", stamina_refill);
     console.log("A total of %d seconds have passed since the last refill.  A total of %d points are available.", local_now - stamina_refill, Math.floor((local_now - stamina_refill) / 300));
     console.groupEnd();

     console.log("Current health is %d out of a maximum of %d.", health, health_limit);
     console.log("Current energy is %d out of a maximum of %d.", energy, energy_limit);
     console.log("Current stamina is %d out of a maximum of %d.", stamina, stamina_limit);*/

    setInterval(function(){
        if (combatant_Max != 0) {
            var combatDmg = combatant_Max - combatant_Current;
            $('#npcDamage').text('-' + combatDmg);
        }
    }, 500);
}

/*
    sq10 | CI:B0107 | 2/3

    functions.js -> Get rid of all stat-related functions.
*/

function ucfirst(str) {
    str += '';
    var f = str.charAt(0)
            .toUpperCase();
    return f + str.substr(1);
}
function popupNotAtBase() {
    if (Stats.getEnergy() >= base_place.energy_cost) {
        $('#popBase_haveEnergy').show();
    } else {
        $('#popBase_noEnergy').show();
    }
}

function format(duration) {
    days = parseInt(duration / (24 * 60 * 60));
    left = duration % (24 * 60 * 60);
    hours = parseInt(left / (60 * 60));
    left = left % (60 * 60);
    minutes = parseInt(left / 60);
    seconds = parseInt(left % 60);
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
    str = minutes + ":" + seconds;
    if (hours) {
        str = hours + ":" + str;
    }
    if (days) {
        str = parseInt(days) + " day(s) " + str;
    }
    return str;
}

$(document).ready(function() {
    if (!location.hash) {
        return;
    }
    var id = location.hash.substr(1);
    if (!$('#' + id).length) {
        return;
    }
    showonlyone(id);
});

function showintro(id) {
    $('.section').hide();
    $('#' + id).show();
}

function seen_base_intro(id){
    $.ajax({
		url:'/base/seen_base_intro',
        type: "POST",
        data:{player_id: id},
        success:function(result){
        }
	});
}

function sell_item(player_id,player_item_id){
    $.ajax({
		url:'/action/sell_item',
        type: "POST",
        data:{player_id: player_id,player_item_id:player_item_id}
	});
}

function store_item(player_id,player_item_id){
    $.ajax({
		url:'/action/store_item',
        type: "POST",
        data:{player_id: player_id,player_item_id:player_item_id}
	});
}

function retrieve_item(player_id,player_item_id){
    $.ajax({
		url:'/action/retrieve_item',
        type: "POST",
        data:{player_id: player_id,player_item_id:player_item_id}
	});
}

function sell_thing(player_id,player_thing_id){
    $.ajax({
		url:'/action/sell_thing',
        type: "POST",
        data:{player_id: player_id,player_thing_id:player_thing_id}
	});
}

function showonlyone(id) {
    $('.section').hide();
    $('#' + id).show();
}

function toggleOne(id, id2) {
    if ($('#' + id).is(':visible')) {
        $('.' + id2).hide();
    } else {
        $('.' + id2).hide();
        $('#' + id).show();
    }
}

function toggle(ele) {
    $('#' + ele).toggle();
}

function show(id) {
    $('#' + id).show();
}

function hide(id) {
    $('#' + id).hide();
}

function checkCache() {
    if ($('.selectedCache').css('left') == '10px') {
        $('.cacheWeaponSection').hide();
        $('#cache1').show();
    }
    ;

    if ($('.selectedCache').css('left') == '242px') {
        $('.cacheWeaponSection').hide();
        $('#cache2').show();
    }
    ;

    if ($('.selectedCache').css('left') == '474px') {
        $('.cacheWeaponSection').hide();
        $('#cache3').show();
    }
    ;
}

function loadItem(id) {
    $('#öbjInfo').empty();
    $('#öbjInfo').load('/store/item/' + id);
    $('#popStore').show();
}

function loadModifier(id) {
    $('#öbjInfo').empty();
    $('#öbjInfo').load('/store/modifier/' + id);
    $('#popStore').show();
}

function loadThing(id) {
    $('#öbjInfo').empty();
    $('#öbjInfo').load('/store/thing/' + id);
    $('#popStore').show();
}

function loadBoosts(id) {
    $('#öbjInfo').empty();
    $('#öbjInfo').load('/store/boost/' + id);
    $('#popStore').show();
}

function checkPasswordMatch() {
    var password = $("#password").val();
    var confirmPassword = $("#password2").val();

    if (password == confirmPassword || !$(".testresult").hasClass("shortPass")) {
        $('#submit').removeAttr('disabled');
        $('#submit').removeClass('gray');
        $('#submit').addClass('blue');
        return true;
    }
}

$(document).ready(function() {
	/**
	 * Ticket #85
	 */
	 $(".storeWeaponCashebox").delegate(".storeCachePopUp","mouseover", function(e){
	    	e.stopPropagation();
	    	$(".cachePopUp").hide();
	    	$('#popUp'+$(this).attr('id')).toggle();
	    });

	 $(".storeWeaponCashebox").delegate(".storeCachePopUp","mouseout", function(e){
	    	e.stopPropagation();
	    	$('#popUp'+$(this).attr('id')).hide();
	    });
	   // $(".storeWeaponCashebox").delegate("#team_sort", change)
	    $(".storeWeaponCashebox").delegate("#items_sort","change",function(e){
			var val = $(this).val();
		    $.ajax({
		            url:"/store/ajax_order_items",
		            type: "POST",
		            data:{items_sort : val},
		            success:function(result){
		                $(".ajax_store_cache").html(result);
		                $("#items_sort").val(val);
		                
		            }
		     });
		    
	    });
	    $(".storeWeaponCashebox").delegate(".generateCacheItem", "click", function(e){
	      	var val = $("#items_sort").val();
	    	$.ajax({
	    		url:"/store/ajax_generate_cache_item",
	            type: "POST",
	            data:{items_sort : val},
	            success:function(result){
	                $(".ajax_store_cache").html(result);
	                $("#items_sort").val(val);
	                
	            }
	    	});
	    });
	    

	    $(".storeWeaponCashebox").delegate("#dropGeneratedItem", "click", function(e){
	    	var val = $("#items_sort").val();
	    	 $.ajax({
		            url:"/store/ajax_order_items",
		            type: "POST",
		            data:{items_sort : val},
		            success:function(result){
		                $(".ajax_store_cache").html(result);
		                $("#items_sort").val(val);
		                
		            }
		     });
	    	
	    });
	    
	    $(".storeWeaponCashebox").delegate("#getGeneratedItem", "click", function(e){
	    	var val = $("#items_sort").val();
	    	var generatedItemId = $("#generatedItemId").val();
	    	 $.ajax({
		            url:"/store/ajax_generate_cache_item",
		            type: "POST",
		            data:{items_sort : val, item_id: generatedItemId},
		            success:function(result){
		                $(".ajax_store_cache").html(result);
		                $("#items_sort").val(val);
		                $("#generatedPopUp").hide();
		                
		            }
		     });
	    	
	    });
	    
	    
	    
	    /*
	     * End Ticket #85
	     * 
	     */

    $('.profileBtn ').click(function(event) {

    	var i = $(this).attr('id');
    	  event.stopPropagation();
          if ($(".inventoryStats[id="+i+"]").is(':visible')) {
              $(".inventoryStats[id="+i+"]").hide();
          } else {
              $(".inventoryStats[id="+i+"]").hide();
              $(".inventoryStats[id="+i+"]").show();
          }
    });
    $(".itemImageModal").bind('mouseover', function(e){
        $(".itemDetailsModal").hide();
        $('#'+$(this).attr('id')+'content').show(); 

    });


	 $(".ajaxContent").delegate(".small-pad", "click" ,function(event) {
	      event.stopPropagation();
	      $('.small-pad').children('img').removeClass('equipOpacity');
	      if ($(this).children('.equipStats').is(':visible')) {
	          $('.equipStats').hide();
	      } else {
	          $('.equipStats').hide();
	          $(this).children('.equipStats').show();
	          $(this).children('img').addClass('equipOpacity');
	      }
	  });

	   $('.ajaxContent').delegate( '.inventory-slot' , 'click', function(event) {
	        event.stopPropagation();
	        if ($(this).children('.inventoryStats').is(':visible')) {
	            $('.inventoryStats').hide();
	        } else {
	            $('.inventoryStats').hide();
	            $(this).children('.inventoryStats').show();
	        }
	    });


	$(".ajaxContent").delegate(".selectorSquad","change",function(e){
		var id = $(this).val();
	    var player_id = id.split("_");
	    $.ajax({
	            url:"/profile/ajax_squad_members",
	            type: "POST",
	            data:{user_id : id},
	            success:function(result){
	                $(".ajaxContent").html(result);
	                $(".selectorSquad").val(id);
	                $(".squad_player_id").val(id);
	                
	            }
	     });

	});

   $('.ajaxContent').delegate(".enableItem",'click',function(e){
   	   e.preventDefault();

       var squadPlayer = $(".selectorSquad").val();
       var form = $(this).closest("form");
       var player_item_id = $("input[name='player_item_id']",form).val();
       var item_id = $("input[name='item_id']",form).val();
       var squad_player_id  = $(".selectorSquad").val();
       $.ajax({
           url: "action/equip",
           type: "post",
           data:{player_item_id: player_item_id,item_id:item_id, squad_player_id:squad_player_id  },
           success:function(result){
               $(".ajaxContent").html(result);
               $(".selectorSquad").val(squadPlayer);
               $("input[name='squad_player_id']").val(squad_player_id);
               }
           });

       });

   $('.ajaxContent').delegate("input[name='remove_item']",'click', function(e){
   	e.preventDefault();
   	var squadPlayer = $(".selectorSquad").val();
	    var form = $(this).closest("form");
 	    var player_item_id = $("input[name='player_item_id']",form).val();
       $.ajax({
       	url: "action/unequip" ,
           type: "post",
           data:{ squad_player_id:squadPlayer, player_items_squad_id : player_item_id  },
           success:function(result){
        	   $(".ajaxContent").html(result);
        	   $(".selectorSquad").val(squadPlayer);
        	   $(".squad_player_id").val(squadPlayer);
               }
           });
       });



    $(".password_test").passStrength();
    $("#password").keyup(checkPasswordMatch);
    $("#password2").keyup(checkPasswordMatch);

    $('.locationTitleTab').click(function() {
        $('#location').show();
    });

    $('.hideIcon').click(function() {
        $('#location').hide();
    });

    $('#exploreBtn').click(function() {
        $('#exploreBtn').attr('disabled', 'disabled');
        $('#exploreBtn').removeClass('blue');
        $('#exploreBtn').addClass('gray');
        $('#explore').submit();
    });

    $('.mapTravel').click(function() {
        $('.mapTravel').attr('disabled', 'disabled');
        $('.mapTravel').removeClass('blue');
        $('.mapTravel').addClass('gray');
        $(this).parent('form').submit();
    });

    $('#fightBtn').click(function() {
        $('#fightBtn').attr('disabled', 'disabled');
        $('#fleeBtn').attr('disabled', 'disabled');
        $('#fightBtn').removeClass('blue');
        $('#fleeBtn').removeClass('blue');
        $('#fightBtn, #fleeBtn').addClass('gray');
        $('#fight').submit();
    });

    $('#fleeBtn').click(function() {
        $('#fightBtn').attr('disabled', 'disabled');
        $('#fleeBtn').attr('disabled', 'disabled');
        $('#fightBtn').removeClass('blue');
        $('#fleeBtn').removeClass('blue');
        $('#fightBtn, #fleeBtn').addClass('gray');
        $('#flee').submit();
    });

    $('.closeLevelPos').click(function() {
        $('#levelUp').submit();
    });

    $('.storeBtn').click(function() {
        $('#popStore').hide();
    });

    $('#congratsLevel').click(function() {
        $('#congratsBtn').attr('disabled', 'disabled');
        $('#congratsBtn').removeClass('blue');
        $('#congratsBtn').addClass('gray');
        $('#levelUp').submit();
    });

    $('.modBtn').click(function() {
        var modifier = $(this).parent('form');
        $('#popModifier').show();

        $('#modify').click(function() {
            modifier.submit();
        });

    });
/*
    $('.inventory-slot').click(function(event) {
        event.stopPropagation();
        if ($(this).children('.inventoryStats').is(':visible')) {
            $('.inventoryStats').hide();
        } else {
            $('.inventoryStats').hide();
            $(this).children('.inventoryStats').show();
        }
    });
*/
    $('.mapPosition').click(function(event) {
        event.stopPropagation();
        if ($(this).children('.mapInfo').is(':visible')) {
            $('.mapInfo').hide();
        } else {
            $('.mapInfo').hide();
            $(this).children('.mapInfo').show();
        }
    });

    $('#cacheSelect1').click(function() {
        if ($('.selectedCache').css('left') == '242px') {
            $('.selectedCache').animate({left: '-=232px'}, 500, checkCache());
        }
        ;

        if ($('.selectedCache').css('left') == '474px') {
            $('.selectedCache').animate({left: '-=464px'}, 1000, checkCache());
        }
        ;
        $('.cacheWeaponSection').hide();
        $('#cache1').show();
    });

    $('#cacheSelect2').click(function() {
        if ($('.selectedCache').css('left') == '10px') {
            $('.selectedCache').animate({left: '+=232px'}, 500, checkCache());
        }
        ;

        if ($('.selectedCache').css('left') == '474px') {
            $('.selectedCache').animate({left: '-=232px'}, 500, checkCache());
        }
        ;
        $('.cacheWeaponSection').hide();
        $('#cache2').show();
    });

    $('#cacheSelect3').click(function() {
        if ($('.selectedCache').css('left') == '10px') {
            $('.selectedCache').animate({left: '+=464px'}, 1000, checkCache());
        }
        ;

        if ($('.selectedCache').css('left') == '242px') {
            $('.selectedCache').animate({left: '+=232px'}, 500, checkCache());
        }
        ;
        $('.cacheWeaponSection').hide();
        $('#cache3').show();
    });

    $('body').click(function(e) {
        $('.equipStats').hide();
        $('.small-pad').children('img').removeClass('equipOpacity');
		$('.large-pad').children('img').removeClass('equipOpacity');
        $('.inventoryStats').hide();
        $('.mapInfo').hide();
		$('#popLevel').hide();
        $('.modal').hide();
    });
/**
    $('.small-pad').click(function(event) {
        event.stopPropagation();
        $('.small-pad').children('img').removeClass('equipOpacity');
        if ($(this).children('.equipStats').is(':visible')) {
            $('.equipStats').hide();
        } else {
            $('.equipStats').hide();
            $(this).children('.equipStats').show();
            $(this).children('img').addClass('equipOpacity');
        }
    });
**/
	$('.large-pad').click(function(event) {
        event.stopPropagation();
        $('.large-pad').children('img').removeClass('equipOpacity');
        if ($(this).children('.equipStats').is(':visible')) {
            $('.equipStats').hide();
        } else {
            $('.equipStats').hide();
            $(this).children('.equipStats').show();
            $(this).children('img').addClass('equipOpacity');
        }
    });

    $('.introNext').click(function() {
        ++introPage;
        $.post('/intro/upd_progress', {page: introPage});
        $('#introStart').animate({marginTop: '-=480px'}, 1000);
    });

    $('.introNext').click(function() {
        $('.introNext').toggle();
    });

    $('.introAction').click(function() {
        $(this).hide();
        $(this).parent('.introSection').children('.popUp').show();
        $(this).parent('.introSection').children('.explorePosition').show();

        if ($('.explorePosition').is(":visible")) {
            $(this).parent('.introSection').children('.popUp').hide();
            $('.introProgress').removeClass('pos4');
            $('.introProgress').addClass('pos1');
        }
    });

    $('.exploreButton').click(function() {
        if ($('.introProgress').hasClass('pos4')) {
            $('.introProgress').width('100%');
            $('.exploreProgressText span').text('100');
            $('.pos4').show();
        }

        if ($('.introProgress').hasClass('pos3')) {
            $('.introProgress').width('75%');
            $('.exploreProgressText span').text('75');
            $('.pos3').show();
        }

        if ($('.introProgress').hasClass('pos2')) {
            $('.introProgress').width('50%');
            $('.exploreProgressText span').text('50');
            $('.pos2').show();
        }

        if ($('.introProgress').hasClass('pos1')) {
            $('.introProgress').width('25%');
            $('.exploreProgressText span').text('25');
            $('.pos1').show();
        }

        $('.exploreButton').removeClass('blue');
        $('.exploreButton').addClass('gray');
    });

    $('.confirm').click(function() {
        if ($('.explorePosition').is(":visible")) {
            if ($('.introProgress').is('.pos4')) {
                $('.introProgress').removeClass('pos4');
                $('.introProgress').addClass('posFinish');
            }

            if ($('.introProgress').is('.pos3')) {
                $('.introProgress').removeClass('pos3');
                $('.introProgress').addClass('pos4');
            }

            if ($('.introProgress').is('.pos2')) {
                $('.introProgress').removeClass('pos2');
                $('.introProgress').addClass('pos3');
            }

            if ($('.introProgress').is('.pos1')) {
                $('.introProgress').removeClass('pos1');
                $('.introProgress').addClass('pos2');
            }

            $('.exploreButton').removeClass('gray');
            $('.exploreButton').addClass('blue');

            if ($('.introProgress').is('.posFinish')) {
                $('.exploreButton').removeClass('blue');
                $('.exploreButton').addClass('gray');
                $('.introNext').toggle();
            }
        } else {
            $('.introNext').toggle();
        }
        $('.popUp').hide();
    });

    $('.introFight').click(function() {
        $('.fightButton').css('visibility', 'visible');
    });

    $('.fightButton').click(function() {
        var currentHp = $('.combatantHealthText span').text();
        var normAtk = 10 + (1 + Math.floor(Math.random() * 5));
        var critAtk = 15 + (1 + Math.floor(Math.random() * 15));
        var atkType = 1 + Math.floor(Math.random() * 10);

        if (atkType < 4) {
            if (critAtk > currentHp) {
                currentHp = 0;
                $('.fightButton').css('visibility', 'hidden');
                $('#introCombat').parent('.introSection').children('.popUp').show();
            } else {
                currentHp = currentHp - critAtk;
            }
        } else {
            if (normAtk > currentHp) {
                currentHp = 0;
                $('.fightButton').css('visibility', 'hidden');
                $('#introCombat').parent('.introSection').children('.popUp').show();
            } else {
                currentHp = currentHp - normAtk;
            }
        }
        $('.combatantHealthText span').text(currentHp);
        $('.combatantHealth').width((currentHp * 2) + '%');
    });

    $('.introCut').click(function() {
        $(this).hide();
        $('.damageButton').css('visibility', 'visible');
    });

    $('.damageButton').click(function() {
        var currentHp = $('.itemStrengthText span').text();
        var normAtk = 3 + (1 + Math.floor(Math.random() * 5));

        if (normAtk > currentHp) {
            currentHp = 0;
            $('.damageButton').css('visibility', 'hidden');
            $('.introNext').toggle();
        } else {
            currentHp = currentHp - normAtk;
        }

        $('.itemStrengthText span').text(currentHp);
        $('.itemStrength').width((currentHp * 2) + '%');
    });

    $('.cover').click(function() {
        $('.dead').show();
    });

    $('.end').click(function() {
        $('#intro').hide();
    });

    $('img.add-button-boosts').mouseover(function(){
    	$('.boost_msg').show();
    });

    $('img.add-button-boosts').mouseout(function(){
    	$('.boost_msg').hide();
    });

	$('.add-button').mouseover(function(){
    	$(this).children('.stat_msg').show();
    });

    $('.add-button').mouseout(function(){
    	$(this).children('.stat_msg').hide();
    });
	$('img.exploreInfo').mouseover(function(){
    	$(this).parent('.exploreProgressText').children('.stat_msg').show();
    });

    $('img.exploreInfo').mouseout(function(){
    	$(this).parent('.exploreProgressText').children('.stat_msg').hide();
    });
	$('img.exploreBarInfo').mouseover(function(){
    	$(this).parent('.exploreTooltip').children('.stat_msg').show();
    });

    $('img.exploreBarInfo').mouseout(function(){
    	$(this).parent('.exploreTooltip').children('.stat_msg').hide();
    });
	$('.stat-icon-absolute').mouseover(function(){
    	$(this).children('.stat_msg').show();
    });

    $('.stat-icon-absolute').mouseout(function(){
    	$(this).children('.stat_msg').hide();
    });

	$('.stat-icon-float').mouseover(function(){
    	$(this).children('.stat_msg').show();
    });

    $('.stat-icon-float').mouseout(function(){
    	$(this).children('.stat_msg').hide();
    });
	
	$('.statsRank').mouseover(function(){
    	$(this).children('.stat_msg').show();
    });

    $('.statsRank').mouseout(function(){
    	$(this).children('.stat_msg').hide();
    });

    $('.cacheWeaponSection').hide();
    $('#cache1').show();
});

/*
    Ivan's script for startup

    Loads all modules made that are applicable for the page.
*/
(function(){
    var possibleModules = [
        "Training",
        "Stats"
    ];

    $(function(){
        //Go through all possible modules.
        for (var i=0;i<possibleModules.length;i++){
            var module = possibleModules[i];
            //Check to see if the module exists along with
            //its init() function, and then run it asynchronously.
            window[module]&&
            window[module].init&&
            setTimeout(window[module].init);
        }
    });
})();