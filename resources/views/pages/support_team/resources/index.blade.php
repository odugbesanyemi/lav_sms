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


        </div>

    </div>
    <div class="custom-modal relative" id="display-modal" style="display: none;">
        <span class="fixed z-10 bg-gray-500/50 top-0 left-0 w-full h-full"></span>
        <div class="bg-white border rounded-t-2xl rounded w-7/12 max-md:w-11/12 overflow-hidden mx-auto fixed top-36 left-1/2  -translate-x-1/2 z-20" id="add">
            <div class="title p-3 border-b text-gray-900 flex justify-between items-center items-center"><span class="items-center flex"><iconify-icon icon="material-symbols:add-ad-sharp"></iconify-icon>Add new Resource</span> <button class="text-red-400" id="close_btn" onclick="hideModal()"><iconify-icon icon="bytesize:close" class="text-red-400"></iconify-icon></button></div>
            <form action="resource/create" enctype="multipart/form-data" id="create_form">
                @csrf
                <div class="flex flex-col overflow-y-auto h-96 md:p-5">
                    <div class="fileadd p-3">
                        <!--
                        <div class="p-3 w-full border-3">hello</div> -->
                        <div class="border rounded overflow-hidden w-full">
                            <label for="uploader" class="p-3 w-full m-0 block flex items-center flex-1 flex-col justify-center bg-gradient-to-b from-slate-50 to-slate-100 rounded ">
                                <span class="block text-4xl mx-auto"><img src="{{asset('global_assets/images/folder_add.png')}}" class="w-24" alt=""></span>
                                <p class="text-gray-300 m-0 text-xl ">Click to Add Resource</p>
                            </label>
                            <p class="bg-slate-200 rounded-b p-2 m-0 text-black file-input-name">File name when available</p>
                            <input type="file" class="d-none file-input-handler" id="uploader" name="filename" required>
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
                            <div class="w-full">
                                <div>
                                    <label for="">Select resource type <span class="text-red-400">*</span></label>
                                    <select name="resource_type_id" id="" required class="form-control">
                                        <option value="1">Select one</option>
                                        @foreach ($resourceType as $rt)
                                            <option value="{{ $rt->id }}">{{$rt->name}}</option>
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
    <div class="custom-modal relative" id="edit-modal" style="display: none;">
        <span class="fixed z-10 bg-gray-500/50 top-0 left-0 w-full h-full"></span>
        <div class="bg-white border rounded-t-2xl rounded w-7/12 max-md:w-11/12 overflow-hidden mx-auto fixed top-36 left-1/2  -translate-x-1/2 z-20" id="add">
            <div class="title p-3 border-b text-gray-900 flex justify-between items-center items-center"><span class="items-center flex"><iconify-icon icon="material-symbols:add-ad-sharp"></iconify-icon>Add new Resource</span> <button class="text-red-400" id="close_btn" onclick="hideModal()"><iconify-icon icon="bytesize:close" class="text-red-400"></iconify-icon></button></div>
            <form action="resource/create" enctype="multipart/form-data" id="create_form">
                @csrf
                <div class="flex flex-col overflow-y-auto h-96 md:p-5">
                    <div class="fileadd p-3">
                        <!--
                        <div class="p-3 w-full border-3">hello</div> -->
                        <div class="border rounded overflow-hidden w-full">
                            <label for="uploader" class="p-3 w-full m-0 block flex items-center flex-1 flex-col justify-center bg-gradient-to-b from-slate-50 to-slate-100 rounded ">
                                <span class="block text-4xl mx-auto"><img src="{{asset('global_assets/images/folder_add.png')}}" class="w-24" alt=""></span>
                                <p class="text-gray-300 m-0 text-xl ">Click to Add Resource</p>
                            </label>
                            <p class="bg-slate-200 rounded-b p-2 m-0 text-black file-input-name">File name when available</p>
                            <input type="file" class="d-none file-input-handler" id="uploader" name="filename" >
                        </div>
                    </div>
                    <div class="other info text-gray-700 p-3">
                        <div class="space-y-3 gap-3">
                            <div class="w-full">
                                <div>
                                    <label for="">Title <span class="text-red-400">*</span></label>
                                    <input value="" name="title" type="text" required placeholder="Academic Year 2000 Past Question" class="form-control">
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <label for="">Description <span class="text-red-400">*</span></label>
                                    <textarea value="" name="description" required id="" placeholder="resource description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="gap-3 mt-2 space-y-3">
                            <div class="w-full">
                                <div>
                                    <label for="">Select resource type <span class="text-red-400">*</span></label>
                                    <select name="resource_type_id" id="" value class="form-control">
                                        <option value="1">Select one</option>
                                        @foreach ($resourceType as $rt)
                                            <option value="{{ $rt->id }}">{{$rt->name}}</option>
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
                    var ul = $('<ul class="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 flex-wrap gap-3 py-3"></ul>')
                    $.each(data,function(index,item){
                        var cardHtml = `
                            <li class="border relative rounded-lg overflow-hidden">
                                <div class="">
                                    <div class="card-img">
                                        <img src="{{ asset('storage/images/${item.image}') }}" class="w-full h-44 object-cover" alt="">
                                    </div>
                                    <div class=" p-3 bg-white">
                                        <h5 class="font-bold uppercase">${item.title}</h5>
                                        <p class="desc">${item.description}</p>
                                        <p class="space-y-2 m-0 flex item-center"><iconify-icon icon="clarity:date-line" class="text-green-400"></iconify-icon> ${new Date(item.created_at).toDateString()}</p>
                                    </div>
                                    <div class="absolute top-4 right-1">
                                        <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <iconify-icon icon="icon-park-outline:more-two" class="text-2xl"></iconify-icon>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(Qs::userIsTeamSAT())
                                            <li onclick="editResource('${item.id}')"><a class="dropdown-item flex item-center text-blue-500" href="#"><iconify-icon icon="uil:edit"></iconify-icon>Edit</a></li>
                                            <li onclick="deleteResource('${item.id}')"><a class="dropdown-item flex item-center text-red-500" href="#"><iconify-icon icon="material-symbols:delete-outline"></iconify-icon>Delete</a></li>
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
        },2000)
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
    $(document).ready(function(){
        getInutFilename();
        getResource();
        submitCreateForm();
    })
</script>

    {{--Section List Ends--}}

@endsection
