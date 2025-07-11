<div border="1" class="p-2 mb-2 bg-light text-dark rounded">
    <form action="{{ route('tasks.delete', $task->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button 
            type="submit" 
            class="btn btn-primary float-right"
            title="Remove this task"
            onclick="return confirm('Are you sure you want to remove this task?')"
        >X
        </button>
    </form>
    <p>
        {{ $task->id }}: {{ $task->text }}
    </p>
    <hr>
    <p>
        @foreach ( $task->tags()->where('active', true)->get() as $tag)
            @if (!$loop->first) , @endif {{ $tag->getUpperName() }}
        @endforeach
    </p>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop-{{ $task->id }}">
        Edit
    </button>

    <!-- Modal -->
    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
    @csrf
    @method('PATCH')
        

    <div class="modal fade" id="staticBackdrop-{{ $task->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel-{{ $task->id }}">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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