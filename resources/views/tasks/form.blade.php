<div class="bg-white shadow rounded-xl p-4 space-y-4">

    <!-- Text -->
    <div class="flex flex-col">
        <label for="text{{ $task->id }}" class="text-sm font-medium text-gray-700 mb-1">{{ __('Text') }}</label>
        <textarea name="text{{ $task->id }}" id="text{{ $task->id }}" rows="3" maxlength="255"
            class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400 @error('text' . $task->id) border-red-500 @enderror"
            required>{{ old("text{$task->id}", $task->text ?? '') }}</textarea>
        @error('text' . $task->id)
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Order -->
    <div class="flex flex-col">
        <label for="order{{ $task->id }}" class="text-sm font-medium text-gray-700 mb-1">{{ __('Order') }}</label>
        <input type="number" name="order{{ $task->id }}" id="order{{ $task->id }}" min="0" max="100"
            value="{{ old('order' . $task->id, $task->order ?? '') }}"
            class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400 @error('order' . $task->id) border-red-500 @enderror"
            required>
        @error('order' . $task->id)
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Column -->
    <div class="flex flex-col">
        <label for="column_id{{ $task->id }}" class="text-sm font-medium text-gray-700 mb-1">{{ __('Column') }}</label>
        <select name="column_id{{ $task->id }}" id="column_id{{ $task->id }}"
            class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400">
            @foreach ($columns as $column)
                <option value="{{ $column->id }}" @if($column->id == $task->column_id) selected @endif>{{ $column->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tags -->
    <div class="flex flex-col">
        <label for="tags{{ $task->id }}[]" class="text-sm font-medium text-gray-700 mb-1">{{ __('Tags') }}</label>
        <select name="tags{{ $task->id }}[]" id="tags{{ $task->id }}[]" multiple
            size="8"
            class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400">
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}"
                    @if (in_array($tag->id, old('tags' . $task->id, $task->tags->pluck('id')->toArray()))) selected @endif>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>
