@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-le flex-wrap justify-between gap20 mb-20">
                <h3>Brands</h3>
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
                        <div class="text-tiny">Brands</div>
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
                    <a class="tf-button style-1 w208" href="{{route('admin.add_brand')}}"><i
                            class="icon-plus"></i>Add new</a>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('info'))
                                <div class="alert alert-info">
                                    {{ session('info') }}
                                </div>
                            @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($brands as $item)

                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset('storage/uploads/brand/' . $item->image) }}" alt="{{ $item->name }}">
                                        </div>
                                        <div class="name">
                                            <a href="#" class="body-title-2">{{$item->name}}</a>
                                        </div>
                                    </td>
                                    <td>{{$item->slug}}</td>
                                    <td><a href="#" target="_blank">0</a></td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{route('admin.edit_brand', ['id' => $item->id])}}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.brand.delete', $item->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć tę markę?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">    <i class="icon-trash-2"></i></button>
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
{{$brands->links('pagination::bootstrap-5')}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
