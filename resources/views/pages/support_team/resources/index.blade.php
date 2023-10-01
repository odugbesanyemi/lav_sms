@extends('layouts.master')
@section('page_title', 'Resources')
@section('content')
<style>
    .selected{
        border: 2px solid red;
    }
    label{
        margin: 0;
    }
    #resourceContainer ul{
        display: grid;
    }
    .tab-selection .active{
        background-color: #2c3e50 !important;
        color: white !important;
    }

</style>
<div class="position-relative" style="position:relative;">
    <div class="titl text-center">
        <div class="titl-content text-slate-800 pb-5 space-y-4">
            <h3 class="m-0 font-medium uppercase mb-2 text-red-500">Resource Repository</h3>
            <p class="m-0 md:w-9/12 mx-auto mt-2 ">Our library of digital resources is designed to assist both students and educators. Download files, assignments, and reference materials for a richer learning experience.</p>
            @if (Qs::userIsTeamSAT())
                <button class="button border p-2 px-2 pr-3 mx-auto rounded-lg bg-white flex items-center"  onclick="showModal()"><iconify-icon icon="mdi:add-box"></iconify-icon>Add New Resource</button>
            @endif
        </div>
    </div>
    <div class="bg-white border rounded-2xl overflow-hidden">
        <div class="tab-selection border-b ">
            <ul class="flex flex-wrap text-gray-700 justify-center gap-2 m-0 py-3 bg-slate-50" id="tab-parent">
                    <li><a href="#all"><label onclick="getResource()" for="all" class="active group hover:bg-slate-500 hover:text-slate-50 transition-all border text-slate-900  py-1 rounded-2xl px-3">All<input type="radio" class="hidden" name="resources" id="all"></label></a></li>
                @foreach ($resourceType as $rt)
                    <li><a href="#{{$rt->id}}"><label onclick="getResource('{{$rt->id}}')" for='{{$rt->id}}' class="hover:bg-slate-500 hover:text-slate-50 transition-all border text-slate-900 py-1 rounded-2xl px-3 group">{{ $rt->name }}<input type="radio" class="hidden" name="resources" id="{{$rt->id}}"></label></a></li>
                @endforeach
            </ul>
        </div>
        <div class="tab-display p-3 mx-auto text-gray-700" id="resourcesContainer">
            <div class="grid grid-cols-3 gap-3 p-2 max-md:grid-cols-1">
                <div role="status" class="max-w-sm p-4 border border-gray-200 rounded animate-pulse md:p-6 dark:border-gray-700">
                    <div class="flex items-center justify-center h-48 mb-4 bg-gray-300 rounded dark:bg-gray-700">
                        <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                            <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
                        </svg>
                    </div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                    <div class="flex items-center mt-4 space-x-3">
                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                        </svg>
                        <div>
                            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-2"></div>
                            <div class="w-48 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                        </div>
                    </div>
                    <span class="sr-only">Loading...</span>
                </div>
                <div role="status" class="max-w-sm p-4 border border-gray-200 rounded animate-pulse md:p-6 dark:border-gray-700">
                    <div class="flex items-center justify-center h-48 mb-4 bg-gray-300 rounded dark:bg-gray-700">
                        <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                            <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
                        </svg>
                    </div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                    <div class="flex items-center mt-4 space-x-3">
                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                        </svg>
                        <div>
                            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-2"></div>
                            <div class="w-48 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                        </div>
                    </div>
                    <span class="sr-only">Loading...</span>
                </div>
                <div role="status" class="max-w-sm p-4 border border-gray-200 rounded animate-pulse md:p-6 dark:border-gray-700">
                    <div class="flex items-center justify-center h-48 mb-4 bg-gray-300 rounded dark:bg-gray-700">
                        <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                            <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
                        </svg>
                    </div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                    <div class="flex items-center mt-4 space-x-3">
                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                        </svg>
                        <div>
                            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-2"></div>
                            <div class="w-48 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                        </div>
                    </div>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

        </div>

    </div>

    <div class="custom-modal relative" id="display-modal" style="display: none;">
        <span class="fixed z-10 bg-gray-500/50 top-0 left-0 w-full h-full"></span>
        <div class="bg-white border rounded-t-2xl rounded w-7/12 max-md:w-11/12 overflow-hidden mx-auto fixed top-36 left-1/2  -translate-x-1/2 z-20" id="add">
            <div class="title p-3 border-b text-gray-900 flex justify-between items-center"><span class="items-center flex"><iconify-icon icon="material-symbols:add-ad-sharp"></iconify-icon>Add new Resource</span> <button class="text-red-400" id="close_btn" onclick="hideModal()"><iconify-icon icon="bytesize:close" class="text-red-400"></iconify-icon></button></div>
            <form action="resource/create" enctype="multipart/form-data" id="create_form">
                @csrf
                <div class="flex flex-col overflow-y-auto h-96 md:p-5">
                    <div class="fileadd p-3">

                        <!-- <div class="border rounded overflow-hidden w-full">
                            <label for="uploader" class="p-3 w-full m-0 block flex items-center flex-1 flex-col justify-center bg-gradient-to-b from-slate-50 to-slate-100 rounded ">
                                <span class="block text-4xl mx-auto"><img src="{{asset('global_assets/images/folder_add.png')}}" class="w-24" alt=""></span>
                                <p class="text-gray-300 m-0 text-xl ">Click to Add Resource</p>
                            </label>
                            <input type="file" class="d-none file-input-handler" id="uploader" name="filename" required>
                        </div> -->

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
                    <div class="other info text-gray-700 p-3">
                        <div class="space-y-3 gap-3">
                            <div class="w-full">
                                <div>
                                    <label for="">Title <span class="text-red-400">*</span></label>
                                    <input name="title" type="text" required placeholder="Academic Year 2000 Past Question" class="form-control">
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <label for="">Description <span class="text-red-400">*</span></label>
                                    <textarea name="description" required id="" placeholder="resource description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="gap-3 mt-2 space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="">Select resource type <span class="text-red-400">*</span></label>
                                    <select name="resource_type_id" id="" required class="form-control">
                                        <option value="0">Select one</option>
                                        @foreach ($resourceType as $rt)
                                            <option value="{{ $rt->id }}">{{$rt->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="">Choose Subject <span class="text-red-400">*</span></label>
                                    <select name="subject_id" id="" required class="form-control">
                                        <option value="0">N/A</option>
                                        @foreach ($subjects as $s)
                                            <option value="{{ $s->id }}">{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <label for="">Choose display Image</label>
                                    <input required class="form-control" name="image" type="file" accept="jpg/png" placeholder="your text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="school_id" value="{{Qs::findActiveSchool()[0]->id}}">
                <input type="hidden" name="acad_year_id" value="{{Qs::getActiveAcademicYear()[0]->id}}">
                <div class="footer p-2 border-t">
                    <button type="submit" class="bg-blue-500 rounded-lg p-2 px-4">Submit</button
                </div>

            </form>

        </div>
    </div>

</div>
<script>
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var resources = [];
    function changeSelection(){
        $('#tab-parent li a label').click(function() {
        // Remove the 'active' class from all list items
        $('#tab-parent li a label').removeClass('active');

        // Add the 'active' class to the clicked list item
        $(this).addClass('active');
    });
    }
    function getResource(resource_id=null){
        changeSelection();
        $('#ajax-loader').show()
        var url = resource_id == null?`resource/all`:`resource/${resource_id}`;
        $.ajax({
            url:url,
            method:'get',
            success:(data)=>{
                var resourceContainer = $('#resourcesContainer')
                var noDataContainer = $('#noData')
                if(data.length >= 1){
                    var ul = $('<ul class="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 py-3"></ul>')
                    $.each(data,function(index,item){
                        var cardHtml = `
                            <li class="border relative rounded-lg overflow-hidden items-stretch h-full">
                                <div class="flex flex-col flex-1 h-full">
                                    <div class="card-img">
                                        <img src="{{ asset('storage/images/${item.image}') }}" class="w-full h-44 object-cover" alt="">
                                    </div>
                                    <div class="bg-white border-t flex flex-1 flex-col justify-between h-full">
                                        <div class="p-3 space-y-3">
                                            <h5 class="font-bold uppercase">${item.title}</h5>
                                            <p class="desc mb-2">${item.description}</p>
                                            <p class="space-y-2 m-0 flex item-center"><iconify-icon icon="clarity:date-line" class="text-green-400"></iconify-icon> ${new Date(item.created_at).toDateString()}</p>
                                        </div>
                                        <div class="border-t p-2 bg-slate-50">
                                            <p class="m-0  text-slate-700 rounded">Author:${item.author} </p>
                                        </div>
                                    </div>
                                    <div class="absolute top-4 right-1">
                                        <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <iconify-icon icon="icon-park-outline:more-two" class="text-2xl"></iconify-icon>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(Qs::userIsTeamSAT())
                                            <li><a class="dropdown-item flex item-center text-blue-500" href="resource/edit/${item.id}"><iconify-icon icon="uil:edit"></iconify-icon>Edit</a></li>
                                            <li onclick="confirmDelete('${item.id}')"><form id="item-delete-${item.id}" action="resource/delete/${item.id}" method="post"> @csrf @method('delete') <a class="dropdown-item flex item-center text-red-500" href="#"><iconify-icon icon="material-symbols:delete-outline"></iconify-icon>Delete</a></form></li>
                                            @endif
                                            <li><a class="dropdown-item flex item-center text-green-500" download href="{{ asset('storage/resources/${item.filename}') }}"><iconify-icon icon="material-symbols:download"></iconify-icon>Download</a></li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        `;
                        ul.append(cardHtml)
                    })
                    resourceContainer.html(ul)
                }else{
                    var noData = `
                    <div id="noData" class="text-center" >
                        <img class="w-25 max-md:w-full mx-auto " src="{{asset('global_assets/images/not_found.png')}}" alt="">
                        <h4 class="text-blue-500">Nothing Found!</h4>
                    </div>
                    `
                    resourceContainer.html(noData)
                }
                $('#ajax-loader').hide()
            },
            error:function(xhr,status,error){

            }
        })
    }
    function showModal(){
        $('#ajax-loader').show()
        var timeout = setTimeout(()=>{
            $('#ajax-loader').hide()
             $('#display-modal').show('slow',()=>{clearTimeout(timeout)})
        },1000)
        // clearTimeout(timeout)
    }
    function hideModal(){
        $('#display-modal').hide('slow')
    }
    function getInutFilename(){
        $('.file-input-handler').change(function(el){
            var file = $(this)[0].files[0];
            var displayName = $('.file-input-name')
            displayName.text(file.name);
        })
    }
    function submitCreateForm(){
        var form = $('#create_form')
        form.on('submit',function(e){
            $('#ajax-loader').show()
            e.preventDefault();
            var formData = new FormData(form[0])
            console.log(formData)
            $.ajax({
                url:'resource/create',
                type:'POST',
                data:formData,
                processData:false,
                contentType:false,
                success:function(){
                    $('#ajax-loader').hide()
                    getResource();
                    $('display-modal').hide();
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Handle any errors that occur during the AJAX request
                    console.error('Form submission failed:', errorThrown);
                    $('#ajax-loader').hide()
                }
            })
        })
    }
    function deleteResource(id){
        $('#ajax-loader').show()
        data = {};
        data._token = csrfToken
        $.ajax({
            url:`resource/delete/${id}`,
            data:data,
            method:"delete",
            success:function(){
                $('#ajax-loader').hide()
                getResource();
            },
            error: function(xhr, textStatus, errorThrown) {
                // Handle any errors that occur during the AJAX request
                console.error('error Deleting data:', errorThrown);
                $('#ajax-loader').hide()
            }
        })
    }
    function editResource(id){
        // get resource data
        var data = {};
        data._token = csrfToken
        $.ajax({
            url:`resource/${id}`,
            data:data,
            success:function(res){
                var editForm = $('#edit-form')
                // var formData = new FormData(editForm)
                $.each(res[0],function(key,value){
                    // console.log(key)
                    $('#edit-form[name="'+ key + '"]').val(value)
                });
                $('#edit-modal').show();
            }
        })
    }
    $(document).ready(function(){
        getInutFilename();
        getResource();
        submitCreateForm();
    })
</script>

    {{--Section List Ends--}}

@endsection
