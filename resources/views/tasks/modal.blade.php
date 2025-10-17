        {{-- Modal overlay --}}
        <div x-show="open"
                x-transition.opacity
                x-cloak
                @click.self="open = false"
                @keydown.escape.window="open = false"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            
            {{-- Modal card --}}
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-2 border border-gray-200"
                :style="`background-color: ${columnColour}`"
            >
                {{-- Header --}}
                <div class="flex justify-between items-center border-b px-4 py-2">
                    <h3 class="text-lg font-bold text-gray-800" x-text="isEditing ? 'Edit Task' : 'New Task'"></h3>
                    <button type="button"
                            class="text-gray-500 hover:text-gray-800"
                            @click="open = false">
                        &times;
                    </button>
                </div>

                <form :action="isEditing ? '/tasks/' + taskId : '{{ route('tasks.store') }}'" method="POST">
                    @csrf

                    {{-- Body --}}
                    <template x-if="isEditing">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <input type="hidden" name="column_colour" :value="columnColour">
                    <input type="hidden" name="task_id" x-model="taskId">

                    <div class="px-4 py-4">

                        <div class="bg-white shadow rounded-xl p-4 space-y-4">

                            @error('creation_error')
                                <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
                            @enderror

                            @error('user_id')
                                <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
                            @enderror

                            <!-- Text -->
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">Text</label>
                                <textarea name="text" rows="3" maxlength="255"
                                    class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400"
                                    x-model="taskText" required></textarea>
                                @error('text')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Order -->
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">Order</label>
                                <input type="number" name="order" min="0" max="100"
                                    class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400"
                                    x-model="taskOrder" required>
                                @error('order')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Column -->
                            <div class="flex flex-col" x-show="isEditing" x-transition>
                                <label class="text-sm font-medium text-gray-700 mb-1">Column</label>
                                <select name="column_id"
                                    x-model="columnId"
                                    class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400">
                                    @foreach ($columns as $column)
                                        <option value="{{ $column->id }}">{{ $column->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tags -->
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">Tags</label>
                                <select name="tags[]" multiple
                                    size="8"
                                    class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400"
                                    x-model="taskTags">

                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="flex justify-end gap-2 border-t px-4 py-2">
                        <button type="button"
                                class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200"
                                @click="open = false">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            <span x-text="isEditing ? 'Update Task' : 'Create Task'"></span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
