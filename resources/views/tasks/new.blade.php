<div border="1" class="p-2 mb-2 bg-light text-dark rounded">
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div>

            <label for="text">Text:</label>
            <textarea 
                class="form-control mb-2 @error('text') help is-danger @enderror" 
                name="text" 
                id="text"
                rows="3"
                maxlength="255"
                style="@error('text') color:#d8000c @enderror"
                required
            >{{ old('text') }}</textarea>

            @error('text')
                <p class="help is-danger" style="color:#d8000c">{{ $errors->first('text') }}</p>
            @enderror

            <label for="order">Order:</label>
            <input 
                type="number" 
                class="form-control mb-2 @error('order') help is-danger @enderror" 
                name="order" 
                id="order" 
                value="{{ old('order') }}"
                min="0"
                max="100"
                style="@error('order') color:#d8000c @enderror"
                required
            >
            @error('order')
                <p class="help is-danger" style="color:#d8000c">{{ $errors->first('order') }}</p>
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
                <button class="btn btn-primary bottom" type="submit">New</button>
            </div>

        </div>
    </form>
</div>