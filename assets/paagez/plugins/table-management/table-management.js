class tableManagement{
	constructor(element,options) {
		this.element = element
		this.options = options
	}

	createTable()
	{
		var table = `<table class="table table-hover">
						<thead></thead>
						<tbody></tbody>
					</table>`
		$("#"+this.options.id).append(table)
	}

	createHeader()
	{
		var head = ``
		var that = this
		$.each(this.options.field, function(field,label){
			var sort = (that.options.sortField[field] == true) ? `data-tm-field="${field}"` : ""
			head += `<th ${sort}>${label}</th>`
		})
		var header = `
					<tr>
						${head}
					</tr>`
		$("#"+this.options.id+" table thead").html(header)
	}

	createFilter()
	{
		var show = (this.options.filter) ? '' : 'hide'
		var filters = `<div class="table-management mb-3">
							<div class="form-row form-icon">
								<input type="text" placeholder="${this.options.labels.search}" class="form-control search-field">
								<a href="#" class="icon search"><i class="fa-sharp fa-regular fa-magnifying-glass"></i></a>
							</div>
							<div class="table-management-filter ms-1 ${show}">
								<button class="btn btn-outline-primary btn-filter"><i class="fa-sharp fa-regular fa-filter"></i></button>
								<form class="filter-box" action="${this.options.url}">
									<div class="mb-2 d-flex default-column">
										<div class="d-flex flex-column" id="append-column"></div>
										<input type="hidden" name="keyword">
									</div>
									<div class="mb-2">
										<button type="submit" class="btn btn-primary">${this.options.labels.filter}</button>
									</div>
								</form>
							</div>
							<div class="d-flex append-button-container">
							</div>
						</div>`
		$(this.element).prepend(filters)
	}

	renderSortTable()
	{
		var that = this
		var first_field = ''
		$("#"+this.options.id+' th[data-tm-field]').each(function(index,item){
			$(this).children('a').remove()
			var field = $(this).data('tm-field')
			if(index == 0){
				first_field = field
			}
			var sort = $("#"+that.options.id+" form.filter-box input[name='sort']").val()
			var order = $("#"+that.options.id+" form.filter-box input[name='order']").val()
			if(field == sort){
				if(order == 'desc'){
					$(this).append(`<a href="#" class="sort ms-1" data-field="${field}"><i class="fa-duotone fa-sort-down"></i></a>`)
				}else{
					$(this).append(`<a href="#" class="sort ms-1" data-field="${field}"><i class="fa-duotone fa-sort-up"></i></a>`)
				}
			}else{
				$(this).append(`<a href="#" class="sort ms-1" data-field="${field}"><i class="fa-sharp fa-regular fa-sort"></i></a>`)
			}
		})
		if(first_field){
			if($("#"+this.options.id+" form.filter-box .default-column input[name='sort']").length == 0 && $("#"+this.options.id+" form.filter-box .default-column input[name='order']").length ==0){
				$("#"+this.options.id+" form.filter-box .default-column").append(`
										<input type="hidden" name="sort" value="${first_field}">
										<input type="hidden" name="order" value="asc">`)
			}
		}
	}

	pagination()
	{
		var show = ``
		if(this.options.pagination){
			$.each(this.options.pagination,function(page,label){
				show += `<option value="${page}">${label}</option>`
			})
		}
		if(show){
			var pagination = `<div class="table-pagination">
								<div class="table-pagination-nav"></div>
								<div class="table-pagination-show">
									<select class="form-select" name="show">
										${show}
									</select>
								</div>
							  </div>`
			var first_show = Object.keys(this.options.pagination)[0]
			if(first_show){
				$("#"+this.options.id+" form.filter-box .default-column").append(`
											<input type="hidden" name="show" value="${first_show}">
											<input type="hidden" name="page" value="1">`)
			}
			$(this.element).append(pagination)
		}
	}

	appendFilter()
	{
		$("#"+this.options.id+" form.filter-box").prepend($(this.options.addFilter))
	}

	appendButton()
	{
		$("#"+this.options.id+" .append-button-container").prepend($(this.options.addButton))
	}

	getData()
	{
		var that = this
		var data = $("#"+this.options.id+" form.filter-box").serialize()
		$.ajax({
			url : this.options.url,
			method : this.options.method,
			data : data,
			beforeSend : function(){

			},
			error : function(xhr){
				console.log(xhr)
				$("#"+that.options.id+" table tbody").html(`<tr><td>${xhr.responseJSON.message}</td></tr>`)
			}
		}).then(function(xhr){
			if(xhr)
			{
				if(that.options.row == 'data'){
					var rows = ``
					$.each(xhr.result.data,function(index,item)
					{
						var tr = `<tr>`
						$.each(that.options.field,function(field,label){
							if(item[field]){
								tr += `<td>${item[field]}</td>`
							}
						})
						tr += '</tr>'
						rows += tr
					})
					$("#"+that.options.id+" table tbody").html(rows)
					$("#"+that.options.id+" .table-pagination-nav").html(that.createPagination(xhr.result))
				}else{
					$("#"+that.options.id+" table tbody").html(xhr.view)
					$("#"+that.options.id+" .table-pagination-nav").html(xhr.pagination)
				}
			}
			if(that.options.onData){
				window[that.options.onData](data)
			}
		})
	}

	createPagination(data)
	{
	   var delta = 2;
       var range = [];
       var rangeWithDots = [];
       var l;
       var currentpage = (data.current_page) ? data.current_page : 1
       var total = (data.total) ? data.total : 1
       var perpage = (data.per_page) ? data.per_page : 1
       var prev = (currentpage-1 == 0) ? "" : currentpage-1;
       var next = (currentpage+1 > Math.ceil(total/perpage)) ? "" : currentpage+1;
       if(Math.ceil(total/perpage) == 1){
       		return ``
       }

        //navigation
        var button = ``;
        button +=` <li class="page-item" aria-disabled="true" aria-label="« Sebelumnya">
				      <a class="page-link" aria-hidden="true" data-page="${prev}">‹</a>
				   </li>`;
        var active = '';

        for (let i = 1; i <= Math.ceil(total/perpage); i++) {
            if (i == 1 || i == Math.ceil(total/perpage) || i >= currentpage-delta && i < currentpage+delta+1) {
                range.push(i);
            }
        }

        for (let i of range) {
            if (l) {
                if (i - l === 2) {
                    rangeWithDots.push(l + 1);
                } else if (i - l !== 1) {
                    rangeWithDots.push('...');
                }
            }
            rangeWithDots.push(i);
            l = i;
        }

        $.each(rangeWithDots, function( i, v ) {
          var active = (v == currentpage) ? "active" : '';
          var dataPage = ($.isNumeric(v)) ? `data-page="${v}"` : '';
          button +=`<li class="page-item ${active}"><a class="page-link" ${dataPage}>${v}</a></li>`;
        });

        button +=` <li class="page-item">
				      <a class="page-link" rel="next" aria-label="Berikutnya »" data-page="${next}">›</a>
				    </li>`;
		return `<nav>
  				 <ul class="pagination">${button}</ul>
  				</nav>`
	}
}

