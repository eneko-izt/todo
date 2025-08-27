@extends('layouts.app')

@section('content')

    <h3>{{ Date('Y-m-d') }}</h3>
    <a href="{{ route('welcome') }}" class="btn btn-primary" title="Go to Welcome page">Home</a>
    <a href="{{ route('home') }}" class="btn btn-primary" title="Go to Dashboard">Go to Dashboard</a>
    @can('viewColumn', 'App\Column')
        <a href="{{ route('columns.index') }}" class="btn btn-primary" title="Go to Columns page">Columns</a>
    @endcan
    @can('viewTag', 'App\Tag')
        <a href="{{ route('tags.index') }}" class="btn btn-primary" title="Go to Tags page">Tags</a>
    @endcan
    @can('viewUser', 'App\User')
        <a href="{{ route('users.index') }}" class="btn btn-primary" title="Go to Users page">Users</a>
    @endcan
    <div class="d-lg-flex justify-content-between w-100 mt-5">
        @forelse ($columns as $column)
            <div class="p-2 text-white @if (!$loop->first) ml-lg-2 @endif"
                style="min-width: {{ 100 / $columns->count() }}%; min-height: 150px; background-color: {{ $column->colour }};">
                {{ $column->name }}

                @foreach ($column->activeTasks as $task)
                    @include('tasks.task', ['task' => $task])
                @endforeach


                <div class="d-flex justify-content-between">


                        <!-- Share tasks with other users -->
                        <button type="button" id="new-btn-{{ $column->id }}" class="btn btn-primary">
                            New Task
                        </button>

                </div>

                <div id="new-content-{{ $column->id }}" style="display: none;">
                    @include('tasks.new', ['column' => $column])
                </div>

            </div>

        @empty
            <p>No columns found.</p>
        @endforelse
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const buttons  = document.querySelectorAll('[id^="new-btn-"]');
  const contents = document.querySelectorAll('[id^="new-content-"]');

  buttons.forEach(button => {
    button.addEventListener('click', function () {
      const columnId  = this.id.replace('new-btn-', '');
      const contentId = `new-content-${columnId}`;
      const contentDiv = document.getElementById(contentId);

      // Check current computed visibility BEFORE we hide everything
      const isVisible = window.getComputedStyle(contentDiv).display !== 'none';

      // Hide all contents & reset all buttons
      contents.forEach(div => div.style.display = 'none');
      buttons.forEach(btn => {
        btn.textContent = 'New Task';
        btn.setAttribute('aria-expanded', 'false');
      });

      // If the clicked one was NOT visible, show it and update its button
      if (!isVisible) {
        contentDiv.style.display = 'block';
        this.textContent = 'Cancel';
        this.setAttribute('aria-expanded', 'true');
      }
      // if it WAS visible, we left it hidden (and button reset) — i.e. Cancel hides
    });
  });
});
</script>

@endsection
