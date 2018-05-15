<div class="content pt-10">
    <hr class="is-marginless">
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th>Product</th>
            <th>Qty</th>
            <th>Note</th>
        </tr>
        </thead>
        <tbody>
        @foreach($product->groupProducts as $gp)
        <tr>
            <td>
                <a href="{{ url('catalog/product/'.$gp->relatedProduct->uri) }}" target="_blank">
                    <img src="{{ $gp->relatedProduct->getProductDefaultImageUrl() }}" alt="{{ $gp->relatedProduct->name }}" class="image mb-10" style="height: 50px;">
                </a>
            </td>
            <td>
                <a  href="{{ url('catalog/product/'.$gp->relatedProduct->uri) }}" target="_blank">
                    {{ $gp->relatedProduct->name }}
                </a>
            </td>
            <td>{{ $gp->quantity }}</td>
            <td>{{ $gp->note }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>