<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sobat Industri</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Use min-height for full page height */
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }
        .card {
            border-radius: 15px;
            margin-top: 2rem; /* Adjust the margin-top for card positioning */
        }
        .card-body {
            padding: 2rem;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .input-group-text {
            cursor: pointer;
        }
        .header-logo {
            text-align: center; /* Center align the logo */
            margin-bottom: 1cm; /* Margin bottom for spacing */
        }
        .header-logo img {
            max-width: 100px;
            margin-right: 1cm;
        }
        .row.justify-content-center {
            margin-top: 0.5rem; /* Adjust the margin-top for the row */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-logo">
            <img src="{{asset('assets/img/logo.png')}}" alt="Klinik NU Logo">
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <img src="path/to/illustration.png" class="card-img" alt="Illustration">
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title">Klinik NU Muntilan</h5>
                                <p class="card-text">Jl. Gunungpring, Ngasem, Gunungpring, Kec. Muntilan, Kabupaten Magelang</p>
                                <form action="{{ route('login') }}" method="POST" id="formAuthentication">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email" autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-eye-slash"></i></span>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group d-flex justify-content-between">
                                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                    <div class="text-center mt-3">
                                        <p>No account yet? <a href="{{ route('register') }}">Register</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.input-group-text').on('click', function() {
                let passwordInput = $('#password');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
        });
    </script>
</body>
</html>
