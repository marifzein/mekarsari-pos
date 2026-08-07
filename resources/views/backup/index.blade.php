@extends('layouts.app')

@section('title','Backup Database')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- HEADER --}}

    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Backup Database
            </h1>

            <p class="text-sm text-slate-500">
                Backup database MySQL ke file SQL (.sql.gz)
            </p>

        </div>
        <div class="flex items-center gap-3">
            <form
                action="{{ route('backup.create') }}"
                method="POST"
            >
                @csrf

                
                <x-button color="primary" type="submit">

                <i class="ri-save-fill"></i>

                    Backup Sekarang

                </x-button>
            </form>

            @can('akses-owner-admin')
            <!-- TOMBOL BARU: Buat Skema Cabang Baru -->
            <form action="{{ route('backup.skema-only') }}" method="POST">
                @csrf
                <x-button color="green" type="submit">
                    <i class="ri-git-branch-line"></i> Buat Skema Baru
                </x-button>
            </form>
            @endcan
        

        </div>
    </div>
    

    <div
        class="bg-white rounded-2xl shadow border overflow-hidden"
    >

        <table class="w-full">

            <thead
                class="bg-slate-100 text-slate-700"
            >

            <tr>

                <th class="text-left p-4">
                    Nama File
                </th>

                <th class="text-center w-40">
                    Ukuran
                </th>

                <th class="text-center w-56">
                    Tanggal
                </th>

                <th class="text-center w-48">
                    Aksi
                </th>

            </tr>

            </thead>

            <tbody>

            @forelse($files as $file)

                <tr class="border-t hover:bg-slate-50">

                    <td class="p-4">

                        {{ $file['name'] }}

                    </td>

                    <td class="text-center">

                        {{ number_format($file['size']/1024/1024,2) }}

                        MB

                    </td>

                    <td class="text-center">

                        {{ date('d-m-Y H:i:s',$file['date']) }}

                    </td>

                    <td class="px-3">

                        <div
                            class="flex justify-center gap-2"
                        >
                            <a href="{{ route('backup.download',$file['name']) }}">
                                <x-button color="green" type="button">

                                    <i class="ri-download-cloud-fill"></i>

                                      Download

                                </x-button>
                            </a>
                            {{-- <a

                                href="{{ route('backup.download',$file['name']) }}"

                                class="px-3 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700"

                            >
                              
                              <i class="ri-download-cloud-fill"></i>
                                Download

                            </a> --}}

                            @can('akses-owner-admin')
                            <form

                                action="{{ route('backup.destroy',$file['name']) }}"

                                method="POST"

                                class="delete-form"

                            >

                                @csrf

                                @method('DELETE')

                                {{-- <button

                                    type="submit"

                                    class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700"

                                >

                                    Hapus

                                </button> --}}

                                <x-button color="red" 
                                type="submit">

                                  <i class="ri-delete-bin-5-line"></i>

                                    Hapus

                                </x-button>

                            </form>
                            @endcan

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="4"
                        class="text-center p-8 text-slate-400"
                    >

                        Belum ada file backup.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

@push('scripts')

<script>

document
.querySelectorAll('.delete-form')
.forEach(form=>{

    form.addEventListener('submit',function(e){

        e.preventDefault();

        Swal.fire({

            title:'Hapus Backup?',

            text:'File backup akan dihapus permanen.',

            icon:'warning',

            showCancelButton:true,

            confirmButtonText:'Ya',

            cancelButtonText:'Batal'

        })

        .then(result=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script>

@endpush