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
</div>