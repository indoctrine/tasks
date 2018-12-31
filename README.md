# Task List

Stores a list of tasks and allows completion. An exercise in Object Oriented Programming in PHP.


## CHANGELOG
```
  tasks.php:
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

  tasks.js:
    28 December 2018    - File created.
                        - Makes AJAX call to markdelete.php to set marked on records.
                        - Updates the buttons and column values in the table.
                        - Colours date fields in red where they are due today or overdue.
    31 December 2018    - Added $.post for delete button, which includes confirmation of the delete
                          and resultant removal of the table row.

  markdelete.php:
    28 December 2018    - File created.
    31 December 2018    - Added handler for deletion, which calls DeleteTask() in tasks.php
```
