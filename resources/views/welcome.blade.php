<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task App</title>
    
    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Basic Styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .task-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        input[type="text"] {
            width: calc(100% - 90px);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        button:hover {
            background-color: #218838;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        li:last-child {
            border-bottom: none;
        }
        .delete-task {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-task:hover {
            background-color: #c82333;
        }
        .completed-task {
            text-decoration: line-through;
            color: #888;
        }
        #show-all {
            margin-top: 20px;
            background-color: #007bff;
        }
        #show-all:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="task-container">
    <h1>Task List</h1>
    <div>
        <input type="text" id="task-input" placeholder="Enter Task" />
        <button id="add-task">Add Task</button>
    </div>

    <ul id="task-list"></ul>

    <button id="show-all">Show All Tasks</button>
</div>

<script>
    $(document).ready(function() {
        // Setup CSRF token for jQuery AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load all tasks on page load
        loadTasks();

        // Load all tasks function
        function loadTasks() {
            $.get('/tasks', function(tasks) {
                $('#task-list').empty();
                tasks.forEach(function(task) {
                    addTaskToList(task);
                });
            });
        }

        // Add new task
        $('#add-task').click(function() {
            var taskName = $('#task-input').val();
            if (taskName === '') {
                return alert('Task name is required');
            }

            $.post('/tasks', {name: taskName}, function(task) {
                addTaskToList(task);
                $('#task-input').val('');
            }).fail(function(xhr) {
                alert(xhr.responseJSON.message); // Handle duplicates or errors
            });
        });

        // Toggle task completion
        $(document).on('change', '.complete-task', function() {
            var taskId = $(this).data('id');
            $.ajax({
                url: '/tasks/' + taskId + '/toggle',
                type: 'PATCH',
                success: function(task) {
                    loadTasks(); // Reload tasks to reflect changes
                }
            });
        });

        // Delete task with confirmation
        $(document).on('click', '.delete-task', function() {
            if (confirm('Are you sure to delete this task?')) {
                var taskId = $(this).data('id');
                $.ajax({
                    url: '/tasks/' + taskId,
                    type: 'DELETE',
                    success: function() {
                        loadTasks();
                    }
                });
            }
        });

        // Show all tasks (completed and non-completed)
        $('#show-all').click(function() {
            loadTasks();
        });

        // Helper function to add a task to the task list
        function addTaskToList(task) {
            var taskHtml = `
                <li>
                    <input type="checkbox" class="complete-task" data-id="${task.id}" ${task.completed ? 'checked' : ''} />
                    <span class="${task.completed ? 'completed-task' : ''}">${task.name}</span>
                    <button class="delete-task" data-id="${task.id}">Delete</button>
                </li>
            `;
            $('#task-list').append(taskHtml);
        }
    });
</script>

</body>
</html>
