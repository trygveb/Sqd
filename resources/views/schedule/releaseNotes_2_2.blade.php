@extends('schedule.layout')

@section('content')


<div class="container">
    <h1 class="text-center"> {{ __('Release notes')}} for {{$names['application']}}  Version 2.2 </h1>
    <br>
     <div style="margin-left:auto;margin-right:auto;  width: 60%;  border: 3px solid #ca7300;  padding: 10px;">
      <h2>För Schemaadministratörer</h2>
      <ul>
          <li>Länken till sidan för att skapa nytt schema ligger numera under menyn med ditt namn</li>
          <li>Den sidan har fått följande nya fält</li>
         <ul>
             <li>Beskrivning (av schemat)</li>
            <li>"Namn_i_schema"</li>
         </ul>
          <li>På sidan "Mina scheman" kan du nu ändra namn och andra attribut för scheman som du administrerar</li>
          <li>Diverse hjälptexter inlagda</li>
          <li>Kvittering på lyckad uppdatering inlagd på vissa formulär</li>
          <li>Datum måste läggas till i tidsordning</li>
      </ul>
   </div>
    <br>
     <div style="margin-left:auto;margin-right:auto;  width: 60%;  border: 3px solid #ca7300;  padding: 10px;">
      <h2>För alla Användare</h2>
         <ul>
             <li>Fylligare information under menyvalet "Om"</li>
             <li>Sidan "Scheman" har bytt namn till "Mina scheman", och har fått lite mer information</li>
         </ul>
   </div>
@if ($isRoot)
   <br>
   <div style="margin-left:auto;margin-right:auto;  width: 60%;  border: 3px solid #ca7300;  padding: 10px;">
   <h2>Kommande förbättringar</h2>
      <ul>
           <li>Kvittering på lyckad uppdatering på fler formulär</li>
          <li>Möjlighet för Schemaadministratörer att ändra närvarostatus på alla schemamedlemmar</li>
          <li>Hjälptext på Hantera Datum</li>
      </ul>
   </div>
@endif
</div>
@endsection
