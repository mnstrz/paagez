class timePicker{

	constructor(element,options) {
		this.element = element
		this.options = options
	}

	calculatePosition(items=[],radius,centerX,centerY,leadingZero=false){
		var x,y,name,angle,positions = []
		const numPoints = items.length
		const angleIncrement = (2 * Math.PI) / numPoints;
		$.each(items,function(index,item)
		{
			angle = (index+1) * angleIncrement;
		    x = centerX + radius * Math.sin(angle);
		    y = centerY - radius * Math.cos(angle);
		    if(leadingZero)
		    {
		    	name = (item<10) ? '0'+item : item
		    }else{
		    	name = item
		    }
		    positions.push({
		    	value : item,
		    	label : name.toString(),
		    	x : x,
		    	y : y
		    })
		})
		return positions
	}

	load()
	{
		var that = this
		$('.timepicker-container').remove()
		var hours_am = []
		for(var i=1;i<=12;i++){
			hours_am.push(i)
		}
		hours_am = this.calculatePosition(hours_am,90,95,95,true)
		var hours_am_element = ``
		$.each(hours_am,function(index,item)
		{
			hours_am_element += `<div class="item select_hour" style="left:${item.x}px;top:${item.y}px">
									<input type="radio" name="hour" id="hour_${item.value}" value="${item.value}" data-label="${item.label}">
									<label for="hour_${item.value}" data-label="${item.label}">${item.label}</label>
								</div>`
		})
		var hours_pm = []
		for(var i=13;i<=24;i++){
			hours_pm.push(i)
		}
		hours_pm = this.calculatePosition(hours_pm,60,65,65)
		var hours_pm_element = ``
		$.each(hours_pm,function(index,item)
		{
			hours_pm_element += `<div class="item select_hour" style="left:${item.x}px;top:${item.y}px">
									<input type="radio" name="hour" id="hour_${item.value}" value="${item.value}" data-label="${item.label}">
									<label for="hour_${item.value}" data-label="${item.label}">${item.label}</label>
								</div>`
		})
		var minutes = []
		for(var i=0;i<=59;i+=this.options.minuteInverval){
			minutes.push(i)
		}
		minutes = this.calculatePosition(minutes,90,95,95,true)
		var minutes_element = ``
		$.each(minutes,function(index,item)
		{
			var text = (item.value%5 == 0) ? item.label : '•';
			minutes_element += `<div class="item select_minute" style="left:${item.x}px;top:${item.y}px">
									<input type="radio" name="minute" id="minutes_element_${index}" value="${item.value}" data-label="${item.label}">
									<label for="minutes_element_${index}" data-label="${item.label}">${text}</label>
								</div>`
		})
		var id = "container-"+this.options.id
		var top = $("#"+this.options.id).position().top
		var left = $("#"+this.options.id).position().left
		var window_height = $(document).height()
		var window_width = $(document).width()
		var bottom_pos = (top+400 >= window_height) ? 'timepicker-bottom' : '';
		var right_pos = (left+300 >= window_width) ? 'timepicker-right' : '';
		if(this.options.bottom)
		{
			bottom_pos = 'timepicker-bottom'
		}
		if(this.options.right)
		{
			right_pos = 'timepicker-right'
		}
		var container = `<div class="timepicker-container ${bottom_pos} ${right_pos}" id="${id}">
							<div class="timepicker-dialog">
								<form class="timepicker-body">
									<div class="label">
										<button class="hour" type="button">00</button>
										<div>:</div>
										<button class="minute" type="button">00</button>
									</div>
									<div class="minutes">
										<div class="items">
											${minutes_element}
										</div>
									</div>
									<div class="hours hours-am">
										<div class="items">
											${hours_am_element}
										</div>
									</div>
									<div class="hours hours-pm">
										<div class="items">
											${hours_pm_element}
										</div>
									</div>
								</form>
								<div class="timepicker-footer">
									<button type="button" role="button" class="btn btn-outline-primary btn-sm ms-1 close-timepicker">Close</button>
									<button type="button" role="button" class="btn btn-primary btn-sm ms-1 submit-timepicker">Apply</button>
								</div>
							</div>
						</div>`
		$(container).insertAfter($(this.element))
		this.defaultValue()
		this.showHour()
	}

	defaultValue()
	{
		var that = this
		var value = $(this.element).val()
		if(value.length)
		{
			value = value.split(":")
			var hour = value[0]
			var minute = value[1]
			$('#container-'+that.options.id+" input[name='hour'][data-label='"+hour+"']").trigger('click')
			$('#container-'+that.options.id+" input[name='minute'][data-label='"+minute+"']").trigger('click')
		}
	}

	close()
	{
		var that = this
		$('#container-'+that.options.id).remove()
	}

	submit()
	{
		var that = this
		var value = ''
		var hour = $('#container-'+that.options.id+" input[name='hour']:checked").data('label')??'00'
		var minute = $('#container-'+that.options.id+" input[name='minute']:checked").data('label')??'00'
		$(this.element).val(hour+":"+minute)
		this.close()
	}

	showHour()
	{
		var that = this
		$('#container-'+that.options.id+" .minutes").removeClass('show')
		$('#container-'+that.options.id+" .hours").addClass('show')
		$('#container-'+that.options.id+" button.minute").removeClass('active')
		$('#container-'+that.options.id+" button.hour").addClass('active')
	}

	showMinute()
	{
		var that = this
		$('#container-'+that.options.id+" .minutes").addClass('show')
		$('#container-'+that.options.id+" .hours").removeClass('show')
		$('#container-'+that.options.id+" button.minute").addClass('active')
		$('#container-'+that.options.id+" button.hour").removeClass('active')
	}
}

