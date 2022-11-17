<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <div>
        <input type="text" placeholder="Email" value="test@gmail.com" class="email-reg">
        <input type="text" placeholder="Name" value="Test" class="name-reg">
        <input type="text" placeholder="Password" value="11223344" class="password-reg">
        <button type="confirm" class="register">Регистрация</button>
    </div>

    <br>
    <hr>
    <br>

    <div>
        <input type="text" placeholder="Email" class="email" value="test@gmail.com">
        <input type="text" placeholder="Password" class="password" value="11223344">
        <button type="confirm" class="login">Логин</button>
    </div>
    <br>
    <hr>
    <br>

    <div>
        <button type="confirm" class="orders" data-token="">Get orders</button>
    </div>
</body>

</html>

<script>
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('register')) {
            let data = {
                email: $('.email-reg').val(),
                password: $('.password-reg').val(),
                name: $('.name-reg').val()
            };
            data = JSON.stringify(data);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('client.register') }}", true);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('Accept', 'application/json; charset=utf-8');

            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;

                console.log('Register response', this.responseText);
            }

            xhr.send(data);
        }

        if (event.target.classList.contains('login')) {
            let data = {
                email_or_phone: $('.email').val(),
                password: $('.password').val()
            };
            data = JSON.stringify(data);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('client.login') }}", true);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('Accept', 'application/json; charset=utf-8');

            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;

                console.log('Login response', this.responseText);
                let response = JSON.parse(this.responseText);
                let token = response.data.accessToken;

                $('.orders').data('token', token);
                console.log(token);
            }

            xhr.send(data);
        }

        if (event.target.classList.contains('orders')) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "{{ route('client.orders.index') }}", true);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('Accept', 'application/json; charset=utf-8');

            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;

                console.log(this.responseText);
            }

            xhr.send();
        }
    });
</script>
