<table>
    <thead>
    <tr>
        <th>Consecutivo</th>
        <th>Fecha</th>
        <th>Proveedor código</th>
        <th>Proveedor Nombre</th>
        <th>Estado</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($purchases as $purchase)
        <tr>
            <td>{{ $purchase->id }}</td>
            <td>{{ $purchase->created_at }}</td>
            <td>{{ $purchase->provider->code }}</td>
            <td>{{ $purchase->provider->name }}</td>
            <td>{{ $purchase->status }}</td>
            <td>{{ $purchase->total }}</td>            
        </tr>
    @endforeach
    </tbody>
</table>