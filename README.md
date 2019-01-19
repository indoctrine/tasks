# Task List

Stores a list of tasks and allows completion. An exercise in Object Oriented Programming in PHP.


## CHANGELOG
```
  CHANGELOG tasks.php:
    20 October 2018     - File created.
    25-27 November 2018 - Wrote method to push all data into objects.
                        - Learnt about PDO::FETCH_CLASS
    28 November 2018    - Array is now indexed by task_id
    6 December 2018     - renderlist() method outputs entire list of tasks
    8 December 2018     - Changed renderlist() to two render() methods so
                          the task object will render itself.
    28 December 2018    - Can now mark tasks as complete. This is done through
                          an AJAX call from tasks.js which initiates markdelete.php.
    29 December 2018    - Updated database connector to use a global variable.
                          It's okay, my art teacher said it was fine.
    31 December 2018    - Created DeleteTask() method to handle deletion of tasks.
                        - Learned that you cannot delete the current task, so that's cool, I guess.
                        - Render() will now check if there is anything in the array before
                          pooping out a table. Good boy, Render().
    3 January 2019      - Default output order is now due_date descending.
                        - Spans for the headers to make the sorting thing work.
    16 January 2019     - Moved ValidatePost method up to the TaskList class as I realised that there
                          won't be any task to call it on yet.
                        - Separated ValidatePost out into separate validation methods that are called by
                          ValidatePost in order to compartmentalise things more and make it easier to add
                          fields in future.
                        - Added additional checks on the date validation, as it was relying too heavily on
                          correct input by the user.

  CHANGELOG tasks.js:
    28 December 2018    - File created.
                        - Makes AJAX call to markdelete.php to set marked on records.
                        - Updates the buttons and column values in the table.
                        - Colours date fields in red where they are due today or overdue.
    31 December 2018    - Added $.post for delete button, which includes confirmation of the delete
                          and resultant removal of the table row.
    3 January 2019      - Date field now distinguishes between whether the task is completed or not
                          and colours appropriately.
                        - Fancy red background to highlight which task you just clicked the delete button on.
                        - The above removed the need to print the task name in the confirm box and made the
                          code slightly clearer.
                        - Sorting using an external library. May write my own at some point.
    16 January 2019     - Added form event handler after fighting with caching.

  CHANGELOG markdelete.php:
    28 December 2018    - File created.
    31 December 2018    - Added handler for deletion, which calls DeleteTask() in tasks.php

  CHANGELOG addtask.php:
    16 January 2019     - File created.
```
