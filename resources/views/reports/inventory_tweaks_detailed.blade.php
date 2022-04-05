<table>
    <thead>
    <tr>
        <th>Consecutivo</th>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Producto</th>
        <th>Cantidad</th>
    </tr>
    </thead>
    <tbody>
    @foreach($inventoryTweaks as $inventoryTweak)
        @foreach($inventoryTweak->tweakDetails as $tweakDetail)
            <tr>
                <td>{{ $inventoryTweak->id }}</td>
                <td>{{ $inventoryTweak->created_at }}</td>
                <td>{{ $inventoryTweak->type }}</td>
                <td>{{ $tweakDetail->product->name }}</td>
                <td>{{ $tweakDetail->quantity }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>