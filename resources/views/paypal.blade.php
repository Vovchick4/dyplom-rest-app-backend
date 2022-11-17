<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paypal</title>
</head>

<body>
    <div>
        <button type="confirm" class="order">Order</button>
    </div>
</body>

</html>

<script>
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('order')) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('client.orders.store') }}", true);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('Accept', 'application/json; charset=utf-8');

            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;

                let response = JSON.parse(this.responseText);
                console.log(response);

                if (response.data.link) {
                    window.open(response.data.link, '_blank').focus();
                }
            }

            let data = {
                payment_type: 'paypal',
                restaurant_id: 1,
                table: 1,
                is_online_payment: 1,
                plates: {
                    1: {
                        amount: 1,
                        price: 25
                    },
                    2: {
                        amount: 1,
                        price: 10
                    }
                }
            };

            xhr.send(JSON.stringify(data));
        }
    });
</script>
