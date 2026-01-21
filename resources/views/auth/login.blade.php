<!doctype html>
<html
  lang="en"
  class="layout-wide customizer-hide"
  dir="ltr"
  data-skin="default"
  data-assets-path="{{ asset('assets/') }}/"
  data-template="vertical-menu-template-no-customizer"
  data-bs-theme="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login SIKUALA</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
      rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <div class="authentication-wrapper authentication-cover">
      <a href="#" class="app-brand auth-cover-brand">
        <span class="app-brand-logo demo">
          <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo Air" width="80" height="65" />
        </span>
        <span class="app-brand-text demo text-heading">MILKTRACK | Sistem Monitoring Susu</span>
      </a>

      <div class="authentication-inner row m-0">
        <div class="d-none d-xl-flex col-xl-8 p-0">
          <div class="auth-cover-bg d-flex justify-content-center align-items-center">
            <img
              src="{{ asset('assets/img/SIKUALA/555.png') }}"
              alt="Logo Aplikasi"
              class="my-5 app-logo img-fluid"
              style="max-width: 700px; margin-left: 50px;" />
          </div>
        </div>

        <!-- Login Form -->
        <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
          <div class="w-px-400 mx-auto mt-12 pt-5">
            <h4 class="mb-1">Welcome to MilkTrack! 👋</h4>
            <p class="mb-6">Masuk menggunakan Username dan Password Anda.</p>

            <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('login') }}">
              @csrf

              <!-- Username -->
              <div class="mb-6 form-control-validation">
                <label for="username" class="form-label">Username</label>
                <input
                  type="text"
                  class="form-control @error('username') is-invalid @enderror"
                  id="username"
                  name="username"
                  value="{{ old('username') }}"
                  placeholder="Masukkan username"
                  required
                  autofocus
                  autocomplete="username" />
                @error('username')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <!-- Password -->
              <div class="mb-6 form-password-toggle form-control-validation">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="••••••••••"
                    required
                    autocomplete="current-password" />
                  <span class="input-group-text cursor-pointer">
                    <i class="icon-base ti tabler-eye-off"></i>
                  </span>
                </div>
                @error('password')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <!-- Remember Me -->
              <div class="my-8">
                <div class="d-flex justify-content-between">
                  <div class="form-check mb-0 ms-2">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }} />
                    <label class="form-check-label" for="remember_me"> Remember Me </label>
                  </div>
                </div>
              </div>

              <!-- Login Button -->
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </form>

            <!-- Register Link -->
          </div>
        </div>
      </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
  </body>
</html>
