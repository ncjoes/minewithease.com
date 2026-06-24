<!DOCTYPE html>
<html>
<head>
    <style>
        thead {
            font-weight: 600;
        }

        tbody tr, a {
            border: none;
            color: black;
        }

        tbody tr:nth-of-type(odd) {
            background-color: whitesmoke;
        }

        tbody tr:hover, tr:hover a {
            background-color: gray;
            color: white;
        }
    </style>
    <title>App. Routes!</title>
</head>
<body>
<h1 style="text-align: center; margin: 5vh 5vh">APP ROUTES</h1>
<div style="margin-bottom: 10vh;">
    <table border="1" cellpadding="3" cellspacing="0" style="margin: auto; min-width: 80%;">
        <thead>
        <tr>
            <td colspan="6"><h3 style="text-align: center">Route Table</h3></td>
        </tr>
        <tr>
            <td>Domain</td>
            <td>Methods</td>
            <td>URL Pattern</td>
            <td>Name</td>
            <td>Middleware</td>
            <td>Action</td>
        </tr>
        </thead>
        <tbody>
        @foreach ($routes as $route)
            @php
                $methods = array_filter($route->methods(), function ($e) {
                    return $e != 'HEAD';
                });
            $title=$route->getActionName();
            $parts = explode("\\",$title);
            $text = $parts[sizeof($parts)-1]
            @endphp
            <tr>
                <td>{{$route->getDomain()}}</td>
                <td>{{implode(', ', $methods)}}</td>
                <td>
                    <a href="{{implode('/',['http://'.$route->getDomain(),strlen((string)$route->uri())>1?$route->uri():''])}}"
                       target="_new">
                        {{$route->uri()}}
                    </a>
                </td>
                <td>{{$route->getName()}}</td>
                <td>{{implode(', ', $route->gatherMiddleware())}}</td>
                <td><a href="#{{$title}}" title="{{$title}}">{{$text}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
