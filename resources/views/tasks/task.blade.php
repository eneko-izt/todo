<div border="1" class="p-2 mb-2 bg-light text-dark rounded">
    <form action="{{ route('tasks.activate', ['id' => $task->id, 'value' => '0'] ) }}" method="POST">
        @csrf
        @method('PATCH')
        <button 
            type="submit" 
            class="btn btn-primary float-right"
            onclick="return confirm('Are you sure you want to remove this task?')"
        >X
        </button>
    </form>
    <p>
        {{ $task->id }}: {{ $task->text }}
    </p>
</div>