@extends('app')

@section('content')
    @include('response')
    <div class="panel panel-default">
        <div class="panel-heading"><h3>Podatki o študentu {{$student->ime}} {{$student->priimek}}</h3></div>
        <div class="panel-body">
            <div>
                <p>Vpisna številka: {{$student->vpisna}}</p>
                <p>Ime: {{ $student->ime }}</p>
                <p>Priimek: {{ $student->priimek }}</p>
                <p>Telefon: {{ $student->telefon }}</p>
                <p>Email: {{ $student->email }}</p>
                @if (\Session::get('vloga') == "referent" )
                    <p>Spol: {{$student->spol}}</p>
                    <p>EMŠO: {{$student->emso}}</p>
                    <p>Davčna številka: {{$student->davcna}}</p>
                    <p>Datum rojstva: {{date('d.m.Y',strtotime($student->datum_rojstva))}}</p>
                    <p>Občina in država rojstva: {{$student->obcina_rojstva.', '.$student->drzava_rojstva}}</p>
                    <p>Naslov za pošiljanje pošte:
                    @if ($student->posiljanje != 0)
                        {{$student->naslovZacasni.', '.$student->postaZacasni.' '.$student->obcinaZacasni.', '.$student->drzavaZacasni}}
                    @else
                        {{$student->naslov.', '.$student->posta.' '.$student->obcina.', '.$student->drzava}}
                    @endif </p>
                    <p>Državljanstvo: {{$student->drzavljanstvo}}</p>
                    <p>Vnos ocene: <a href="{{ action('VnosOceneReferentController@vnesiOceno', ['id_studenta'=>$student->id]) }}">Klikni za vnos</a></p>
                    <p>Elektronski indeks: <a href="{{ action('StudentController@elektronskiIndeks', ['idStudenta'=>$student->id]) }}">Klikni za ogled</a></p>
                @endif
                <p>Kartotečni list: <a href="{{ action('KartotecniListController@prikazKartotecniList',['id'=>$student->id]) }}">Klikni za ogled</a></p>
            </div>

            <hr>

            <table class="table">
                <h4>Sklepi</h4>
                <tr>
                    <th>Id</th>
                    <th>Datum izdaje</th>
                    <th>Datum veljavnosti</th>
                    <th>Organ</th>
                    <th>Vsebina</th>
                    @if (\Session::get('vloga') == "referent" )
                        <th>Uredi</th>
                    @endif
                </tr>
                @foreach($student->sklepi as $sklep)
                    <tr>
                        <td>{{ $sklep->id }}</td>
                        <td>{{ date('d.m.Y',strtotime($sklep->datum_izdaje)) }}</td>
                        <td>{{ date('d.m.Y',strtotime($sklep->datum_veljavnosti)) }}</td>
                        <td>{{ $sklep->organ->ime }}</td>
                        <td>{{ $sklep->vsebina }}</td>
                        @if (\Session::get('vloga') == "referent" )
                            <td><a href="{{ action('SklepController@edit', ['idStudenta'=>$student->id, 'idSklepa'=>$sklep->id]) }}">Uredi sklep</a></td>
                        @endif
                    </tr>
                @endforeach
            </table>
            @if (\Session::get('vloga') == "referent" )
                <a href="{{ action('SklepController@create',['idStudenta'=>$student->id]) }}" class="btn btn-danger">Nov sklep</a>
            @endif

            <hr>

            @if (\Session::get('vloga') == "referent" )
                <table class="table">
                    <h4>Programi</h4>
                    @if(empty($studentProgrami))
                        <tr>
                            <td>Študent ni bil vpisan v noben program.</td>
                        </tr>
                    @else
                        <tr>
                            <th>Študijsko leto</th>
                            <th>Naziv programa</th>
                            <th>Letnik</th>
                            <th>Način študija</th>
                            <th>Vrsta vpisa</th>
                            <th>Stanje</th>
                            <th>Urejanje</th>
                            <th>Tisk</th>
                        </tr>
                    @endif
                    @foreach($studentProgrami as $sp)
                        <tr>
                            <td>{{ $sp->studijsko_leto }}</td>
                            <td>{{ $sp->studijski_program->ime }}</td>
                            <td>{{ $sp->letnik }}</td>
                            <td>{{ $sp->nacin_studija }}</td>
                            <td>{{ $sp->vrstaVpisa->ime }}</td>
                            <td>
                                @if(is_null($sp->vloga_oddana))
                                    Vloga še ni oddana.
                                @elseif(is_null($sp->vloga_potrjena))
                                    Vloga ni potrjena.
                                @else
                                    Vloga je potrjena.
                                @endif
                            </td>
                            <td>
                                @if($sp->studijsko_leto > date('Y',strtotime('-1 year')))
                                    <a href="{{ action('VpisniListReferentController@prikaziStudenta',['id_student_program'=>$sp->id]) }}">Uredi žeton</a>
                                @endif
                            </td>
                            <td>
                                @if($sp->vloga_potrjena != null && $sp->vloga_oddana != null)
                                    <a href="{{$sp->id_studenta}}/potrdila/{{$sp->id}}">Natisni potrdila</a>
                                @endif
                            </td>
                        </tr>

                    @endforeach
                </table>
                <a class="btn btn-danger" href="{{ action('StudentController@novZeton',['id'=>$student->id]) }}">Ustvari nov žeton</a>
        </div>
    </div>

            @elseif (\Session::get('vloga') == "ucitelj" )
                </div></div>

                <div class="panel panel-default">
                    <div class="panel-heading"><h3>Predmeti študenta {{$student->ime.' '.$student->priimek}} pri profesorju {{$ucitelj->ime.' '.$ucitelj->priimek}}</h3></div>
                    <div class="panel-body">
                        @if (!$predmeti->isEmpty())
                            @foreach($predmeti as $predmet)
                                <h4>Naziv predmeta: {{$predmet->predmet->naziv}}</h4>
                                <h4>Študijski program:
                                    @foreach($studentProgrami as $sp)
                                        @if ($sp->studijsko_leto == $predmet->studijsko_leto)
                                            {{$sp->studijski_program->ime}}</h4>
                                        @endif
                                    @endforeach
                                <h4>Študijsko leto: {{$predmet->studijsko_leto}}</h4>
                                <h4>Trenutna ocena: {{ ($predmet->ocena == 0)?'/':($predmet->ocena) }}</h4>
                                <?php
                                    $konec = DB::table('program_predmet')->where('studijsko_leto','=',$predmet->studijsko_leto)->where('id_predmeta','=',$predmet->id_predmeta)->first();
                                    if ($konec != null)
                                    {
                                        $konec=$konec->konec_predavanj;
                                    }
                                   ?>


                                <p>Vnos ocene:
                                @if ($konec != null && $konec > date('Y-m-d') )
                                    Ocene ni mogoče vnesti, saj konec predavanj nastopi z datumom {{date('d.m.Y',strtotime($konec))}}.
                                @elseif ($predmet->zakljucek == 0)
                                    <a href="{{ action('PredmetiUciteljController@vnesiOceno',['id'=>$predmet->id_predmeta, 'id_studenta'=>$student->id]) }}">Klikni za vnos</a>
                                @else
                                    Ocene ni mogoče vnesti.
                                @endif </p>

                                <p>Sprememba ocene:
                                    @if ($predmet->zakljucek != null && $predmet->zakljucek = 1)
                                        Ocene ni mogoče spremeniti.
                                    @elseif ($konec != null && $konec > date('Y-m-d') )
                                        Ocene ni mogoče spremeniti.
                                    @elseif ($predmet->datum_vnosa_ocene != null && (date('Y-m-d') < date('Y-m-d',strtotime($predmet->datum_vnosa_ocene.' + 30 days')) ) )
                                        <a href="{{ action('PredmetiUciteljController@vnesiOceno',['id'=>$predmet->id_predmeta, 'id_studenta'=>$student->id]) }}">Klikni za spremembo</a>
                                    @else
                                        Ocene ni mogoče spremeniti po 30 dneh vnosa.
                                    @endif </p>
                                <table class="table">
                                    <tr>
                                        <th width='200px'>Datum </th>
                                        <th width='200px'>Polaganje</th>
                                        <th width='200px'>Število točk izpita</th>
                                        <th width='200px'>Ocena</th>
                                    </tr>

                                    <?php $stevec=1; ?>

                                    @if ($student->polaganja()->where('id_predmeta','=',$predmet->id_predmeta)->get()->sortBy('datum')->isEmpty())
                                        <tr>
                                            <td>Pri predmetu še ni polaganj. </td>
                                        </tr>
                                    @else
                                        @foreach ($student->polaganja()->where('id_predmeta','=',$predmet->id_predmeta)->get()->sortBy('datum') as $polaganje)
                                            <tr>
                                                <td>{{date('d.m.Y',strtotime($polaganje->datum))}}</td>
                                                <td>{{$stevec++}}</td>
                                                <td>{{$polaganje->pivot->tocke_izpita}}</td>
                                                <td>{{($polaganje->pivot->ocena == 0)?'':$polaganje->pivot->ocena}}</td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </table><hr>
                            @endforeach
                            <p>Opozorilo: Ocene po 30 dneh vnosa ne morete več spreminjati.</p>
                        @else
                            <p>Študent pri tem profesorju še ni opravljal predmetov.</p>
                        @endif
                    </div>
                </div>

            @endif


@endsection