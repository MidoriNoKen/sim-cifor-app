<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta name="csrf-token" value="{{ csrf_token() }}" />

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Scripts -->
    </head>
    <body class="font-sans antialiased">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="table-responsive">
                        <table class="table align-middle text-center">
                            @if ($receiver->position == 'Supervisor' || $receiver->position == 'Manager' || $receiver->position == 'Finance')
                            <div class="card">
                                <div class="card-header">Leave Application Approval Reminder - {{ $status }}</div>
                                <div class="card-body">
                                    <p class="lead">Halo {{ $receiver->name }},</p>
                                    <p>Kami ingin memberitahu bahwa Anda mendapatkan pengajuan izin cuti dari {{ $sender->name }}. Mohon untuk memeriksa status pengajuan izin cuti tersebut dan pastikan semua persyaratan terpenuhi.</p>
                                    <p>Terima kasih atas perhatiannya.</p>
                                    <p class="font-weight-bold">Salam,<br><br><br>Admin SIM Cifor</p>
                                </div>
                            </div>
                            @elseif ($status == 'New')
                            <div class="card">
                                <div class="card-header">Leave Application Notification - {{ $status }}</div>
                                <div class="card-body">
                                    <p class="lead">Halo {{ $receiver->name }},</p>
                                    <p>Pengajuan izin cuti Anda masih diproses. Mohon untuk menunggu persetujuan pengajuan izin cuti dari {{ $sender->name }}.</p>
                                    <p>Terima kasih atas perhatiannya.</p>
                                    <p class="font-weight-bold">Salam,<br><br><br>Admin SIM Cifor</p>
                                </div>
                            </div>
                            @else
                            <div class="card">
                                <div class="card-header">Leave Application Notification - {{ $status }}</div>
                                <div class="card-body">
                                    <p class="lead">Halo {{ $receiver->name }},</p>
                                    <p>Kami ingin menginformasikan bahwa status pengajuan diperbarui menjadi {{ $status }}. Telah diperbarui oleh {{ $sender->name }}.</p>
                                    <p>Terima kasih atas perhatiannya.</p>
                                    <p class="font-weight-bold">Salam,<br><br><br>Admin SIM Cifor</p>
                                </div>
                            </div>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