(function($) {

    $.fn.extend({
        timePicker: function(options) {
            options = $.extend( {}, $.timePicker.defaults, options );
            this.refresh = function() {

		    }
		    if (!$(this).attr('data-toggle-timepicker')) {
	            $(this).attr('data-toggle-timepicker','timePicker');
	            this.each(function() {
	                new $.timePicker(this,options);
	            });
	        }
            return this;
        }
    });

    $.timePicker = function(element, options) {
    	if(element.id){
    		options.id = element.id
    	}else{
    		let result = 'timepicker-'+(Math.random() + 1).toString(36).substring(7)
    		options.id = result
    		$(element).attr('id',result)
    	}
    	$(element).attr('readonly','readonly')
    	options.top = $(element).position().top;
    	options.left = $(element).position().left;
    	var time = new timePicker(element,options)

    	$(document).on('click','#container-'+options.id+" input[name='hour']",function(e)
    	{
    		var text = $('#container-'+options.id+" input[name='hour']:checked").data('label')
    		$('#container-'+options.id+" .hour").text(text)
    		time.showMinute()
    	})

    	$(document).on('mouseover','#container-'+options.id+" .select_hour label",function(e)
    	{
    		var text = $(this).data('label')
    		$('#container-'+options.id+" .hour").text(text)
    	})

    	$(document).on('mouseout','#container-'+options.id+" .select_hour label",function(e)
    	{
    		var text = $('#container-'+options.id+" input[name='hour']:checked").data('label')
    		$('#container-'+options.id+" .hour").text(text)
    	})

    	$(document).on('click','#container-'+options.id+" input[name='minute']",function(e)
    	{
    		var text = $('#container-'+options.id+" input[name='minute']:checked").data('label')
    		$('#container-'+options.id+" .minute").text(text)
    		time.showHour()
    	})

    	$(document).on('mouseover','#container-'+options.id+" .select_minute label",function(e)
    	{
    		var text = $(this).data('label')
    		$('#container-'+options.id+" .minute").text(text)
    	})

    	$(document).on('mouseout','#container-'+options.id+" .select_minute label",function(e)
    	{
    		var text = $('#container-'+options.id+" input[name='minute']:checked").data('label')
    		$('#container-'+options.id+" .minute").text(text)
    	})

    	$(document).on('click','#container-'+options.id+" button.hour",function(e)
    	{
    		time.showHour()
    	})

    	$(document).on('click','#container-'+options.id+" button.minute",function(e)
    	{
    		time.showMinute()
    	})


    	$(document).on('click focus',"#"+options.id,function()
    	{
    		time.load()
    	})

    	$(document).on('click','#container-'+options.id+" .submit-timepicker",function(e)
    	{
    		time.submit()
    	})

    	$(document).on('click','#container-'+options.id+" .close-timepicker",function(e)
    	{
    		time.close()
    	})
    	$(document).mouseup(function(e) 
		{
		    var container = $(".timepicker-dialog");
		    if (!container.is(e.target) && container.has(e.target).length === 0) 
		    {
		    	time.close()
		    }
		});
    };

    $.timePicker.defaults = {
    	minuteInverval : 5,
    	bottom: false,
    	right: false
    };

})(jQuery);