<table>
    <thead>
    <tr>
        <th>Consecutivo</th>
        <th>Fecha</th>
        <th>Cliente identificación</th>
        <th>Cliente nombres</th>
        <th>Estado</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio unitario</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        @foreach($sale->saleDetails as $saleDetail)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->created_at }}</td>
                <td>{{ $sale->client->identification }}</td>
                <td>{{ $sale->client->full_names }}</td>
                <td>{{ $sale->status }}</td>
                <td>{{ $saleDetail->product_name }}</td>
                <td>{{ $saleDetail->quantity }}</td>
                <td>{{ $saleDetail->price_unit }}</td>
                <td>{{ $saleDetail->price_total }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>