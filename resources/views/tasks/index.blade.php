@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ __('All Tasks') }}</h1>

    <table id="myTable" class="min-w-full text-sm" role="grid">
        <thead class="bg-gray-100 text-left text-gray-700">
            <tr>
                <th class="px-4 py-2 font-semibold">{{ __('Column') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Task') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Tags') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Owner') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Shared with') }}</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.alltasks') }}",
            columns: [
                { data: 'column', name: 'column' },
                { data: 'text', name: 'text' },
                { data: 'tags', name: 'tags', orderable: false },
                { data: 'owner', name: 'owner' },
                { data: 'sharingUsers', name: 'sharingUsers', orderable: false },
            ],
            language: {
                paginate: {
                    emptyTable: "{{ __('No columns found.') }}",
                    previous: "{{ __('Previous') }}",
                    next: "{{ __('Next') }}"
                },
                lengthMenu: "{{ __('Show _MENU_ entries') }}",
                zeroRecords: "{{ __('No matching records found') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries', ['START' => 1, 'END' => 10, 'TOTAL' => 100]) }}",
                infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                infoFiltered: "{{ __('(filtered from _MAX_ total entries)', ['MAX' => 100]) }}",
                search: "{{ __('Search:') }}"
            },
        });
    });
</script>
@endsection
