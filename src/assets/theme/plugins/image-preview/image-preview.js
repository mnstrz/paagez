class imagePreview{
	constructor(element,options) {
		this.element = element
		this.options = options
	}

	load()
	{
		var that = this
		var element = $("#"+this.options.id)
		var container = `<div class="image-preview" id="image-preview-${this.options.id}">${$("#"+this.options.id).html()}</div>`
		$(container).insertAfter("#"+this.options.id)
		$("#"+this.options.id).appendTo($("#image-preview-"+this.options.id))
		$("#image-preview-"+this.options.id).append(`<div class="image-preview-container"></div>`)
		if(!element.attr('multiple')){
			if(element.data('ip-src')){
				var url_remove = element.data('ip-remove') ? element.data('ip-remove') : ''
				var btn_remove = (this.options.remove) ? `<button type='button' data-ip-remove="${url_remove}">x</button>` : ``
				$("#image-preview-"+this.options.id+" .image-preview-container").append(`<div class="image" style="width:${this.options.width}px;height:${this.options.height}px;"><img src="${element.data('ip-src')}">${btn_remove}</div>`)
			}
		}else{
			var images = element.data('ip-src')
			if(Array.isArray(images))
			{
				$.each(images,function(index,item){
					var url_remove = ''
					var remove = element.data('ip-remove')
					if(Array.isArray(remove)){
						if(remove[index]){
							url_remove = remove[index]
						}
					}
					var btn_remove = (that.options.remove) ? `<button type='button' class="remove-image" data-ip-remove="${url_remove}">x</button>` : ``
					$("#image-preview-"+that.options.id+" .image-preview-container").append(`<div class="image" style="width:${that.options.width}px;height:${that.options.height}px;"><img src="${item}">${btn_remove}</div>`)
				})
			}
		}
	}

	showImage(file)
	{
		var that = this
		var validate = true
		$("#image-preview-"+that.options.id+" .new-image").remove()
		$.each(file.files,function(index,image){
			var mime = image.type
	    	mime = mime.split('/')
	    	var type = mime[0]
	    	mime = mime[1]
	    	if(type != 'image'){
	    		var message = that.options.mimeMessage.replaceAll(":mime","*")
	            Swal.fire({
	              text: message,
	              icon: 'info',
	              showDenyButton: false,
	              showCancelButton: false,
	              confirmButtonText: 'OK'
	            })
	    		validate = false
	    		return
	    	}
		    if(that.options.mimes.length > 0){
		    	if(!that.options.mimes.includes(mime)){
		    		var message = that.options.mimeMessage.replaceAll(":mime",that.options.mimes.join(','))
		            Swal.fire({
		              text: message,
		              icon: 'info',
		              showDenyButton: false,
		              showCancelButton: false,
		              confirmButtonText: 'OK'
		            })
		    		validate = false
		    		return
		    	}
		    }
			if(that.options.maxSizeValidation){
				var size = image.size/1024
		        if(size >= parseInt(that.options.maxSize)){
		        	var message = that.options.maxSizeMessage.replaceAll(":size",that.options.maxSize.toLocaleString())
		            Swal.fire({
		              text: message,
		              icon: 'info',
		              showDenyButton: false,
		              showCancelButton: false,
		              confirmButtonText: 'OK'
		            })
		            $(file).val('')
		            validate = false
		            return
		        }
		    }
		})
		if(validate){
			$.each(file.files,function(index,image){
				var src = that.previewImage(image)
			})
		}
	}

	previewImage(image)
	{
		var that = this
		var reader = new FileReader();
		var element = $("#"+this.options.id)
		var images = []
        reader.onload = function(event) {
        	var src = event.target.result
        	if(element.attr('multiple')){
            	$("#image-preview-"+that.options.id+" .image-preview-container").append(`<div class="image new-image" style="width:${that.options.width}px;height:${that.options.height}px;"><img src="${src}"></div>`)
        	}else{
            	$("#image-preview-"+that.options.id+" .image-preview-container").html(`<div class="image new-image" style="width:${that.options.width}px;height:${that.options.height}px;"><img src="${src}"></div>`)
        	}
			if(that.options.callback){
				window[that.options.callback](src)
			}
        }
        reader.readAsDataURL(image);
	}

	removeImage(url,element)
	{
		var that = this
		var param = this.removeParam
		$.ajax({
			url : url,
			type : that.options.removeMethod,
			data : param
		}).then(function(xhr){
			element.parent('.image').remove()
			if(that.options.removeCallback){
				window[that.options.removeCallback](xhr)
			}
		})
	}
}

(function($) {

    $.fn.extend({
        imagePreview: function(options) {
            options = $.extend( {}, $.imagePreview.defaults, options );
            this.each(function() {
                new $.imagePreview(this,options);
            });
            return this;
        }
    });

    $.imagePreview = function(element, options ) {
    	if(element.id){
    		options.id = element.id
    	}else{
    		let result = 'table-'+(Math.random() + 1).toString(36).substring(7)
    		options.id = result
    		$(element).attr('id',result)
    	}
    	var preview = new imagePreview(element,options)
    	if(options.autoPreview)
    	{
    		preview.load()
    	}
    	$(document).on('change','#'+options.id,function(e){
    		preview.showImage(this)
    	})
    	$(document).on('click','#image-preview-'+options.id+" .remove-image",function(e){
    		url = $(this).data('ip-remove')
    		if(url){
    			preview.removeImage(url,$(this))
    		}
    	})
    };

    $.imagePreview.defaults = {
    	width : 150,
    	height : 150,
    	remove : false,
    	removeMethod : 'GET',
    	removeParam : {},
    	removeCallback : null,
    	maxSizeValidation : false,
    	maxSize : 5000,
    	maxSizeMessage : "Maximum size of image is :size KB",
    	mimes : [],
    	mimeMessage : "Only accepted image format (:mime)",
    	autoPreview : true,
    	callback : null
    };

})(jQuery);