        <div class="field">
            <label class="label" for="name">Name</label>
            <div class="control">
                <input 
                    class="input @error('name') help is-danger @enderror"
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $column->name ?? '') }}"
                    maxlength="255"
                    style="@error('name') color:#d8000c @enderror"
                    required>

                @error('name')
                    <p class="help is-danger" style="color:#d8000c">{{ $errors->first('name') }}</p>
                @enderror
            </div>
        </div>
        <div class="field">
            <div class="control">
                <input 
                    type="checkbox" 
                    name="active" 
                    @if (old('active') == 'on' && !$column->exists)
                        checked
                    @elseif (old('active') == null && !$column->exists && $errors->isEmpty())
                        checked
                    @elseif (old('active') == 'on' && $column->exists)
                        checked
                    @elseif ($column->exists && $column->active && old('active') == null && $errors->isEmpty())
                        checked
                    @elseif ($column->exists && old('active') == 'on' )
                        checked
                    @endif
                    
                >
                <label class="label" for="active">Active</label>
            </div>
        </div>
