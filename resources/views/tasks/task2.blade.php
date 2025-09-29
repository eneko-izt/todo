            <div class="bg-white shadow rounded-xl mb-3">
                <div class="p-3">
                    <p class="font-medium mb-2">{{ $task->id }}: {{ $task->text }}</p>
                    <div class="flex flex-wrap gap-2 mb-2">

            @foreach ($task->tags()->active()->get() as $tag)
                    <span class="bg-gray-200 px-2 py-1 rounded text-xs">{{ $tag->getUpperName() }}</span>
            @endforeach

                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-gray-300 w-6 h-6 flex items-center justify-center rounded-full text-xs">A</span>
                        <span class="bg-gray-300 w-6 h-6 flex items-center justify-center rounded-full text-xs">B</span>
                    </div>
                    <div class="flex gap-2">
                        <button class="border px-2 py-1 rounded text-xs">Edit</button>
                        <button class="border px-2 py-1 rounded text-xs">+ User</button>
                    </div>
                </div>
            </div>
