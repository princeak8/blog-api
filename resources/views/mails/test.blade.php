<html>
    <header>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"
    >
    </header>
    <body>
        <div>
            <div>
                <div style="margin-bottom: 20px;">
                    <img src="{{env('APP_URL').'images/car.jpg'}}" width="300" height="150" />
                </div>
                <div>
                    <div style="margin-bottom: 20px; width:100%">
                        <h1>PRINCEAK BLOG</h1>
                    </div>
                    <div style="margin-top: 20px; width:100%">
                        <p>Hi <b>{{$name}}</b>
                        Sending Mail from Laravel.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>