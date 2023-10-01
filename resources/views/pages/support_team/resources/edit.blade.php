@extends('layouts.master')
@section('page_title', 'Edit Resources')
@section('content')
<div class="relative" >
        <div class="" id="edit">
            <!-- <div class="title p-3 border-b text-gray-900 flex justify-between items-center items-center"><span class="items-center flex"><iconify-icon icon="material-symbols:add-ad-sharp"></iconify-icon>Edit Resource</span> <button class="text-red-400" id="edit_close_btn" onclick="hideModal()"><iconify-icon icon="bytesize:close" class="text-red-400"></iconify-icon></button></div> -->
            <form action="/resource/update/{{$resource->id}}" enctype="multipart/form-data" id="edit_form" method="post">
                @csrf @method('put')
                <div class="space-y-4">
                    <div class="fileadd">
                        <!--
                        <div class="p-3 w-full border-3">hello</div> -->
                        <div class="flex items-center justify-center w-full">
                            <label for="uploader" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, PDF, XLSX, DOC, DOCX</p>
                                </div>
                                <p class="w-full text-center rounded-b p-2 m-0 text-black file-input-name">File name when available</p>
                                <input type="file" class="d-none file-input-handler" id="uploader" name="filename" required>
                            </label>
                        </div>
                    </div>
                    <div class="other info text-gray-700">
                        <div class="space-y-3 gap-3">
                            <div class="w-full">
                                <div>
                                    <label for="">Title <span class="text-red-400">*</span></label>
                                    <input value="{{$resource->title}}" name="title" type="text" required placeholder="Academic Year 2000 Past Question" class="form-control">
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <label for="">Description <span class="text-red-400">*</span></label>
                                    <textarea value="" name="description" required id="" placeholder="resource description" class="form-control">{{$resource->description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="gap-3 mt-2 space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="">Select resource type </label>
                                    <select name="resource_type_id" id="" value class="form-control" {{ $resource->resource_type_id }}>
                                        <option value="0">Select one</option>
                                        @foreach ($resourceType as $rt)
                                            <option {{ $resource->resource_type_id == $rt->id?'selected':'' }} value="{{ $rt->id }}">{{$rt->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="">Choose Subject <span class="text-red-400">*</span></label>
                                    <select name="subject_id" id="" required class="form-control">
                                        <option value="0">N/A</option>
                                        @foreach ($subjects as $s)
                                            <option {{ $resource->subject_id == $s->id?'selected':'' }} value="{{ $s->id }}">{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <label for="">Choose display Image</label>
                                    <input class="form-control" name="image" type="file" accept="jpg/png" placeholder="your text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="school_id" value="{{$resource->school_id}}">
                <input type="hidden" name="acad_year_id" value="{{$resource->acad_year_id}}">
                <div class="footer mt-4 text-center">
                    <button type="submit" class="bg-blue-500 rounded-lg p-3 px-10 w-44 mx-auto text-blue-50">Update</button
                </div>

            </form>

        </div>
    </div>

@endsection
