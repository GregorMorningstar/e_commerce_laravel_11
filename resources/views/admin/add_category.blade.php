@extends('layouts.admin')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="main-content-wrap">
                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                    <h3>Brand Information</h3>
                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                        <li>
                            <a href="{{ route('admin.index') }}">
                                <div class="text-tiny">Dashboard</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories') }}">
                                <div class="text-tiny">Kategorie</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <div class="text-tiny">Nowa Kategoria</div>
                        </li>
                    </ul>
                </div>
                <!-- new-category -->
                <div class="wg-box">
                    <form class="form-new-product form-style-1" action="{{ route('admin.store_category') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <fieldset class="name">
                            <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                            <input class="flex-grow"
                                   type="text"
                                   placeholder="Category name"
                                   name="name"
                                   tabindex="0"
                                   value="{{ old('name') }}"
                                   aria-required="true"
                                   required="">
                            @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title">Category Slug <span class="tf-color-1">*</span></div>
                            <input class="flex-grow"
                                   type="text"
                                   placeholder="Category Slug"
                                   name="slug"
                                   tabindex="0"
                                   value="{{ old('slug') }}"
                                   aria-required="true"
                                   required="">
                            @error('slug')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <fieldset>
                            <div class="body-title">Upload Images <span class="tf-color-1">*</span></div>
                            <div class="upload-image flex-grow">
                                <div id="imgpreview" class="item preview-container" style="display: none;">
                                    <img id="preview" src="" class="effect8" alt="Image Preview">
                                </div>
                                <div id="upload-file" class="item up-load">
                                    <label class="uploadfile" for="myFile">
                <span class="icon">
                    <i class="icon-upload-cloud"></i>
                </span>
                                        <span class="body-text">Drop your images here or select
                    <span class="tf-color">click to browse</span>
                </span>
                                        <input type="file" id="myFile" name="image" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                </div>
                                @error('image')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset>
                        <div class="bot">
                            <div></div>
                            <button class="tf-button w208" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="bottom-page">
            <div class="body-text">Copyright © 2024 SurfsideMedia</div>
        </div>
    </div>
    <script>
        function previewImage(event) {
            const fileInput = event.target;
            const previewContainer = document.getElementById('imgpreview');
            const previewImage = document.getElementById('preview');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block'; // Pokaż podgląd
                };

                reader.readAsDataURL(fileInput.files[0]);
            } else {
                previewContainer.style.display = 'none'; // Ukryj podgląd, jeśli brak pliku
                previewImage.src = '';
            }
        }
    </script>
@endsection
