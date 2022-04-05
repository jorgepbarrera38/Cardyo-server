<table>
    <thead>
    <tr>
        <th>Consecutivo</th>
        <th>Fecha</th>
        <th>Cliente identificación</th>
        <th>Cliente nombres</th>
        <th>Estado</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        <tr>
            <td>{{ $sale->id }}</td>            
            <td>{{ $sale->created_at }}</td>            
            <td>{{ $sale->client->identification }}</td>            
            <td>{{ $sale->client->full_names }}</td>            
            <td>{{ $sale->status }}</td>            
            <td>{{ $sale->total }}</td>            
        </tr>
    @endforeach
    </tbody>
</table>