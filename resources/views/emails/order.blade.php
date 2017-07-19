<h3><strong>Benutzer:</strong></h3>
<p><strong>Name:</strong><br>{{ $user->name }}</p>
<p><strong>Email:</strong><br>{{ $user->email }}</p>
<p><strong>Telefon:</strong><br>{{ $user->tel }}</p>
<hr>
<h3><strong>Einsatzstelle:</strong></h3>
<p><strong>Code:</strong><br>{{ $branch->code }}</p>
<p><strong>Debitorennummer:</strong><br>{{ $branch->customer_number }}</p>
<p><strong>Name:</strong><br>{{ $branch->name }}, {{ $branch->name_2 }}</p>
<p><strong>Adresse:</strong><br>{{ $branch->address_line_1 }}</p>
<p><strong>Zweite Adresse:</strong><br>{{ $branch->address_line_2 }}</p>
<p><strong>PLZ:</strong><br>{{ $branch->zip_code }}</p>
<p><strong>Ort:</strong><br>{{ $branch->city }}</p>
<p><strong>Bundesland:</strong><br>{{ $branch->province }}</p>
<p><strong>Staat:</strong><br>{{ $branch->country_code }}</p>
<p><strong>GLN:</strong><br>{{ $branch->global_location_number }}</p>
<hr>
<h3><strong>Vertrag:</strong></h3>
<p><strong>Code:</strong><br>{{ $contract->sub_contract_number }}</p>
<hr>
<h3><strong>Auftrag:</strong></h3>
<p><strong>Angefragt für:</strong><br>{{ $order->date }}</p>
<p><strong>Uhrzeit:</strong><br>{{ $order->time }}</p>
<p><strong>Nachricht:</strong><br>{{ $order->message }}</p>
<p><strong>Erstellungsdatum:</strong><br>{{ $order->created_at }}</p>
<p><strong>Datum der letzten Änderung:</strong><br>{{ $order->updated_at }}</p>