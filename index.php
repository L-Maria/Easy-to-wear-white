<!DOCTYPE html>

<html>
    <head>
        <title> Easy to wear white </title>
        <style>
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body::before{
            content: "";
            background-image: url("images/background.jpg");
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .button{
            font-size: 2rem;
            box-shadow: 0px 0px 20px aqua;
            border-radius: 12px;
            background-color: aqua;
            padding: 10px 20px;
            width: 10%;
            text-align: center;
        }

        .button:hover{
            box-shadow: 0px 0px 50px;
        }
    </style>
    </head>
    <body>
            <div class="button">
                login
            </div>
    </body>
</html>