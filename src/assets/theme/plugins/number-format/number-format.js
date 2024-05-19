class numberFormat{
	constructor(element,options) {
		this.element = element
		this.options = options
	}

	load()
	{
		var that = this
		var element = $("#"+this.options.id)
		var container = `<div class="number-format" id="number-format-${this.options.id}">${$("#"+this.options.id).html()}</div>`
		$(container).insertAfter("#"+this.options.id)
		$("#"+this.options.id).appendTo($("#number-format-"+this.options.id))
		var attr = this.getAttributes(element)
		var newInput = this.createInput(attr)
		$("#"+this.options.id).remove()
		$("#number-format-"+this.options.id).append(newInput.input)
		$("#number-format-"+this.options.id).append(newInput.hidden)
		this.defaultFormat()
	}

	getAttributes ( node ) {
	    var attrs = {};
		if(node.length > 0){
		    $.each(node[0].attributes, function ( index, attribute ) {
		        attrs[attribute.name] = attribute.value;
		    });
		}
	    return attrs;
	}

	createInput(attributes)
	{
		var attr = ``
		var attr_input = ``
		$.each(attributes,function(param,value){
			if(param != 'type' && param != 'class')
			{
				attr += `${param}="${value}" `
			}
		})
		$.each(attributes,function(param,value){
			if(param != 'type' && param != 'id' && param != 'name')
			{
				attr_input += `${param}="${value}" `
			}
		})
		return {
			input : `<input type="text" ${attr_input}>`,
			hidden : `<input type="hidden" ${attr}>`
		}
	}

	defaultFormat()
	{
		var that = this
		var value = $("#number-format-"+this.options.id+" input[type='hidden']").val()
		var newValueFormat = ""
		if(this.options.currency){
			newValueFormat = parseFloat(value).toLocaleString(this.options.currencyFormat,{
				style : 'currency',
				currency : 'USD'
			})
		}else{
			newValueFormat = parseFloat(value).toLocaleString()
			newValueFormat = newValueFormat.replaceAll(",",";")
			newValueFormat = newValueFormat.replaceAll(".","'")
			newValueFormat = newValueFormat.replaceAll(";",this.options.delimiter)
			newValueFormat = newValueFormat.replaceAll("'",this.options.decimal)
		}
		if(isNaN(parseFloat(newValueFormat))){
			newValueFormat = 0
			$("#number-format-"+this.options.id+" input[type='hidden']").val(newValueFormat)
		}
		$("#number-format-"+this.options.id+" input[type='text']").val(newValueFormat)
	}

	refreshFormat(value)
	{
		if(value.slice(-1) == this.options.decimal){
			return
		}
		value = value.replaceAll(this.options.delimiter,'')
		value = value.replaceAll(this.options.decimal,'.')
		$("#number-format-"+this.options.id+" input[type='hidden']").val(value)
		this.defaultFormat()
	}
}

(function($) {

    $.fn.extend({
        numberFormat: function(options) {
            options = $.extend( {}, $.numberFormat.defaults, options );
            this.each(function() {
                new $.numberFormat(this,options);
            });
            return this;
        }
    });

    $.numberFormat = function(element, options ) {
    	if(element.id){
    		options.id = element.id
    	}else{
    		let result = 'numberformat-'+(Math.random() + 1).toString(36).substring(10)
    		options.id = result
    		$(element).attr('id',result)
    	}
    	var number = new numberFormat(element,options)
    	number.load()
    	$(document).on("keypress keyup blur","#number-format-"+options.id+" input[type='text']",function (event) {
	        $(this).val($(this).val().replace(/[^0-9\.|\,]/g,''));
	        if(event.key == options.decimal)
	        {
	        	return true;
	        }
	        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57  )) {
	          event.preventDefault();
	        }
	        number.refreshFormat($(this).val())
	    });
    };

    $.numberFormat.defaults = {
    	currency : false,
    	currencyFormat : 'en-US',
    	delimiter : ',',
    	decimal : '.'
    };

})(jQuery);