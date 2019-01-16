$(document).ready(() => {
  today = $.date('Y-m-d');

  function ddformat(){
    $('.due_col').each(function(){
      if($.trim($(this).siblings('.comp_col').text()) == "No" && $(this).text() <= today){
        $(this).css('color', 'red');
      }
      else if($.trim($(this).siblings('.comp_col').text()) == "Yes"){
        $(this).removeAttr('style');
      }
    });
  }
  ddformat();

  $('.mark').on('click', function() {
    const mark = $(this).val();

    $.post(
        'markdelete.php',
        {
          'submit': true,
          'mark': mark,
          'delete': null
        },
        markresponse => {
          if(markresponse == 1){
            $(this).text('✕');
            $('#row_' + mark).children('.comp_col').text('Yes');
            $('#output').text(markresponse);
            ddformat();
          }
          else if(markresponse == 0){
            $(this).text('✓');
            $('#row_' + mark).children('.comp_col').text('No');
            ddformat();
          }
          else{
            $('#output').text(markresponse);
          }
        }
    );
  });

  $('.delete').on('click', function() {
    const del = $(this).val();
    $('#row_' + del).toggleClass('deleteme');

    setTimeout(function(){ //Wait a short period to prompt.
      if(confirm("Are you sure you want to delete this task?")){
        $.post(
          'markdelete.php',
          {
            'submit': true,
            'mark': null,
            'delete': del
          },
          delresponse => {
            $('#output').text(delresponse);
          }
        )
        $('#row_' + del).fadeOut("normal", function() {
          $('#row_' + del).remove();
        });
      }
      else{
        $('#row_' + del).removeAttr('class');
      }
    }, 150);
  });

  $(function(){
    $('#taskoutput').tablesorter();
  });

  $('.addbutton').on('click', function() {
    $('#addtask').toggle();
  });

  $('#addtask').on('submit', function() {
    const pri = $('select[name = priority]').val();
    const dd = $('input[name = duedate]').val();
    const desc = $('textarea[name = description]').val();

    $.post(
      'addtask.php',
      {
        'submittask': true,
        'priority': pri,
        'due_date': dd,
        'description': desc
      },
      response => {
        $('#output').text(response);
      }
    )

    console.log(pri, dd, desc);
    return false;
  });
});
