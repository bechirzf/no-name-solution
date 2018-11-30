$(function () {


});

$(document).ready(function(e){
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
		e.preventDefault();
		var param = $(this).attr("href").replace("#","");
		var concept = $(this).text();
		$('.search-panel span#search_label').text(concept);
		$('.input-group #search_param').val(param);
  	});

    $('.lbcx-select select').selectpicker({
      size: 8
    });
      $('.tip').tooltip();
});


$(function () {
    $('#birthday').datetimepicker({
      format: 'YYYY-MM-DD',
      viewMode: 'years'
    });
     $('#datefrom, #datefrom2').datetimepicker({
       format: 'YYYY-MM-DD'
     });
     $('#datefrom3').datetimepicker({
       format: 'YYYY-MM-DD LT'
     });
     $('#dateto, #dateto2').datetimepicker({
         useCurrent: false,
         format: 'YYYY-MM-DD'
     });
     $('#dateto3').datetimepicker({
       format: 'YYYY-MM-DD LT'
     });
     $("#datefrom, #datefrom2").on("dp.change", function (e) {
         $('#dateto, #dateto2').data("DateTimePicker").minDate(e.date);
     });
     $("#dateto, #dateto2").on("dp.change", function (e) {
         $('#datefrom, #datefrom2').data("DateTimePicker").maxDate(e.date);
     });


     $('.withscroll').jScrollPane({autoReinitialise: true});

     var topScroll = $('.withscroll').offset().left,
         endScroll = topScroll + $('.withscroll').width(),
         f = ($('.withscroll').width() / $('.withscroll .jspPane').width())*5 ,
         selection = false,
         startX;

     $('.withscroll .jspPane').mousemove(function(e){
         var mX;
         var delta = startX - e.pageX;
         if((e.pageX < endScroll && (mX = ((e.pageX - endScroll + 200)/f)) > 0) ||
            (e.pageX > topScroll && (mX = (e.pageX - (topScroll + 200))/f) < 0)){
               if(selection && (delta > 10 || delta < -10) )$('.withscroll').data('jsp').scrollByX(mX, false) ;
         }
     })

     $('.withscroll').mousedown(function(e){startX = e.pageX; selection = true ;})
     $('.withscroll').mouseup(function(){selection = false ;})
 });



$(document).ready(function() {
  $('#text-editor').summernote({
    height: 400,
    maxHeight: 400,
    minHeight: 400
  });
  $('#text-editor2').summernote({
    height: 400,
    maxHeight: 400,
    minHeight: 400
  });


  var fixHelper = function(e, ui) {
      ui.children().each(function() {
          $(this).width($(this).width());
      });
      return ui;
  };

  $(".table-sortable .withAccount").sortable({
      helper: fixHelper
      cancel: "nomove"
  }).disableSelection();


  var text_max = 200;
    $('.charactercount').html(text_max + ' characters remaining');

    $('#textarea').keyup(function() {
      var text_length = $('#textarea').val().length;
      var text_remaining = text_max - text_length;

      $('.charactercount').html(text_remaining + ' characters remaining');
    });

});
$(document).ready(function() {
  $(".lbcx-edit").click(function(){
    if ($('.collapse-box').hasClass('opened')) {
      $('.collapse-box').removeClass('opened')
      $('.collapse-content').hide('slow');
      $(".settings .lbcx-edit").show();
    }
    $(this).parent().next(".collapse-content").show('slow');
    $(this).hide();
    $(this).parent().parent('.collapse-box').addClass('opened');


  });
  $(".cancel").click(function(){
    $('.collapse-content').hide('slow');
    $('.collapse-box').removeClass('opened');
    $(".lbcx-edit").show();
  });

  $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).parent().siblings().removeClass('open');
    $(this).parent().toggleClass('open');
  });
});
