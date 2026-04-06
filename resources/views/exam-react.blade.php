<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CBT ONLINE</title>
    @include('partials.admin-latex-styles')
    @include('partials.latex-core')
    @vite(['resources/css/app.css', 'resources/js/exam-react.jsx'])
</head>
<body class="bg-gray-100">
    <div id="exam-app" data-user-timetable-id="{{ $userTimetableId }}"></div>
</body>
</html>
