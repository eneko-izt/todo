    <div class="field">

            @error('user_id' . $task->id)
                <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
            @enderror

            <div class="control">
            <label for="text{{ $task->id }}">Text:</label>
                <textarea 
                    class="form-control mb-2 @error('text' . $task->id) help is-danger @enderror" 
                    name="text{{ $task->id }}"
                    id="text{{ $task->id }}"
                    rows="3"
                    maxlength="255"
                    style="@error('text' . $task->id) color:#d8000c @enderror"
                    required
                >{{ old("text{$task->id}", $task->text ?? '') }}</textarea>

                @error('text' . $task->id)
                    <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
                @enderror

            </div>
        </div>

        <div class="field">
            <div class="control">
                <input 
                    type="checkbox" 
                    name="active{{ $task->id }}"
                    @if ($task->active)
                        checked
                    @endif
                >
                <label class="label" for="active{{ $task->id }}">Active</label>
            </div>
        </div>

        <div class="field">
            <label for="order{{ $task->id }}">Order:</label>
            <div class="control">
                <input 
                    type="number" 
                    class="form-control mb-2 @error('order' . $task->id) help is-danger @enderror" 
                    name='order{{ $task->id }}'
                    id='order{{ $task->id }}'
                    value={{ old("order" . $task->id, $task->order ?? '') }}
                    min="0"
                    max="100"
                    style="@error('order' . $task->id) color:#d8000c @enderror"
                    required
                >

                @error('order' . $task->id)
                    <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
                @enderror

            </div>
        </div>

        <div class="field">
            <label for="column_id{{ $task->id }}">Column:</label>
            <div class="control">
                <select 
                    class="form-control select2"
                    name="column_id{{ $task->id }}"
                    id="column_id{{ $task->id }}"
                    style="width: 100%;"
                >
                    @foreach($columns as $column)
                        <option 
                            value="{{ $column->id }}"
                            @if ($column->id == $task->column_id) selected @endif
                            >
                            {{ $column->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field">
            <label for="tags{{ $task->id }}[]">Tags:</label>
            <div class="control">
                <select 
                    class="form-control select2"
                    name="tags{{ $task->id }}[]"
                    id="tags{{ $task->id }}[]"
                    style="width: 100%;"
                    multiple
                >
                    @foreach($tags as $tag)
                        <option 
                            value="{{ $tag->id }}"
                            @if(in_array($tag->id, $task->tags->pluck('id')->toArray())) selected @endif
                            >
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
