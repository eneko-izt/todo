    <div class="field">
            <div class="control">
            <label for="text">Text:</label>
                <textarea 
                    class="form-control mb-2 @error('text') help is-danger @enderror" 
                    name='text'
                    id='text'
                    rows="3"
                    maxlength="255"
                    style="@error('text') color:#d8000c @enderror"
                    required
                >{{ old('text', $task->text ?? '') }}</textarea>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input 
                    type="checkbox" 
                    name="active" 
                    @if (old('active') == 'on')
                        checked
                    @elseif ($task->active && old('active') == null && $errors->isEmpty())
                        checked
                    @endif
                    
                >
                <label class="label" for="active">Active</label>
            </div>
        </div>

        <div class="field">
            <label for="order">Order:</label>
            <div class="control">
                <input 
                    type="number" 
                    class="form-control mb-2 @error('order') help is-danger @enderror" 
                    name='order'
                    id='order'
                    value="{{ old('order', $task->order ?? '') }}"
                    min="0"
                    max="100"
                    style="@error('order') color:#d8000c @enderror"
                    required
                >
                @error('order')
                    <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="field">
            <label for="input">Tags:</label>
            <div class="control">
                <select 
                    class="form-control select2"
                    name="column"
                    id="column"
                    style="width: 100%;"
                >
                    @foreach($columns as $column)
                        <option value="{{ $column->id }}">{{ $column->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>