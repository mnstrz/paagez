var updates = [];
var all = false;

$(document).ready(function()
{
	index = 0;
	$("tr[data-module]").each(function()
	{
		var mod = $(this).data('module')
		updates.push({
			id : mod,
			index : index
		})
		index++;
	})
})

$(document).on('click',"button[data-module]",function(e)
{
	var module_id = $(this).data('module')
	var index = $(this).data('index')
	updatePackage(module_id,index)
})

function updatePackage(module_id,index)
{
	$.ajax({
		url : $("#url_update_package").val(),
		type : 'POST',
		async : true,
		data : {
			module_id : module_id
		},
		beforeSend: function()
		{
			if($("tr#module_"+index).length == 0)
			{
				$(`<tr id="module_${index}">
						<td colspan="3" class="fs-sm"></td>
					</tr>`).insertAfter("tr[data-index='"+index+"']")
			}
			$("tr#module_"+index+" td").append(`<p><i class="fa-solid fa-spinner fa-spin"></i> Updating module package ...</p>`)
			$("button[data-module='"+module_id+"']").html(`<i class="fa-duotone fa-spinner-third fa-spin"></i>`)
		},
		error: function(xhr)
		{
			$("tr#module_"+index+" td").append(`<p class="text-danger">${xhr.responseJSON.message}</p>`)
			$("button[data-module='"+module_id+"']").html($("button[data-module='"+module_id+"']").data('label'))
			$("tr#module_"+index+" td p i").remove()
		},
		success: function(xhr)
		{
			$("tr#module_"+index+" td").append(`<p class="text-success">${xhr.message}</p>`)
			$("tr#module_"+index+" td p i").remove()
			updateDatabase(module_id,index)
		}
	})
}

function updateDatabase(module_id,index)
{
	$.ajax({
		url : $("#url_update_database").val(),
		type : 'POST',
		async : true,
		data : {
			module_id : module_id
		},
		beforeSend: function()
		{
			if($("tr#module_"+index).length == 0)
			{
				$(`<tr id="module_${index}">
						<td colspan="3" class="fs-sm"></td>
					</tr>`).insertAfter("tr[data-index='"+index+"']")
			}
			$("tr#module_"+index+" td").append(`<p><i class="fa-solid fa-spinner fa-spin"></i> Updating module database ...</p>`)
			$("button[data-module='"+module_id+"']").attr('disabled','disabled')
			$("button[data-module='"+module_id+"']").html(`<i class="fa-duotone fa-spinner-third fa-spin"></i>`)
		},
		error: function(xhr)
		{
			$("tr#module_"+index+" td").append(`<p class="text-danger">${xhr.responseJSON.message}</p>`)
			$("button[data-module='"+module_id+"']").html($("button[data-module='"+module_id+"']").data('label'))
			$("tr#module_"+index+" td p i").remove()
		},
		success: function(xhr)
		{
			$("tr#module_"+index+" td").append(`<p class="text-success">${xhr.message}</p>`)
			$("tr#module_"+index+" td p i").remove()
			updateModule(module_id,index)
		}
	})
}

function updateModule(module_id,index)
{
	$.ajax({
		url : $("#url_update_module").val(),
		type : 'POST',
		async : true,
		data : {
			module_id : module_id
		},
		beforeSend: function()
		{
			if($("tr#module_"+index).length == 0)
			{
				$(`<tr id="module_${index}">
						<td colspan="3" class="fs-sm"></td>
					</tr>`).insertAfter("tr[data-index='"+index+"']")
			}
			$("tr#module_"+index+" td").append(`<p><i class="fa-solid fa-spinner fa-spin"></i> Updating module ...</p>`)
			$("button[data-module='"+module_id+"']").html(`<i class="fa-duotone fa-spinner-third fa-spin"></i>`)
		},
		error: function(xhr)
		{
			$("tr#module_"+index+" td").append(`<p class="text-danger">${xhr.responseJSON.message}</p>`)
			$("button[data-module='"+module_id+"']").html($("button[data-module='"+module_id+"']").data('label'))
			$("tr#module_"+index+" td p i").remove()
		},
		success: function(xhr)
		{
			$("tr#module_"+index+" td").append(`<p class="text-success">${xhr.message}</p>`)
			$("button[data-module='"+module_id+"']").hide()
			$("tr#module_"+index+" td p i").remove()
			if(all === true)
			{
				if($("button[data-index='"+(index+1)+"']").length > 0)
				{
					$("button[data-index='"+(index+1)+"']").removeAttr('disabled')
					$("button[data-index='"+(index+1)+"']").trigger('click')
				}else{
					$("#success-message").show()
					$("#install-update i").remove()
					$("#install-update").removeAttr('disabled')
				}
			}
		}
	})
}

$(document).on('click','#install-update',function()
{
	all = true
	$(this).attr('disabled','disabled')
	$(this).prepend(`<i class="fa-duotone fa-spinner-third fa-spin me-1"></i>`)
	$("button[data-index='0']").focus()
	$("button[data-index='0']").trigger('click')
	$("button[data-index]").attr('disabled','disabled')
})