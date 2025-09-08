<?php

namespace App\Http\Controllers;

use App\File;
use App\Task;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($id)
    {
        $task = Task::findOrFail($id);

        // $nameValidations = ['required', File::max(5 * 1024)];
        // request()->validate(['file' => $nameValidations]);

        $file = request('file');
        $name = $file->hashName();

        $upload = Storage::put("{$name}", $file);
        if ($upload)
        {
            $fileModel = new File();
            $fileModel->filename = $file->getClientOriginalName();
            $fileModel->path = $name;
            $fileModel->size = $file->getSize();
            $fileModel->extension = $file->getClientMimeType();
            $fileModel->task_id = $task->id;
            $fileModel->save();

            return back()->with('success', 'File uploaded successfully')->with('file', $name);
        }
    }
}
