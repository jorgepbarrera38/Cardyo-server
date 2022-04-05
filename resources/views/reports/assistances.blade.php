<table>
    <thead>
    <tr>
        <th>Cliente documento</th>
        <th>Cliente Nombres</th>
        <th>Membresía</th>
        <th>Turno</th>
        <th>Fecha</th>
    </tr>
    </thead>
    <tbody>
    @foreach($assistances as $assistance)
        <tr>
            <td>{{ $assistance->clientMembership->client->identification }}</td>
            <td>{{ $assistance->clientMembership->client->full_names }}</td>
            <td>{{ $assistance->clientMembership->membershipType->name }}</td>
            <td>{{ $assistance->turn->name }}</td>
            <td>{{ $assistance->date }}</td>            
        </tr>
    @endforeach
    </tbody>
</table>