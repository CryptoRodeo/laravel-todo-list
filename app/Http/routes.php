<?php

use App\Task;
use Illuminate\Http\Request;

/**
 * Display All Tasks
 */
Route::get('/', function () {
    /**
     * all HTML templates are stored in the resources/views directory\
     * 
     * the view helper to return one of these templates from our route:
     */
    $tasks = Task::orderBy('created_at', 'asc')->get();

    /**
     * 
     * The view function accepts a second argument which is an array of data that will be made available to the view,
     where each key in the array will become a variable within the view:
     */
    return view('tasks', [
        'tasks' => $tasks
    ]);
});

/**
 * Add A New Task
 */
Route::post('/task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        /**
         * the name field required and state that it must contain less than 255 characters. If the validation fails, we will redirect the user back to the / URL, as well as flash the old input and errors into the session:
         */
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator); /**The ->withErrors($validator) call will flash the errors from the given validator instance into the session so that they can be accessed via the $errors variable in our view. */
    }

    // Create The Task...
    $task = new Task;
    $task->name = $request->name;
    $task->save();

    return redirect('/');
});
/**
 * Delete An Existing Task
 */
Route::delete('/task/{id}', function ($id) {
    Task::findOrFail($id)->delete();

    return redirect('/');
});