@extends('frontend.master')
@section('content')
    <section class="section pt-5 pb-5 products-section">
        <div class="container">
            <div class="section-header text-center">
                <h2>Popular Restaurant</h2>
                <p>Top restaurants, cafes, pubs, and bars in Ludhiana, based on trends</p>
                <span class="line"></span>
            </div>
            <div class="row">

                @php
                    $clients = App\Models\Client::latest()->where('status', '1')->get();
                @endphp

                @foreach ($clients as $client)
                    @php
                        $products = App\Models\Product::where('client_id', $client->id)
                            ->limit(3)
                            ->get();
                        $menuNames = $products
                            ->map(function ($product) {
                                return $product->menu->menu_name;
                            })
                            ->toArray();
                        $menuNamesString = implode(' . ', $menuNames);

                        $coupons = App\Models\Coupon::where('client_id', $client->id)
                            ->where('status', '1')
                            ->first();
                    @endphp

                    <div class="col-md-3">
                        <div class="item pb-3">
                            <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                <div class="list-card-image">
                                    <div class="star position-absolute"><span class="badge badge-success"><i
                                                class="icofont-star"></i> 3.1 (300+)</span></div>
                                    <div class="favourite-heart text-danger position-absolute"><a aria-label="Add to Wishlist" onclick="addWishList({{$client->id}})"><i
                                                class="icofont-heart"></i></a></div>
                                    @if ($coupons)
                                        <div class="member-plan position-absolute"><span
                                                class="badge badge-dark">Promoted</span></div>
                                    @else
                                    @endif
                                </div>
                                <a href="{{ route('res.details', $client->id) }}">
                                    <img src="{{ asset('frontend/img/list/1.png') }}" class="img-fluid item-img">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1"><a href="{{ route('res.details', $client->id) }}" class="text-black">World Famous</a>
                                    </h6>
                                    <p class="text-gray mb-3">{{ $menuNamesString }}</p>
                                    <p class="text-gray mb-3 time"><span
                                            class="bg-light text-dark rounded-sm pl-2 pb-1 pt-1 pr-2"><i
                                                class="icofont-wall-clock"></i> 20–25 min</p>
                                </div>
                                <div class="list-card-badge">
                                    @if ($coupons)
                                        <span class="badge badge-success">OFFER</span> <small>{{ $coupons->discount }}% off
                                            | Use Coupon {{ $coupons->coupon_name }}</small>
                                    @else
                                        <span class="badge badge-success">OFFER</span> <small>Right Now There Have No
                                            Coupon</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            {{-- end col-md-3 --}}
            @endforeach

        </div>
        </div>
    </section>
@endsection
