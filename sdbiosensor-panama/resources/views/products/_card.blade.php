<a href="{{ route('products.show', $product->slug) }}" class="product-card">
    <div class="cert-badges">
        @foreach($product->certifications ?? [] as $cert)
            <span class="badge cert-{{ strtolower(str_replace(['_', ' '], '-', $cert)) }}">
                {{ match($cert) {
                    'CE'     => 'CE',
                    'KMFDS'  => 'KMFDS',
                    'WHO_PQ' => 'OMS PQ',
                    'TGA'    => 'TGA',
                    'FDA'    => 'FDA',
                    'IVDD'   => 'IVDD',
                    default  => $cert,
                } }}
            </span>
        @endforeach
    </div>
    <div class="product-image">
        @if($product->image_path)
            <img src="{{ media_url($product->image_path) }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <div class="img-placeholder">
                <i class="fas fa-flask fa-2x"></i>
            </div>
        @endif
    </div>
    <p class="product-category">{{ $product->category->name }}</p>
    <h4 class="product-name">{{ $product->name }}</h4>
</a>
