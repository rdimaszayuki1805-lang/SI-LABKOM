
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard | SI-LABKOM</title>

    <link rel="stylesheet"
          href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
</head>

<body style="background:#f6f9f7;">

<header class="bg-white border-bottom">
    <div class="container py-3 d-flex align-items-center justify-content-between">

        <div>
            <h5 class="mb-0" style="color:#2F855A;">
                SI-LABKOM
            </h5>

            <small class="text-muted">
                MTs Al Ittihadiyah Pekanbaru
            </small>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </form>

    </div>
</header>

<main class="container py-5">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <h3 style="color:#2F855A;">
                Dashboard {{ $roleTitle }}
            </h3>

            <p class="text-muted">
                Selamat datang di Sistem Informasi
                Pengelolaan Laboratorium Komputer.
            </p>

            <hr>

            <p>
                <strong>Nama:</strong>
                {{ auth()->user()->name }}
            </p>

            <p>
                <strong>Email:</strong>
                {{ auth()->user()->email }}
            </p>

            <p>
                <strong>Role:</strong>
                {{ strtoupper(auth()->user()->role) }}
            </p>

            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                Login berhasil. Autentikasi Laravel
                dan session pengguna aktif.
            </div>

        </div>
    </div>

</main>

</body>
</html>
