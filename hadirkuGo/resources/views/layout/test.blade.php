<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard iOS Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #002f4b; /* Deep navy */
            color: #FFF;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        }
        .navbar-ios {
            background-color: rgba(0, 47, 75, 0.1);
            border-top: 1px solid rgba(255,255,255,0.1);
            position: fixed;
            bottom: 0;
            width: 100%;
            transition: all 0.3s ease;
        }
        .nav-link {
            color: #FFF !important;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: #00B7FF !important; /* A vibrant blue for interaction */
        }
        .nav-link .fa {
            font-size: 1.5em;
            line-height: 1em;
        }
        .card-ios {
            background-color: rgba(255, 255, 255, 0.05);
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card-ios:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .card-title {
            color: #FFF;
            font-size: 1.2em;
        }
        .card-text {
            color: rgba(255, 255, 255, 0.7);
        }
        .fa-icon {
            color: #00B7FF; /* Same vibrant blue */
            font-size: 2em;
            margin-bottom: 10px;
        }
        /* More content styling */
        .content-text {
            font-size: 0.9em;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<header class="py-5 text-center">
    <h1 class="display-4">Dashboard</h1>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card card-ios h-100">
                <div class="card-body d-flex flex-column">
                    <i class="fa-icon fa fa-sun-o text-center"></i>
                    <h5 class="card-title text-center">Cuaca</h5>
                    <p class="card-text text-center content-text">Cuaca cerah dengan suhu 28°C. Kelembaban 60%.</p>
                    <div class="mt-auto">
                        <small class="text-muted text-center">Perkiraan untuk hari ini</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card card-ios h-100">
                <div class="card-body d-flex flex-column">
                    <i class="fa-icon fa fa-calendar text-center"></i>
                    <h5 class="card-title text-center">Kalender</h5>
                    <p class="card-text text-center content-text">
                        Selasa, 18 November 2024 <br>
                        - Meeting dengan Tim Pemasaran pada pukul 10:00 <br>
                        - Deadline Proyek A pada pukul 15:00
                    </p>
                    <div class="mt-auto">
                        <small class="text-muted text-center">Lihat semua agenda</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card card-ios h-100">
                <div class="card-body d-flex flex-column">
                    <i class="fa-icon fa fa-newspaper-o text-center"></i>
                    <h5 class="card-title text-center">Berita</h5>
                    <p class="card-text text-center content-text">
                        "Inovasi Teknologi Baru dalam Bidang Kesehatan" - <br>
                        Sebuah tim peneliti telah mengembangkan teknologi AI yang dapat memprediksi risiko penyakit jantung dengan akurasi 95%.
                    </p>
                    <div class="mt-auto">
                        <small class="text-muted text-center">Baca Selengkapnya</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<nav class="navbar navbar-ios">
    <div class="container-fluid">
        <ul class="nav justify-content-center w-100">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#"><i class="fa fa-home"></i> Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-calendar"></i> Kalender</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-newspaper-o"></i> Berita</a>
            </li>
        </ul>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script>
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            this.classList.add('active');
            document.querySelectorAll('.nav-link').forEach(otherLink => {
                if (otherLink !== this) otherLink.classList.remove('active');
            });
        });
    });
</script>
</body>
</html>