<table>
    <thead>
    <tr>
        <th>Producto</th>
        <th>{{ $type == 'productsWithLittleStock' ? 'Stock mínimo' : 'Stock máximo' }}</th>
        <th>Stock actual</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $type == 'productsWithLittleStock' ? $product->stock_min : $product->stock_max }}</td>
            <td>{{ $product->stock }}</td>
        </tr>
    @endforeach
    </tbody>
</table>