<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.ico') }}"/>
	<title>E-študij FRI</title>

	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('multi-select/css/multi-select.css') }}">

    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('bootstrap-3.3.4-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-te-1.4.0.min.js') }}"></script>
    <script src="{{ asset('multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('js/functions.js') }}"></script>

</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            @if (\Session::get('vloga') == "referent" )
                <div class="navbar-header">
                    <a class="navbar-brand">Pozdravljeni, {{\Session::get('imepriimek')}} (referent)</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('WelcomeController@index') }}">Odjava</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('StudijskiProgramController@index') }}">Programi</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('IzpitniRokController@getSpremeniIzpitniRok') }}">Izpitni roki</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('PredmetController@index') }}">Predmeti</a>
                </div>
                <div class="nav navbar-nav navbar-right dropdown">
                    <a class="navbar-brand" href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Študenti</a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ action('HomeController@datoteka') }}">Uvoz novih študentov</a></li>
                        <li><a href="{{ action('VpisniListController@seznamVlog') }}">Potrdi vpisane študente</a></li>
                        <li><a href="{{ action('StudentController@searchForm') }}">Podatki o študentih</a></li>
                        <li><a href="{{ action('ListStudentsController@getStudents') }}">Seznam študentov po predmetih</a></li>
                        <li><a href="{{ action('ListStudentsController@getAdvSeznam') }}">Seznam študentov - napredno iskanje</a></li>
                        <li><a href="{{ action('StanjeVpisaController@index') }}">Stanje vpisa</a></li>
                        <li><a href="{{ action('VpisniListReferentController@obrazecVpisniList') }}">Vpiši študenta</a></li>
                    </ul>
                </div>

            @elseif (\Session::get('vloga') == "student" )
                <div class="navbar-header">
                    <a class="navbar-brand">Pozdravljeni, {{\Session::get('imepriimek')}} (študent)</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('WelcomeController@index') }}">Odjava</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('IzpitController@mojiRazpisaniRoki')  }}">Izpitni roki</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('VpisniListController@obrazecVpisniList')  }}">Vpisni list</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('KartotecniListController@prikazKartotecniList', ['id'=>\Session::get('id')])  }}">Kartotečni list</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('ElektronskiIndeksController@prikazEIndeks', ['id'=>\Session::get('id')])  }}">Elektronski indeks</a>
                </div>
            @elseif (\Session::get('vloga') == "ucitelj" )
                <div class="navbar-header">
                    <a class="navbar-brand">Pozdravljeni, {{\Session::get('imepriimek')}} (učitelj)</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('WelcomeController@index') }}">Odjava</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('PredmetiUciteljController@vrniPredmete') }}">Moji predmeti</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('IzpitniRokController@getSpremeniIzpitniRok') }}">Izpitni roki</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('StudentController@searchForm') }}">Podatki o študentih</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('ListStudentsController@getAdvSeznam') }}">Seznam študentov - napredno iskanje</a>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <a class="navbar-brand" href="{{ action('StanjeVpisaController@index') }}">Stanje vpisa</a>
                </div>
            @endif

        </div>
    </nav>

	@yield('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
</body>
</html>
