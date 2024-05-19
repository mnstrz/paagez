class alertMessageClass{
	constructor(options) {
		this.options = options
		this.type = ['success','info','warning','error']
		this.title = {
			'success' : 'Success', 
			'info' : 'Info', 
			'warning' : 'Warning', 
			'error' : 'Error', 
		}
		this.style = {
			'success' : 'alert-success', 
			'info' : 'alert-info', 
			'warning' : 'alert-warning', 
			'error' : 'alert-danger', 
		}
	}

	load()
	{
		var type = (this.type.includes(this.options.type)) ? this.options.type : 'info'
		var title = (this.options.title) ? this.options.title : this.title[type]
		var style = this.style[type]
		$('body .alert-message').remove()
		$('body').append(`<div class="alert alert-pop alert-pill alert-dismissible fade show animate__animated animate__bounceInRight alert-message ${style} ${this.options.class}" role="alert">
	      <strong>${title}!</strong> ${this.options.message}
	      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	    </div>`)
	}
}

function alertMessage(options)
{
	var config = {
		type : 'info',
		title : '',
		message : '',
		class : ''
	}
	$.each(options,function(index,item)
	{
		config[index] = item
	})
	var alert_message = new alertMessageClass(config)
	alert_message.load()
}