<html>
    <header>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"
    >
    </header>
    <body>
        <div style="display:flex; justify-content:center">
            <div>
                <div style="margin-bottom: 20px;">
                    <img src="{{env('APP_URL').'images/car.jpg'}}" width="300" height="150" />
                </div>
                <div style="display:flex; flex-direction:column; align-items:center">
                    <h1>PRINCEAK BLOG</h1>
                    <div style="margin-top: 20px">
                        <p>Hi <b>{{$name}}</b>
                        Sending Mail from Laravel.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>