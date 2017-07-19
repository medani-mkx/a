<h4>
    {{ $branch->address_line_1 }}@if ($branch->zip_code != '' || $branch->city != '') &#149;@endif {{ $branch->zip_code }} {{ $branch->city }}
</h4>
<p>
    <small>
        {{ $branch->code }}<br>
        {{ $branch->name }}@if ($branch->name_2 != ''), {{ $branch->name_2 }}@endif<br>
        {{ $branch->global_location_number }}
    </small>
</p>