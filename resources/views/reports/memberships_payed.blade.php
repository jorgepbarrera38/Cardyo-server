<table>
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Cliente identificación</th>
        <th>Cliente nombres</th>
        <th>Turno</th>
        <th>Membresía</th>
        <th>Cantidad</th>
        <th>Tipo</th>
        <th>Fecha inicio</th>
        <th>Fecha fin</th>
        <th>Precio unitario</th>
        <th>Precio Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($membershipsPayed as $membershipPayed)
        <tr>
            <td>{{ $membershipPayed->created_at }}</td>
            <td>{{ $membershipPayed->client->identification }}</td>
            <td>{{ $membershipPayed->client->full_names }}</td>
            <td>{{ $membershipPayed->turn->name }}</td>
            <td>{{ $membershipPayed->membershipType->name }}</td>
            <td>{{ $membershipPayed->quantity }}</td>
            <td>{{ $membershipPayed->membershipType->type }}</td>
            <td>{{ $membershipPayed->date_start }}</td>
            <td>{{ $membershipPayed->date_finish }}</td>
            <td>{{ $membershipPayed->price }}</td>
            <td>{{ $membershipPayed->total_price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>