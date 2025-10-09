{{-- Task card wrapper --}}
<div class="bg-white shadow rounded-xl mb-3"
     x-data="{ open: {{ old('task_id') == $task->id ? 'true' : 'false' }},
                shareOpen: false }">

     <div class="p-3">
        <p class="font-medium mb-2">{{ $task->id }}: {{ $task->text }}</p>
        <div class="flex flex-wrap gap-2 mb-2">
            @foreach ($task->tags()->active()->get() as $tag)
                <span class="bg-gray-200 px-2 py-1 rounded text-xs">{{ $tag->getUpperName() }}</span>
            @endforeach
        </div>

        <!-- Assigned Users -->
        <div class="flex flex-col mb-2">
            @if (auth()->check() && auth()->user()->can('shareTask', $task))
                <h3 class="text-xs font-semibold mb-1">Assigned Users</h3>
                <div class="flex flex-wrap gap-1">
                    @foreach ($task->sharingUsers()->active()->get() as $user)
                        <div class="flex items-center gap-1 bg-gray-100 px-1 py-0 rounded shadow-sm">
                            <span class="bg-gray-400 text-white w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-semibold leading-none">
                                {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                            </span>
                            <span class="text-[11px] font-medium text-gray-700">{{ $user->name }}</span>
                            <!-- Remove button -->
                            <form action="{{ route('tasks.unshare', [$task->id, $user->id]) }}" method="POST" class="inline-block ml-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Remove user"
                                    class="w-4 h-4 flex items-center justify-center text-xs text-red-600 hover:text-red-800 rounded-full bg-gray-200 hover:bg-gray-300"
                                    onclick="return confirm('Are you sure you want to unshare this task?')">
                                    &minus;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                @if ($task->user->id != auth()->user()->id)
                    <h3 class="text-xs font-semibold mb-1">Owner</h3>
                    <div class="flex flex-wrap gap-1">
                        <div class="flex items-center gap-1 bg-gray-100 px-1 py-0 rounded shadow-sm">
                            <span class="bg-gray-400 text-white w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-semibold leading-none">
                                {{ Str::upper(Str::substr($task->user->name, 0, 1)) }}
                            </span>
                            <span class="text-[11px] font-medium text-gray-700">{{ $task->user->name }}</span>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- File Upload Section -->
        <div class="mb-2">
            @if (auth()->check() && auth()->user()->can('uploadFile', $task))
                <h3 class="text-xs font-semibold mb-1">Files</h3>

                <div class="flex flex-wrap gap-1 mb-2">
                    @foreach ($task->files as $file)
                        <div class="flex items-center gap-1 bg-gray-100 px-1 py-0 rounded shadow-sm">
                            <span class="bg-gray-400 text-white w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-bold leading-none">
                                📎
                            </span>
                            <a href="{{ route('tasks.download', $file->id) }}" class="text-[11px] font-medium text-gray-700 hover:underline">
                                {{ $file->filename }}
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Upload Form -->
                <form action="{{ route('tasks.upload', $task->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col space-y-2">
                    @csrf
                    @method('POST')
                    <input type="file" name="file{{ $task->id }}" required class="border px-2 py-1 rounded text-xs">
                    <button type="submit" class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">
                        Upload
                    </button>
                </form>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            @if (auth()->check() && auth()->user()->can('editTask', $task))
                 <button type="button" 
                        class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600"
                        @click="open = true">
                    Edit
                </button>
            @endif

            @if (auth()->check() && auth()->user()->can('shareTask', $task))
                <button type="button"
                        class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600"
                        @click="shareOpen = !shareOpen">
                    Share
                </button>
            @endif

            @if (auth()->check() && auth()->user()->can('deleteTask', $task))
                <form action="{{ route('tasks.delete', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600"
                        title="Remove this task"
                        onclick="return confirm('Are you sure you want to remove this task?')">
                        Delete
                    </button>
                </form>
            @endif

        </div>

        <!-- Share User Section (Initially Hidden) -->
        <div x-show="shareOpen" x-cloak class="bg-white shadow rounded-xl p-3 mt-2">
            @if (auth()->check() && auth()->user()->can('shareTask', $task))
                <form action="{{ route('tasks.share', $task->id) }}" method="POST" class="flex flex-col space-y-2">
                    @csrf
                    @method('PATCH')
                    <label for="user-{{ $task->id }}" class="text-sm font-medium text-gray-700">Choose a user:</label>
                    <select name="userid" id="user-{{ $task->id }}" class="border rounded p-1 text-sm max-w-[200px]">
                        @foreach ($task->shareableUsers() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600" title="Share task with user">
                        Save
                    </button>
                </form>
            @endif
        </div>

        <!-- Edit Modal -->
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Modal overlay -->
            <div x-show="open"
                x-transition.opacity
                x-cloak
                @click.self="open = false"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">


                <!-- Modal card (like task card) -->
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-2 border border-gray-200"
                    style="background-color: {{ $column->colour }};">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b px-4 py-2">
                        <h3 class="text-lg font-bold text-gray-800">Edit Task</h3>
                        <button type="button" class="text-gray-500 hover:text-gray-800" @click="open = false">&times;</button>
                    </div>

                    <!-- Body -->
                    <div class="px-4 py-4">
                        @include('tasks.form', ['task' => $task])
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 border-t px-4 py-2">
                        <button type="button" 
                                class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200"
                                @click="open = false">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            Update Task
                        </button>
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>
