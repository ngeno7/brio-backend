<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Brio Email</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="w-full flex flex-wrap justify-center bg-gray-200">
        <div class="p-6 w-1/2 bg-blue-300 text-center">
            <h5 class="text-2xl">Reset Password</h5>
            <h6 class="text-sm">This is your verification code</h6>
            <p class="my-1 text-gray-700">Code: {{$code}}</p><br><br><br>
            <h5 class="font-medium mt-5">Regards From Brio Team</h5>
        </div>
    </div>
</body>
</html>