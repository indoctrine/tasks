$(document).ready(() => {
  if($('*[id^="row_"]').length > 0){
    $('#taskoutput').show();
  }

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

  $('#addtask').on('submit', function() {
    const pri = $('select[name = priority]').val();
    const dd = $('input[name = duedate]').val();
    const desc = $('textarea[name = description]').val();
    $('#taskoutput').show();

    $.post(
      'addtask.php',
      {
        'submittask': true,
        'priority': pri,
        'due_date': dd,
        'description': desc
      },
      response => {
        console.log(response);
        if($('#taskoutput').length){
          var taskdata = JSON.parse(response);
          var id = taskdata[0].task_id;
          var ddate = taskdata[0].due_date;
          var priority = taskdata[0].priority;
          var desc = taskdata[0].description;
          var comp = taskdata[0].completed == 1 ? 'Yes' : 'No';
          var tick = taskdata[0].completed == 1 ? '✕' : '✓';

          var markup = '<tr id="row_' + id + '">' +
            '<td>' + id + '</td>' +
            '<td class="due_col">' + ddate + '</td>' +
            '<td>' + priority + '</td>' +
            '<td class="desc">' + desc + '</td>' +
            '<td class="comp_col">' + comp + '</td>' +
            '<td><button class="mark" value="' + id + '">' + tick + '</button></td>' +
            '<td><button class="delete" value="' + id + '">X</button></td>';

          $('#taskoutput tbody').prepend(markup);
          $('.mark');
          $('#output').text('Task ' + id + ' successfully added.')
          ddformat();
        }
      }
    )
    return false;
  });

  $('body').on('click', '.mark', function() {
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

  $('body').on('click', '.delete', function() {
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
          if($('*[id^="row_"]').length == 0){
            $('#taskoutput').hide();
          }
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
});
