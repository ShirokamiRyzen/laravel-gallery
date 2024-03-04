<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="{{ asset('assets/custom/js/swal2.min.js') }}"></script>
    <link rel="icon" href="{{ asset('assets/custom/img/favicon.ico') }}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,600,700|Work+Sans:300,400,700,900');

        * {
            outline-width: 0;
            font-family: 'Montserrat' !important;
        }

        body {
            background: #23272A;
        }

        #container {
            height: 100vh;
            background-size: cover !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #inviteContainer {
            display: flex;
            overflow: hidden;
            position: relative;
            border-radius: 5px;
        }

        .acceptContainer {
            padding: 45px 30px;
            box-sizing: border-box;
            width: 400px;
            margin-left: -400px;
            overflow: hidden;
            height: 0;
            opacity: 0;
        }

        .acceptContainer.loadIn {
            opacity: 1;
            margin-left: 0;
            transition: 0.5s ease;
        }

        .acceptContainer:before {
            content: "";
            background-size: cover !important;
            box-shadow: inset 0 0 0 3000px rgba(40, 43, 48, .75);
            filter: blur(10px);
            position: absolute;
            width: 150%;
            height: 150%;
            top: -50px;
            left: -50px;
        }

        form {
            position: relative;
            text-align: center;
            height: 100%;
        }

        form h1 {
            margin: 0 0 15px 0;
            font-family: 'Work Sans' !important;
            font-weight: 700;
            font-size: 20px;
            color: #fff;
            user-select: none;
            opacity: 0;
            left: -30px;
            position: relative;
            transition: 0.5s ease;
        }

        form h1.loadIn {
            left: 0;
            opacity: 1;
        }

        .formContainer {
            text-align: left;
        }

        .formDiv {
            margin-bottom: 30px;
            left: -25px;
            opacity: 0;
            transition: 0.5s ease;
            position: relative;
        }

        .formDiv.loadIn {
            opacity: 1;
            left: 0;
        }

        .formDiv:last-child {
            padding-top: 10px;
            margin-bottom: 0;
        }

        p {
            margin: 0;
            font-weight: 700;
            color: #aaa;
            font-size: 10px;
            user-select: none;
        }

        input[type=password],
        input[type=email] {
            background: transparent;
            border: none;
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.15);
            padding: 15px 0;
            box-sizing: border-box;
            color: #fff;
            width: 100%;
        }

        input[type=password]:focus,
        input[type=email]:focus {
            box-shadow: inset 0 -2px 0 #fff;
        }

        .logoContainer {
            padding: 45px 35px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transform: scale(0, 0);
        }

        .logoContainer img {
            width: 150px;
            margin-bottom: -5px;
            display: block;
            position: relative;
        }

        .logoContainer img:first-child {
            width: 150px;
        }

        .text {
            padding: 25px 0 10px 0;
            margin-top: -70px;
            opacity: 0;
        }

        .text.loadIn {
            margin-top: 0;
            opacity: 1;
            transition: 0.8s ease;
        }

        .logo {
            position: relative;
            top: -20px;
            opacity: 0;
        }

        .logo.loadIn {
            top: 0;
            opacity: 1;
            transition: 0.8s ease;
        }

        .logoContainer:before {
            content: "";
            background-size: cover !important;
            position: absolute;
            top: -50px;
            left: -50px;
            width: 150%;
            height: 150%;
            filter: blur(10px);
            box-shadow: inset 0 0 0 3000px rgba(255, 255, 255, 0.8);
        }

        .forgotPas {
            color: #aaa;
            opacity: .8;
            text-decoration: none;
            font-weight: 700;
            font-size: 10px;
            margin-top: 15px;
            display: block;
            transition: 0.2s ease;
        }

        .forgotPas:hover {
            opacity: 1;
            color: #fff;
        }

        .remember {
            color: #aaa;
            opacity: .8;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: 0.2s ease;
        }

        .remember:hover {
            opacity: 1;
            color: #fff;
        }

        .acceptBtn {
            width: 100%;
            box-sizing: border-box;
            background: #7289DA;
            border: none;
            color: #fff;
            padding: 20px 0;
            border-radius: 3px;
            cursor: pointer;
            transition: 0.2s ease;
            user-select: none;
        }

        .acceptBtn:hover {
            background: #6B7FC5;
        }

        .register {
            color: #aaa;
            font-size: 12px;
            padding-top: 15px;
            display: block;
        }

        .register a {
            color: #fff;
            text-decoration: none;
            margin-left: 5px;
            box-shadow: inset 0 -2px 0 transparent;
            padding-bottom: 5px;
            user-select: none;
        }

        .register a:hover {
            box-shadow: inset 0 -2px 0 #fff;
        }
    </style>
</head>

<body>
    <div id="container">
        <div id="inviteContainer">
            <div class="logoContainer">
                <img class="logo" src="assets/custom/img/logo.png"
                   width="150px" alt="Logo">
            </div>
            <div class="acceptContainer">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h1>WELCOME BACK!</h1>
                    <div class="formContainer">
                        <div class="formDiv">
                            <p>EMAIL</p>
                            <input type="email" name="Email" id="floatingInput" placeholder="name@example.com"
                                value="{{ old('Email') }}" required>
                        </div>
                        <div class="formDiv">
                            <p>PASSWORD</p>
                            <input type="password" name="Password" id="floatingPassword" placeholder="Password"
                                required>
                            <a class="forgotPas" href="#">FORGOT YOUR PASSWORD?</a>
                        </div>
                        <div class="formDiv">
                            <div class="form-check text-start my-2">
                                <input class="form-check-input" type="checkbox" value="remember-me" id="rememberMe">
                                <label class="form-check-label remember" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                        </div>
                        <div class="formDiv">
                            <button class="acceptBtn" type="submit">Login</button>
                            <span class="register">Need an account?<a
                                    href="{{ route('register.create') }}">Register</a></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            var images = [
                //image here
            ];

            $('#container').append(
                '<style>#container, .acceptContainer:before, #logoContainer:before {background: url(' + images[
                    Math.floor(Math.random() * images.length)] + ') center fixed }');

            setTimeout(function() {
                $('.logoContainer').css('transform', 'scale(1)');
                setTimeout(function() {
                    $('.logoContainer .logo').addClass('loadIn');
                    setTimeout(function() {
                        $('.logoContainer .text').addClass('loadIn');
                        setTimeout(function() {
                            $('.acceptContainer').css('height', '480.5px');
                            setTimeout(function() {
                                $('.acceptContainer').addClass('loadIn');
                                setTimeout(function() {
                                    $('.formDiv, form h1').addClass(
                                        'loadIn');
                                }, 500)
                            }, 500)
                        }, 500)
                    }, 500)
                }, 1000)
            }, 10)
        });
    </script>
    <script>
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
</body>

</html>
