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
          'mark': mark
        },
        response => {
          if(response == 1){
            $(this).html('✕');
            $('#row_' + mark).children('.comp_col').html('Yes');
          }
          else if(response == 0){
            $(this).html('✓');
            $('#row_' + mark).children('.comp_col').html('No');
          }
          else{
            $('#output').html(response);
          }
        }
    );
  });
});
