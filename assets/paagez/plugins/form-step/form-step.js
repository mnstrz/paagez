class formStep{
	constructor(element,options) {
		this.element = element
		this.options = options
	}

	load()
	{
		var height = 300
		var active = $('#'+this.options.id+" .form-step-nav li.active").data('step')
		var validate = 0
		$('#'+this.options.id+" .form-step-item").each(function(){
			if(validate == 0)
			{
				if($(this).find('.error').length > 0)
				{
					validate = $(this).data('step')
					active = validate
				}
			}
		})
		$('#'+this.options.id+" .form-step-container").css('height',(height+50)+'px')
		$('#'+this.options.id+" .form-step-container").hide()
		this.slideStep(active)
	}

	slideStep(active=0)
	{
		active = parseInt(active)
		var that = this
		var width = $('#'+this.options.id+" .form-step-container").outerWidth()
		$('#'+this.options.id+" .form-step-nav li").each(function(index,item){
			var step = parseInt($(this).data('step'))
			var positionLeft = '0px';
			if(step > active)
			{
				positionLeft = (step-active)*width+'px'
			}else if(step < active){
				positionLeft = "-"+(((active-step)*width)+100)+'px'
			}
			$('#'+that.options.id+" .form-step-item[data-step='"+step+"']").css('left',positionLeft)
		})
		$('#'+this.options.id+" .form-step-nav li").removeClass('active')
		$('#'+this.options.id+" .form-step-nav li[data-step='"+active+"']").addClass('active')
		setTimeout(function(){
			$('#'+that.options.id+" .form-step-container").show()
			var height = $('#'+that.options.id+" .form-step-item[data-step='"+active+"']").outerHeight()
			$('#'+that.options.id+" .form-step-container").css('height',(height+50)+'px')
		},1000)
	}
}

(function($) {

    $.fn.extend({
        formStep: function(options) {
            options = $.extend( {}, $.formStep.defaults, options );
            this.each(function() {
                new $.formStep(this,options);
            });
            return this;
        }
    });

    $.formStep = function(element, options ) {
    	if(element.id){
    		options.id = element.id
    	}else{
    		let result = 'table-'+(Math.random() + 1).toString(36).substring(7)
    		options.id = result
    		$(element).attr('id',result)
    	}
    	var form = new formStep(element,options)
    	form.load()
    	$(document).on('click','#'+options.id+" *[data-toggle-step]",function(e){
    		var step = parseInt($(this).data('toggle-step'))
    		var isValidating = $(this).data('validate')
    		if(isValidating){
    			isValidating = isValidating.toString()
    			isValidating = isValidating.split(',')
	    		if(isValidating.length > 0)
	    		{
			    	var validate = [true]
	    			$.each(isValidating,function(index,item){
			    		if(!isNaN(step) && !isNaN(item))
			    		{
			    			if(options.validate && item)
			    			{
				    			var validator = $('#'+options.id).validate()
				    			$('#'+options.id+' .form-step-item[data-step="'+item+'"] input').each(function()
				    			{
				    				if(!validator.element(this))
				    				{
				    					validate.push(false)
				    				}
				    			})
				    			$('#'+options.id+' .form-step-item[data-step="'+item+'"] select').each(function()
				    			{
				    				if(!validator.element(this))
				    				{
				    					validate.push(false)
				    				}
				    			})
				    			$('#'+options.id+' .form-step-item[data-step="'+item+'"] textarea').each(function()
				    			{
				    				if(!validator.element(this))
				    				{
				    					validate.push(false)
				    				}
				    			})
			    			}
			    		}
	    			})
	    			if(!validate.includes(false))
	    			{
	    				form.slideStep(step)
	    			}
	    		}else{
		    		if(!isNaN(step)){
		    			form.slideStep(step)
		    		}
	    		}
    		}else{
	    		if(!isNaN(step)){
	    			form.slideStep(step)
	    		}
    		}
    	})
    };

    $.formStep.defaults = {
    	transition : 1,
    	validate : true
    };

})(jQuery);