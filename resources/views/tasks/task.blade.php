{{-- Task card wrapper --}}
<div class="bg-white shadow rounded-xl mb-3"
     x-data="{ open: {{ old('task_id') == $task->id ? 'true' : 'false' }},
                shareOpen: false }">

     <div class="p-3">
        <p class="font-medium mb-2">{{ $task->text }}</p>
        <div class="flex flex-wrap gap-2 mb-2">
            @foreach ($task->tags()->active()->get() as $tag)
                <span class="bg-gray-200 px-2 py-1 rounded text-xs">{{ $tag->getUpperName() }}</span>
            @endforeach
        </div>

        <!-- Assigned Users -->
        <div class="flex flex-col mb-2">
            @if (auth()->check() && auth()->user()->can('shareTask', $task))
                <h3 class="text-xs font-semibold mb-1">{{ __('Sharing Users') }}</h3>
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
                                <button type="submit" 
                                    title="{{ __('Remove user') }}"
                                    class="w-4 h-4 flex items-center justify-center text-xs text-red-600 hover:text-red-800 rounded-full bg-gray-200 hover:bg-gray-300"
                                    onclick="return confirm({{ json_encode(__('Are you sure you want to unshare this task?')) }})">
                                    &minus;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                @if ($task->user->id != auth()->user()->id)
                    <h3 class="text-xs font-semibold mb-1">{{ __('Owner') }}</h3>
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
                <h3 class="text-xs font-semibold mb-1">{{ __('Files') }}</h3>

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
                <form action="{{ route('tasks.upload', $task->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    x-data="{ filename: '' }"
                    class="flex flex-col space-y-2">
                    @csrf

                    <!-- Hidden real input -->
                    <input type="file"
                        id="file{{ $task->id }}"
                        name="file{{ $task->id }}"
                        class="hidden"
                        required
                        @change="filename = $event.target.files.length ? $event.target.files[0].name : ''">

                    <!-- Custom choose button -->
                    <label for="file{{ $task->id }}"
                        class="bg-gray-200 px-3 py-1 rounded text-sm hover:bg-gray-300 cursor-pointer inline-block text-center">
                        {{ __('Choose File') }}
                    </label>

                    <!-- Show selected filename -->
                    <template x-if="filename">
                        <span class="text-xs text-gray-600">
                            {{ __('Selected:') }} <span x-text="filename"></span>
                        </span>
                    </template>

                    <!-- Upload button -->
                    <button type="submit"
                            class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">
                        {{ __('Upload') }}
                    </button>
                </form>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            @if (auth()->check() && auth()->user()->can('editTask', $task))
                @php
                $taskData = [
                    'id' => $task->id,
                    'column_id' => $task->column_id,
                    'column_colour' => $column->colour,
                    'text' => $task->text,
                    'order' => $task->order,
                    'tags' => $task->tags->pluck('id')->toArray(),
                ];
                @endphp
                <button type="button" 
                    class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600"
                    data-task='@json($taskData)'
                    @click="$dispatch('edit-task', { task: JSON.parse($el.dataset.task) })">
                {{ __('Edit') }}
                </button>
            @endif

            @if (auth()->check() && auth()->user()->can('shareTask', $task))
                <button type="button"
                        class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600"
                        @click="shareOpen = !shareOpen">
                    {{ __('Share') }}
                </button>
            @endif

            @if (auth()->check() && auth()->user()->can('deleteTask', $task))
                <form action="{{ route('tasks.delete', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600"
                        title="{{ __('Delete this task') }}"
                        onclick="return confirm({{ json_encode(__('Are you sure you want to delete this task?')) }})">
                        {{ __('Delete') }}
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
                    <button type="submit" class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">
                        {{ __('Save') }}
                    </button>
                </form>
            @endif
        </div>

    </div>
</div>
