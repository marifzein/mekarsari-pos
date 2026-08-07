<div class="overflow-x-auto">

    <table

        {{ $attributes->merge([

            'class' => 'w-full 
            border 
            border-slate-200
            '

        ]) }}

    >

        {{ $slot }}

    </table>

</div>