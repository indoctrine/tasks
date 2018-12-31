/*
                    Task List
    CHANGES:
      28 December 2018  - File created.
                        - Makes AJAX call to markdelete.php to set marked on records.
                        - Updates the buttons and column values in the table.
                        - Colours date fields in red where they are due today or overdue.
*/


$(document).ready(() => {
  today = $.date('Y-m-d');
  $('.due_col').each(function(){
    $(this).html() <= today ? $(this).css('color', 'red') : ''; //Jen loves ternaries
  });

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
            $(this).html('✕');
            $('#row_' + mark).children('.comp_col').text('Yes');
          }
          else if(markresponse == 0){
            $(this).html('✓');
            $('#row_' + mark).children('.comp_col').text('No');
          }
          else{
            $('#output').text(markresponse);
          }
        }
    );
  });

  $('.delete').on('click', function() {
    const del = $(this).val();

    if(confirm("Are you sure you want to delete the following task? \n\n" + $('#row_' + del).children('.desc').text())){
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
      $('#row_' + del).remove();
    }
  });
});
