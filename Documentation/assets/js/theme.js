/* =================================================================
* Documentation JS
* 
* Author:      Themetorium
* URL:         http://themetorium.net
*
================================================================= */



/* =================================================================
   Active Menu Item on Page Scroll
   Source: https://stackoverflow.com/a/38379108/2785140
================================================================= */

$(function () {
   $(document).on("scroll", onScroll);
    
   //smoothscroll
   $('#sidebar-nav > li > a').on('click', function (e) {
      e.preventDefault();
      $(document).off("scroll");
        
      $('#sidebar-nav > li > a').each(function () {
         $(this).removeClass('active');
      })
      $(this).addClass('active');
      
      var target = this.hash,
         menu = target;
      $target = $(target);
      $('html, body').stop().animate({
         'scrollTop': $target.offset().top + 0
      }, 500, 'swing', function () {
         window.location.hash = target;
         $(document).on("scroll", onScroll);
      });
   });
});

function onScroll(event){
   var scrollPos = $(document).scrollTop();
   $('#sidebar-nav > li > a').each(function () {
      var currLink = $(this);
      var refElement = $(currLink.attr("href"));
      if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
         $('#sidebar-nav > li > a').removeClass("active");
         currLink.addClass("active");
      }
      else{
         currLink.removeClass("active");
      }
   });
}