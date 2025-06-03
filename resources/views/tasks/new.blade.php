<div border="1" class="p-2 mb-2 bg-light text-dark rounded">
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div>
            <textarea 
                class="form-control mb-2" 
                name="text" 
                id="text" 
                rows="3" 
                maxlength="255"
                required>
            </textarea>
            <label for="fname">Order:</label>
            <input 
                type="number" 
                class="form-control mb-2" 
                name="order" 
                id="order" 
                min="0"
                max="100"
                required
            >
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