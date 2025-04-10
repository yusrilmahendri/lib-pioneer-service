<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Card Informasi Profil --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <x-heroicon-o-user class="w-6 h-6 text-primary-500" />
                Informasi Akun
            </h2>

            <dl class="divide-y divide-gray-200">
                <div class="py-3">
                    <dt class="text-sm font-medium text-gray-500">Nama</dt>
                    <dd class="text-lg font-semibold text-gray-800">{{ auth()->user()->name }}</dd>
                </div>
                <div class="py-3">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-lg font-semibold text-gray-800">{{ auth()->user()->email }}</dd>
                </div>
                <div class="py-3">
                    <dt class="text-sm font-medium text-gray-500">Password</dt>
                    <dd class="text-lg font-semibold text-gray-800">*********</dd>
                </div>
            </dl>
        </div>

        {{-- Card Form Edit --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <x-heroicon-o-pencil-square class="w-6 h-6 text-primary-500" />
                Perbarui Informasi
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                {{ $this->form }}

                <div class="pt-4 text-right">
                    <x-filament::button color="primary" type="submit" icon="heroicon-m-check-circle">
                        Simpan Perubahan
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
        {{-- Footer --}}
    <div class="mt-10 text-center text-sm text-gray-500">
        &copy; {{ now()->year }} Created at by <span class="font-semibold text-primary-600">Pioneersolve</span>
    </div>

</x-filament::page>
