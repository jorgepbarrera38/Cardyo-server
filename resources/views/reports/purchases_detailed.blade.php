<table>
    <thead>
    <tr>
        <th>Consecutivo</th>
        <th>Fecha</th>
        <th>Proveedor código</th>
        <th>Proveedor Nombre</th>
        <th>Estado</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio unitario</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($purchases as $purchase)
        @foreach($purchase->purchaseDetails as $purchaseDetail)
            <tr>
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->created_at }}</td>
                <td>{{ $purchase->provider->code }}</td>
                <td>{{ $purchase->provider->name }}</td>
                <td>{{ $purchase->status }}</td>
                <td>{{ $purchaseDetail->product->name }}</td>  
                <td>{{ $purchaseDetail->quantity }}</td>  
                <td>{{ $purchaseDetail->price_unit }}</td>  
                <td>{{ $purchaseDetail->price_total }}</td>  
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>