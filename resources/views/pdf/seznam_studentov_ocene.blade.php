<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-študij FRI</title>

    <style>
        h1, h2, h3, h4, h5, h6 {
            font-family:  DejaVu Sans;
        }
        p, div {
            font-family: DejaVu Sans;
        }
        table {
            margin:0px;padding:0px;
            width:100%;
            border-collapse:collapse;
        }
        td {
            border-bottom: 0.5px solid #cccccc;
        }
        #footer {
            position:fixed;
            bottom:0;
        }
    </style>
</head>
<body>

    <h3>Seznam končnih ocen</h3>

    <p>Predmet: [{{$predmet->sifra}}] {{$predmet->naziv}}</p>
    <p>Izpraševalci: {{$nosilci}}</p>
    <p>Datum in ura: {{$datum}} ob {{ $ura  }}h</p>
    <p>Prostor: {{$prostor}}</p>

    <br>

    <div class="CSSTableGenerator">
        <table>
            <tr style="background-color: #cccccc">
                <td style="width: 50px"> </td>
                <td style="width: 150px">Vpisna št.</td>
                @if($imena == 1)
                <td style="width: 180px">Priimek in ime</td>
                @endif
                <td>Št. polaganj</td>
                <td style="width: 80px">Ocena</td>
            </tr>
            @foreach($studentje as $student)
                <tr>
                    <td> {{$student->zaporedna_st}} </td>
                    <td> {{$student->vpisna}} </td>
                    @if($imena == 1)
                    <td> {{$student->priimek}} {{$student->ime}} </td>
                    @endif
                    <td> {{$student->st_vseh}}</td>
                    @if($student->ocena != 0)
                        <td> {{$student->ocena}} </td>
                    @else
                        <td> \ </td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
    <div id="footer">1</div>
</body>
</html>