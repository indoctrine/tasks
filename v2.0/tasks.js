$(document).ready(() => {
  $('.mark').on('click', function() {
    const mark = $(this).val();

    $.post(
        'markdelete.php',
        {
          'submit': true,
          'mark': mark
        },
        response => {
            $('#output').html(response);
        }
    );
  });
});
