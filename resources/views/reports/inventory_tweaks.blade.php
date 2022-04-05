<table>
    <thead>
    <tr>
        <th>Consecutivo</th>
        <th>Fecha</th>
        <th>Tipo</th>
    </tr>
    </thead>
    <tbody>
    @foreach($inventoryTweaks as $inventoryTweak)
        <tr>
            <td>{{ $inventoryTweak->id }}</td>
            <td>{{ $inventoryTweak->created_at }}</td>
            <td>{{ $inventoryTweak->type }}</td>
        </tr>
    @endforeach
    </tbody>
</table>