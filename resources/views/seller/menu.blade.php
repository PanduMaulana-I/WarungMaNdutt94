<x-layouts.seller :title="'Kelola Menu'">
  <a href="{{ route('menus.create') }}" class="mb-4 inline-block bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">+ Tambah Menu</a>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach ($menus as $menu)
      <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
        <div class="flex gap-4">
          <div class="w-24 h-24 rounded-lg bg-slate-100 overflow-hidden">
            @if($menu->image)
              <img src="{{ asset('storage/'.$menu->image) }}" class="object-cover w-full h-full">
            @else
              <div class="flex items-center justify-center h-full text-slate-400 text-sm">No Image</div>
            @endif
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold">{{ $menu->name }}</h3>
            <p class="text-slate-500 text-sm">{{ $menu->description ?? '-' }}</p>
            <p class="text-indigo-700 font-semibold mt-1">Rp {{ number_format($menu->price,0,',','.') }}</p>
            <p class="text-xs text-slate-400">{{ $menu->stock }} stok</p>
          </div>
        </div>
        <div class="mt-3 flex gap-2">
          <a href="{{ route('menus.edit', $menu->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded text-sm">Edit</a>
          <form method="POST" action="{{ route('menus.destroy', $menu->id) }}" onsubmit="return confirm('Hapus menu ini?')">
            @csrf @method('DELETE')
            <button class="px-3 py-1 bg-rose-500 text-white rounded text-sm">Hapus</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</x-layouts.seller>
