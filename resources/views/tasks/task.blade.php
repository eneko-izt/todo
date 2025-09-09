<div border="1" class="p-2 mb-2 bg-light text-dark rounded">
    @if (auth()->check() && auth()->user()->can('deleteTask', $task))

    <form action="{{ route('tasks.delete', $task->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-primary float-right" title="Remove this task"
            onclick="return confirm('Are you sure you want to remove this task?')">X
        </button>
    </form>

    @endif

    <div>
        <p>{{ $task->id }}: {{ $task->text }}</p>

        <hr>

        <div>
            @foreach ($task->tags()->active()->get() as $tag)
                @if ($loop->first)
                    <span class="badge badge-primary">Tags:</span>
                @else (! $loop->first)
                    ,
                @endif {{ $tag->getUpperName() }}
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-center">
        @if (auth()->check() && auth()->user()->can('editTask', $task))

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mt-3" data-toggle="modal"
                data-target="#staticBackdrop-{{ $task->id }}">
                Edit
            </button>
        
        @endif
    </div>

    <hr>

    <div>
        @if (auth()->check() && auth()->user()->can('uploadFile', $task))
            <span class="badge badge-primary">Uploaded files:</span>

            @foreach ($task->files as $file)
                <br><a href="{{ route('tasks.download', $file->id) }}">{{ $file->filename }}</a>
            @endforeach
        
            <div class="d-flex justify-content-center mt-3">
                <form action="{{ route('tasks.upload', $task->id) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column align-items-center">
                    @csrf
                    @method('POST')
                    <input type="file" name="file" required class="form-control-file mb-2">
                    <button type="submit" class="btn btn-primary">
                        Upload
                    </button>
                </form>
            </div>

        @endif
    </div>

    <hr>

    <div>
        @if (auth()->check() && auth()->user()->can('shareTask', $task))

            @foreach ($task->sharingUsers()->active()->get() as $user)
                @if ($loop->first)
                    <div class="badge badge-primary">Shared with:</div>
                @endif
                <span class="badge badge-secondary">
                    {{ $user->name }}
                    <form action="{{ route('tasks.unshare', [$task->id, $user->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-close bigger-close" title="Remove this user"
                            onclick="return confirm('Are you sure you want to unshare this task?')">
                        </button>
                    </form>
                </span>
            @endforeach

            <div class="d-flex justify-content-center mt-3">
                <!-- Share tasks with other users -->
                <button type="button" id="share-btn-{{ $task->id }}" class="btn btn-primary">
                    Share
                </button>
            </div>

        @else

            @if ($task->user->id != auth()->user()->id)
                <span class="badge badge-primary">Owner:</span>
                <span class="badge badge-secondary">{{ $task->user->name }}</span>
            @endif
        
        @endif
    </div>

    <div id="share-content-{{ $task->id }}" style="display: none;">

        @if (auth()->check() && auth()->user()->can('shareTask', $task))

            <form action="{{ route('tasks.share', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <label for="user">Choose a user:</label>
                <select name="userid" id="user-{{ $task->id }}" style="max-width: 200px;">
                    @foreach ($task->shareableUsers() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary" title="Share task with user">Save
                </button>
            </form>

        @endif

    </div>

    <!-- Modal -->
    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="modal fade" id="staticBackdrop-{{ $task->id }}" data-backdrop="static"
            data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel-{{ $task->id }}">Edit
                            Task</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('tasks.form', ['task' => $task])
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-danger" href="">Cancel</a>
                        <button type="submit" class="btn btn-primary is-link">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@if ($errors->any() && session('modal_id') === 'staticBackdrop-' . $task->id)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#staticBackdrop-{{ $task->id }}').modal('show');
        });
</script>
@endif

<script>
    document.getElementById('share-btn-{{ $task->id }}').addEventListener('click', function() {
        console.log('Share button clicked');
        const content = document.getElementById('share-content-{{ $task->id }}');
        content.style.display = (content.style.display === 'none' || content.style.display === '') 
            ? 'block' 
            : 'none';
    });
</script>
