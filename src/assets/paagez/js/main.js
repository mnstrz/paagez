$(document).ready(function()
{
  $('*[data-bs-toggle="tooltip"]').tooltip()
})

$(document).on('click','.confirm',function(e)
{
  e.preventDefault()
  var title = $(this).data('swal-title')
  var text = $(this).data('swal-text')
  var icon = $(this).data('swal-icon')??''
  var confirm_text = $(this).data('swal-confirm-text')??'Submit'
  var cancel_text = $(this).data('swal-cancel-text')??'Cancel'
  var confirm_color = $(this).data('swal-confirm-text')??'Submit'
  var cancel_color = $(this).data('swal-cancel-text')??'Cancel'
  var url = $(this).attr('href')??null
  var form = $(this).data("form")??null
  Swal.fire({
    title: title,
    text : text,
    icon : icon,
    showCancelButton: true,
    confirmButtonText: confirm_text,
    confirmButtonColor: confirm_color,
    cancelButtonText: cancel_text,
    cancelButtonColor: cancel_color,
  }).then((result) => {
    if (result.isConfirmed) {
      if(url)
      {
        window.location.href = url
      }
      if($(form).length > 0)
      {
        $(form).submit()
      }
    }
  });
})

$(document).on('click',"*[data-filter-target]",function(e)
{
  e.preventDefault()
  var target = $(this).data('filter-target')
  $(target).toggleClass('d-none')
})


$(document).on('click',".btn-floating-box",function()
{
  $(this).prev('.floating-box').addClass('show')
})

$(document).on('click',".floating-box .btn-floating",function()
{
  $(this).parent('.floating-box').removeClass('show')
})

$(document).on('click',".btn-sidebar",function()
{
  $(this).siblings('.sidebar').removeClass('close')
})

$(document).on('click',".btn-sidebar-close",function()
{
  $(this).parent('.sidebar').addClass('close')
})

function uniqid() {
  var timestamp = new Date().getTime();
  var randomString = Math.random().toString(36).substring(2);
  var uniqueId = timestamp + '-' + randomString;
  return uniqueId;
}

function showAlert(params={}){
  if(!params.type)
  {
    params.type = 'success'
  }
  if(!params.message)
  {
    params.message = 'Alert'
  }
  if(!params.autoclose)
  {
    params.autoclose = false
  }
  $("body .alert-pop").remove()
  $('body').append(`<div class="alert alert-pop alert-${params.type} alert-pill alert-dismissible fade show animate__animated animate__bounceInRight" role="alert">
                    ${params.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>`)
  if(params.autoclose)
  {
    setTimeout(function()
    {
      $("body .alert-pop").remove()
    },params.autoclose)
  }
}

function closeAlert()
{
  $("body .alert-pop").remove()
}


$(document).on('mouseenter','nav.sidebar li a.nav-dropdown',function(e)
{
   var top = $(this).offset().top
   $(this).siblings('ul').css({top:top})
})


function waitElement(selector)
{
    return new Promise(resolve => {
        if (document.querySelector(selector)) {
            return resolve(document.querySelector(selector));
        }
        const observer = new MutationObserver(mutations => {
            if (document.querySelector(selector)) {
                observer.disconnect();
                resolve(document.querySelector(selector));
            }
        });
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

var lastScrollTop = 0;

$(document).scroll(function(event){
   var content = $(this).scrollTop()
   if (content > lastScrollTop){
       if(content > 100)
       {
          $(".navbar").addClass('close')
       }else{
          $(".navbar").removeClass('close')
       }
   } else {
       $(".navbar").removeClass('close')
   }
   lastScrollTop = content
});