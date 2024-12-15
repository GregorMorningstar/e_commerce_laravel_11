@extends('layouts.admin')
@section('content')
    @if ($errors->any())
        <div>
            <h1 class="alert-danger body-text text-center">Popraw dane</h1>
        </div>
    @endif
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Add Product</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.products') }}"><div class="text-tiny">Products</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Add product</div></li>
                </ul>
            </div>

            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.store_product') }}">
                @csrf

                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter product name" name="name" value="{{ old('name') }}" required="">
                        @error('name')
                        <div class="alert-danger text-center">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" value="{{ old('slug') }}" required="">
                        @error('slug')
                        <div class="alert-danger text-center">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Choose category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="brand">
                            <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select id="brand_id" name="brand_id" required>
                                    <option value="" disabled selected>Choose Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('brand_id')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" required="">{{ old('short_description') }}</textarea>
                        @error('short_description')
                        <div class="alert-danger text-center">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="description" placeholder="Description" required="">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="alert-danger text-center">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>

                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload image <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*" onchange="previewImage(event)">
                                </label>
                                <!-- Podgląd wybranego obrazu -->
                                <div id="imagePreviewContainer" style="display:none;">
                                    <img id="imagePreview" src="#" alt="Image preview" style="max-width: 100%; margin-top: 10px;">
                                </div>
                            </div>
                        </div>
                        @error('image')
                        <div class="alert-danger text-center">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset>
                        <div class="body-title mb-10">Upload Gallery Images</div>
                        <div class="upload-image mb-16">
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="text-tiny">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple="" onchange="previewGalleryImages(event)">
                                </label>
                            </div>
                        </div>
                        <div id="galleryPreviewContainer" class="gallery-preview-container" style="display: none; margin-top: 10px;">
                            <p>Selected Images:</p>
                            <div id="galleryPreview" class="gallery-preview"></div>
                        </div>
                        @error('images')
                        <div class="alert-danger text-center">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" value="{{ old('regular_price') }}" required="">
                            @error('regular_price')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" value="{{ old('sale_price') }}" required="">
                            @error('sale_price')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" value="{{ old('SKU') }}" required="">
                            @error('SKU')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" value="{{ old('quantity') }}" required="">
                            @error('quantity')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Stock</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="instock" {{ old('stock_status') == 'instock' ? 'selected' : '' }}>InStock</option>
                                    <option value="outofstock" {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>
                            @error('stock_status')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title mb-10">Featured</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            @error('featured')
                            <div class="alert-danger text-center">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Add product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event) {
            const file = event.target.files[0]; // Wybierz pierwszy plik z listy
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewContainer = document.getElementById('imagePreviewContainer');
                const previewImage = document.getElementById('imagePreview');

                previewImage.src = e.target.result;  // Ustaw źródło obrazu na wynik wczytania
                previewContainer.style.display = 'block'; // Pokaż kontener z podglądem
            }

            if (file) {
                reader.readAsDataURL(file);  // Wczytaj obraz jako URL
            }
        }
        function previewGalleryImages(event) {
            const files = event.target.files; // Tablica wybranych plików
            const previewContainer = document.getElementById('galleryPreviewContainer');
            const previewGallery = document.getElementById('galleryPreview');

            previewGallery.innerHTML = '';  // Wyczyść poprzedni podgląd

            if (files.length > 0) {
                previewContainer.style.display = 'block';  // Pokaż kontener z podglądem
            } else {
                previewContainer.style.display = 'none';  // Ukryj kontener, jeśli nie ma plików
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;  // Ustaw źródło obrazu na wynik wczytania
                    img.style.maxWidth = '100px'; // Maksymalna szerokość miniaturki
                    img.style.marginRight = '10px'; // Odstęp między miniaturkami
                    previewGallery.appendChild(img);  // Dodaj miniaturkę do kontenera
                }

                if (file) {
                    reader.readAsDataURL(file);  // Wczytaj obraz jako URL
                }
            }
        }
    </script>
@endsection
