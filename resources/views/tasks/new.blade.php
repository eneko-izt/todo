<div class="bg-white shadow rounded-xl p-4 space-y-4">

    @error('user_id' . $column->id)
        <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
    @enderror

    <!-- Text -->
    <div class="flex flex-col">
        <label for="text{{ $column->id }}" class="text-sm font-medium text-gray-700 mb-1">Text:</label>
        <textarea name="text{{ $column->id }}" id="text{{ $column->id }}" rows="3" maxlength="255"
            class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400 @error('text' . $column->id) border-red-500 @enderror"
            required>{{ old("text{$column->id}") }}</textarea>
        @error('text' . $column->id)
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Order -->
    <div class="flex flex-col">
        <label for="order{{ $column->id }}" class="text-sm font-medium text-gray-700 mb-1">Order:</label>
        <input type="number" name="order{{ $column->id }}" id="order{{ $column->id }}" min="0" max="100"
            value="{{ old('order' . $column->id) }}"
            class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400 @error('order' . $column->id) border-red-500 @enderror"
            required>
        @error('order' . $column->id)
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tags -->
    <div class="flex flex-col">
        <label for="tags{{ $column->id }}[]" class="text-sm font-medium text-gray-700 mb-1">Tags:</label>
        <select name="tags{{ $column->id }}[]" id="tags{{ $column->id }}[]" multiple
            size="8"
            class="border rounded p-2 text-sm focus:ring-1 focus:ring-blue-400">
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}"
                    @if (in_array($tag->id, old('tags' . $column->id, []))) selected @endif>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>
