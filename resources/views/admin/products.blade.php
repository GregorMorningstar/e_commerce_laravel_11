@extends('layouts.admin')
@section('content')
<div class="">

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All Products</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">All Products</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                       tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{route('admin.add_product')}}"><i
                            class="icon-plus"></i>Add new</a>
                </div>
                <div class="table-responsive-sm">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="w-10">#</th>
                            <th>Imię</th>
                            <th>Cena</th>
                            <th>Cena promocyjna</th>
                            <th>SKU</th>
                            <th>Kategoria</th>
                            <th>Marka</th>
                            <th>Wyróżniony</th>
                            <th>Zapasy</th>
                            <th>Ilość</th>
                            <th>Działanie</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @foreach($products as $product)
                            <td ca>{{$product->id}}</td>
                                <td class="pname">
                                    <div class="row">
                                        <!-- Obrazek zajmujący 50% szerokości -->
                                        <div class="col-6">
                                            @if($product->image)
                                                <div class="image-container">
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                                                </div>
                                            @else
                                                <p>No image available</p>
                                            @endif
                                        </div>

                                        <!-- Tekst zajmujący pozostałą część szerokości -->
                                        <div class="col-6">
                                            <div class="name">
                                                <a href="#" class="body-title-2">{{ $product->name }}</a>
                                                <div class="text-tiny mt-3">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>{{$product->regular_price}}</td>
                            <td>{{$product->sale_price}}</td>
                            <td>{{$product->SKU}}</td>
                            <td>{{$product->category->name}}</td>
                            <td>{{$product->brand->name}}</td>
                            <td>{{$product->featured ? "tak" : "nie"}}</td>
                            <td>{{$product->stock_status}}</td>
                            <td>{{$product->quantity}}</td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="#" target="_blank" class="mr-2"> <!-- Ustawienie marginesu po prawej -->
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </a>
                                        <a href="{{route('admin.edit_products',['id' => $product->id])}}" class="mr-2"> <!-- Ustawienie marginesu po prawej -->
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>


                                        <form action="{{ route('admin.delete_product', $product->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć ten produkt?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </form>


                                    </div>
                                </td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">


                </div>
            </div>
        </div>
    </div>



</div>

@endsection
