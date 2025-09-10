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

        $this->authorize('uploadFile', $task);

        $nameValidations = ['required', 'file', 'max:5242880'];
        request()->validate(['file'  . $task->id => $nameValidations]);

        $file = request('file');
        $name = $file->hashName();
        $path = $file->store("uploads");

        if ($path)
        {
            $fileModel = new File();
            $fileModel->filename = $file->getClientOriginalName();
            $fileModel->path = $path;
            $fileModel->size = $file->getSize();
            $fileModel->extension = $file->getClientMimeType();
            $fileModel->task_id = $task->id;
            $fileModel->save();

            return back();
        }
    }

    public function download($id)
    {
        $file = File::with('task')->findOrFail($id);

        $this->authorize('uploadFile', $file->task);

        return Storage::download($file->path, $file->filename);
    }
}
