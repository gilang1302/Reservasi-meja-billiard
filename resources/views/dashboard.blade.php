<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CueMaster Reserve Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-900 mb-6">Denah Meja Billiard (Real-Time)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($tables as $t)
                        <div class="border rounded-xl p-6 text-center shadow-md transition duration-300 hover:scale-105
                            {{ $t->status == 'Available' ? 'bg-green-50 border-green-500' : '' }}
                            {{ $t->status == 'Occupied' ? 'bg-red-50 border-red-500' : '' }}
                            {{ $t->status == 'Booked' ? 'bg-yellow-50 border-yellow-500' : '' }}
                            {{ $t->status == 'Maintenance' ? 'bg-gray-100 border-gray-400' : '' }}">
                            
                            <span class="text-2xl font-black block text-gray-800 mb-2">MEJA {{ $t->table_number }}</span>
                            
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase inline-block
                                {{ $t->status == 'Available' ? 'bg-green-200 text-green-800' : '' }}
                                {{ $t->status == 'Occupied' ? 'bg-red-200 text-red-800' : '' }}
                                {{ $t->status == 'Booked' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                {{ $t->status == 'Maintenance' ? 'bg-gray-300 text-gray-800' : '' }}">
                                {{ $t->status }}
                            </span>
                            
                            <p class="mt-4 text-gray-600 font-semibold">
                                Rp {{ number_format($t->price_per_hour, 0, ',', '.') }} / jam
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>