(function($) {

    $.fn.extend({
        tableManagement: function(options) {
            options = $.extend( {}, $.tableManagement.defaults, options );
            this.each(function() {
                new $.tableManagement(this,options);
            });
            return this;
        }
    });

    $.tableManagement = function(element, options ) {
    	if(element.id){
    		options.id = element.id
    	}else{
    		let result = 'table-'+(Math.random() + 1).toString(36).substring(7)
    		options.id = result
    		$(element).attr('id',result)
    	}
    	$(element).addClass('table-management-container')
    	if(options.sort != false){
    		if(!options.sortField){
    			var sortField = {}
	    		$.each(options.field,function(field,label){
	    			sortField[field] = true
	    		})
	    		options.sortField = sortField
    		}
    	}

    	var table = new tableManagement(element,options)
    	table.createTable()
    	table.createHeader()

    	table.createFilter()
    	if(options.sort != false){
    		table.renderSortTable()
			$(document).on('click',"#"+options.id+" .sort",function(e){
				e.preventDefault()
				var field = $(this).data('field')
				var sort = $("#"+options.id+" form.filter-box input[name='sort']").val()
				var order = $("#"+options.id+" form.filter-box input[name='order']").val()
				if(field == sort){
					if(order == 'asc'){
						$("#"+options.id+" form.filter-box input[name='order']").val('desc')
					}else{
						$("#"+options.id+" form.filter-box input[name='order']").val('asc')
					}
				}else{
					$("#"+options.id+" form.filter-box input[name='sort']").val(field)
					$("#"+options.id+" form.filter-box input[name='order']").val('asc')
				}
    			$("#"+options.id+" form.filter-box input[name='page']").val(1)
				table.renderSortTable()
				table.getData()
			})
    	}
    	if(typeof options.pagination === 'object'){
    		table.pagination()
    		$(document).on('change',"#"+options.id+" .table-pagination-show select[name='show']",function(e){
    			var show = $(this).val()
    			$("#"+options.id+" .search-field").val('')
    			$("#"+options.id+" form.filter-box input[name='keyword']").val('')
    			$("#"+options.id+" form.filter-box")[0].reset()
    			$("#"+options.id+" form.filter-box input[name='show']").val(show)
    			$("#"+options.id+" form.filter-box input[name='page']").val(1)
    			table.getData()
    		})
    	}
    	if(options.addFilter){
    		table.appendFilter()
    	}
    	$(document).on('click',"#"+options.id+" .search",function(e){
    		e.preventDefault()
    		var keyword = $("#"+options.id+" .search-field").val()
    		$("#"+options.id+" form.filter-box input[name='keyword']").val(keyword)
    		$("#"+options.id+" form.filter-box input[name='page']").val(1)
    		table.getData()
    	})
    	$(document).on('keydown',"#"+options.id+" .search-field",function(e){
    		if (e.keyCode === 13) {
		      e.preventDefault();
		      var keyword = $(this).val()
    		  $("#"+options.id+" form.filter-box input[name='keyword']").val(keyword)
    		  $("#"+options.id+" form.filter-box input[name='page']").val(1)
    		  table.getData()
		    }
    	})
    	$(document).on('change',"#"+options.id+" form.filter-box *",function(e){
    		if($(this).attr('name') != 'page'){
    			$("#"+options.id+" form.filter-box input[name='page']").val(1)
    		}
    	})
    	$(document).on('submit',"#"+options.id+" form.filter-box",function(e){
    		e.preventDefault()
    		table.getData()
    		$("#"+options.id+" .table-management-filter").removeClass('show')
    	})
    	$(document).on('click',"#"+options.id+" .table-pagination .page-link",function(e){
    		e.preventDefault()
    		var page = $(this).data('page')
    		if(!page)
    		{
    			var url = $(this).attr('href')
				if(url){
					url = new URL(url)
					var searchParams = new URLSearchParams(url.search);
					page = searchParams.get('page');
				}
    		}
    		if(page){
    			$("#"+options.id+" form.filter-box input[name='page']").val(page)
    			table.getData()
    		}
    	})
    	$(document).on('click','#'+options.id+" .btn-filter",function(e){
    		$("#"+options.id+" .table-management-filter").toggleClass('show')
    	})
    	if(options.addButton)
    	{
    		table.appendButton()
    	}
    	table.getData()
		if(options.onLoaded){
			window[options.onLoaded](options)
		}
    };

    $.tableManagement.defaults = {
    	url : window.location.href,
		method : 'POST',
		pagination : {
			10 : 10,
			25 : 25,
			50 : 50,
			100 : 100
		},
		filter : false,
		addFilter : false,
		addButton : false,
		sort : true,
		field : {},
		sortField : null,
		labels : {
			search : "Search",
			filter : "Filter"
		},
		row : 'data',
		onLoaded : null,
		onData : null,
    };

})(jQuery);