@extends('layouts.app')

@section('title','Edit User')

@section('content')

<x-page-header

    title="Edit User"

    subtitle="Perbarui informasi akun pengguna"

>

    <x-slot:action>

        <a href="{{ route('users.index') }}">

            <x-button color="gray">

                <i class="ri-arrow-left-line"></i>

                Kembali

            </x-button>

        </a>

    </x-slot:action>

</x-page-header>

@if($errors->any())

<x-alert type="error">

    <div class="font-semibold mb-2">

        Terdapat kesalahan:

    </div>

    <ul class="list-disc ml-5">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</x-alert>

@endif

<x-card>

<form

    method="POST"

    action="{{ route('users.update',$user) }}"

>

@csrf
@method('PUT')

<div class="grid grid-cols-2 gap-6">

    <x-input

        label="Nama Lengkap"

        name="name"

        icon="ri-user-line"

        :value="$user->name"

        required

    />

    <x-input

        label="Email"

        name="email"

        type="email"

        icon="ri-mail-line"

        :value="$user->email"

        required

    />

    <x-select

        label="Role"

        name="role"

        icon="ri-shield-user-line"

        required

    >

        <option value="Admin"

            @selected($user->role=='Admin')

        >

            Admin

        </option>

        <option value="Owner"

            @selected($user->role=='Owner')

        >

            Owner

        </option>

        <option value="Supervisor"

            @selected($user->role=='Supervisor')

        >

            Supervisor

        </option>

        <option value="Kasir"

            @selected($user->role=='Kasir')

        >

            Kasir

        </option>

    </x-select>

    <x-select

        label="Status"

        name="is_active"

        icon="ri-user-settings-line"

        required

    >

        <option value="1"

            @selected($user->is_active)

        >

            Aktif

        </option>

        <option value="0"

            @selected(!$user->is_active)

        >

            Nonaktif

        </option>

    </x-select>

    <div class="col-span-2">

        <x-input

            label="Password Baru (Opsional)"

            name="password"

            type="password"

            icon="ri-lock-password-line"

            placeholder="Kosongkan jika tidak ingin mengubah password"

        />

    </div>

</div>

{{-- <div class="flex justify-between items-center mt-8"> --}}
<div class="mt-8 flex justify-end">    

   

    <div class="flex gap-3">
    {{-- <div class="flex justify-end gap-3 mt-8"> --}}

        <a href="{{ route('users.index') }}">

            <x-button color="gray">

                <i class="ri-close-line"></i>

                Batal

            </x-button>

        </a>

        <x-button

            color="primary"

            type="submit"

        >

            <i class="ri-save-line"></i>

            Simpan Perubahan

        </x-button>

    </div>

</div>

</form>

</x-card>

{{-- reset pwd --}}
<x-card class="mt-6">

    <div class="flex items-start gap-4">
    {{-- <div class="mt-6 flex justify-end"> --}}

        <div
            class="w-12 h-12 rounded-xl
            bg-amber-100
            flex items-center justify-center"
        >

            <i
                class="ri-lock-password-line
                text-2xl
                text-amber-600"
            ></i>

        </div>

        <div class="flex-1">

            <h3
                class="text-lg font-semibold
                text-slate-800"
            >

                Keamanan Akun

            </h3>

            <p
                class="mt-2 text-sm
                text-slate-500"
            >

                Password pengguna tidak dapat
                dilihat.

                Jika diperlukan, Admin dapat
                mereset password menjadi
                password default sistem ( 87654321 ).

            </p>

        </div>

    </div>

    {{-- <div class="mt-6"> --}}
    <div class="mt-6 flex justify-end">    
        <form

            id="formResetPassword"

            method="POST"

            action="{{ route('users.reset-password',$user) }}"

        >
        
            @csrf
        </form>

        <x-button 
            color="red"
            id="btnResetPassword"
                type="button"
        >

            <i class="ri-key-2-line"></i>

            Reset Password

        </x-button>

        

    </div>

</x-card>
{{-- reset pwd end --}}

@push('scripts')

<script>

document
.getElementById('btnResetPassword')
.addEventListener('click',function(){

    Swal.fire({

        title:'Reset Password?',

        html:`

            Password akan direset menjadi:

            <br><br>

            <b>87654321</b>

        `,

        icon:'warning',

        showCancelButton:true,

        confirmButtonText:'Ya, Reset',

        cancelButtonText:'Batal',

        confirmButtonColor:'#4F46E5',

        cancelButtonColor:'#94A3B8'

    }).then((result)=>{

        if(result.isConfirmed){

            document
            .getElementById('formResetPassword')
            .submit();

        }

    });

});

</script>

@endpush

@endsection