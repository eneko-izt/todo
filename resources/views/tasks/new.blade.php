<div border="1" class="p-2 mb-2 bg-light text-dark rounded">
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div>

            <label for="text">Text:</label>
            <textarea 
                class="form-control mb-2 @error('text'.$column->id) help is-danger @enderror" 
                name={{ 'text'.$column->id }}
                id={{ 'text'.$column->id }}
                rows="3"
                maxlength="255"
                style="@error('text'.$column->id) color:#d8000c @enderror"
                required
            >{{ old('text'.$column->id) }}</textarea>

            @error('text'.$column->id)
                <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
            @enderror

            <label for="order">Order:</label>
            <input 
                type="number" 
                class="form-control mb-2 @error('order'.$column->id) help is-danger @enderror" 
                name={{ 'order'.$column->id }}
                id={{ 'order'.$column->id }}
                value="{{ old('order'.$column->id) }}"
                min="0"
                max="100"
                style="@error('order'.$column->id) color:#d8000c @enderror"
                required
            >
            @error('order'.$column->id)
                <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
            @enderror

            <label for="input">Tags:</label>
            <select 
                class="form-control select2"
                name="tags[]"
                id="tags"
                style="width: 100%;"
                multiple
            >
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>

            <input 
                type="hidden" 
                name="column_id" 
                id="column_id" 
                value="{{ $column->id ?? '' }}"
            >

            <div class="control">
                <button class="btn btn-primary bottom" type="submit" Title="Create a new task">New</button>
            </div>

        </div>
    </form>
</div>