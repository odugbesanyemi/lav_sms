
@extends('layouts.master')
@section('page_title', 'Import Students')
@section('content')
@php
    $selected = isset($selected) ? true : false;
@endphp
<style >
    iconify{
        color: inherit !important;
    }
    #steps li.active{
        color: orange;
        border-color: orange;
    }
    #steps li.active span{
        border-color: orange !important;
    }
</style>
<div>
    <div class="title bg-white">
        <ol id="steps" class="flex items-center w-full p-3 space-x-2 text-sm font-medium text-center text-gray-500 bg-white rounded-lg dark:text-gray-400 sm:text-base dark:bg-gray-800 dark:border-gray-700 sm:p-4 sm:space-x-4">
            <li class="flex items-center active" id="options">
                <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
                    1
                </span>
                Options <span class="hidden sm:inline-flex sm:ml-2"></span>
                <svg class="w-3 h-3 ml-2 sm:ml-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
                </svg>
            </li>
            <li class="flex items-center" id="upload">
                <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                    2
                </span>
                Upload <span class="hidden sm:inline-flex sm:ml-2">File</span>
                <svg class="w-3 h-3 ml-2 sm:ml-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
                </svg>
            </li>
            <li class="flex items-center" id="confirm">
                <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                    3
                </span>
                Confirmation
            </li>
        </ol
    </div>
    <div class="content- border">
        <form action="/students/save/import" id="importForm" method="post" enctype="multipart/form-data">@csrf
            <ul id="steps-display">
                <li id="options-tab" style="display: none;">
                    <div class="border-b">
                        <div class="p-3 bg-white text-dark flex justify-between items-center">
                            <h6 class="card-title m-0">Options</h6>
                            <iconify-icon icon="ant-design:clear-outlined"></iconify-icon>
                        </div>
                    </div>
                    <div class="card-body bg-gray-50 ">
                        <div class="max-w-5xl mx-auto">
                            <div action="" class="form flex flex-col gap-4" >
                                <div class="">
                                    <label for="" class="block mb-2">Select Class</label>
                                    <select name="class_id" id="class_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">choose class</option>
                                        @foreach ($classes as $c )
                                            <option {{ ($selected && $class_id == $c->id)?'selected':'' }} value="{{ $c->id }}">{{$c->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="" class="block mb-2">Select Class Section</label>
                                    <select name="section_id" id="section" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">N/A</option>
                                        @if($selected)
                                        @foreach ($sections as $s )
                                           <option value="{{$s->id}}">{{ $s->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <input type="hidden" name="acad_year_id" value="{{Qs::getActiveAcademicYear()[0]->id}}">
                                <input type="hidden" name="school_id" value="{{Qs::findActiveSchool()[0]->id}}">
                                <div class="flex gap-2 items-center">
                                    <!-- <button type="button" class="text-white bg-gradient-to-b from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Save</button> -->
                                    <button onclick="nextTab()" type="button" class="py-2 px-3 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 flex gap-1 items-center justify-between">Next <iconify-icon icon="solar:arrow-right-line-duotone" class="text-blue-500"></iconify-icon></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li id="upload-tab" style="display: none;">
                    <div class="border-b">
                        <div class="p-3 bg-white text-dark flex justify-between items-center">
                            <h6 class="card-title m-0">Upload File</h6>
                            <iconify-icon icon="ant-design:clear-outlined"></iconify-icon>
                        </div>
                    </div>
                    <div class="card-body bg-gray-50 ">
                        <div class="max-w-5xl mx-auto">
                            <div class="form flex flex-col gap-4" >
                                <div class="text-center mx-auto">
                                    <h3 class="flex items-center gap-2">Don't Have the template? Click here*  <button class="bg-indigo-500 text-indigo-50 rounded text-sm p-2 hover:bg-indigo-600 transition-all">Download Template</button></h3>
                                    <span class="text-red-500">* Make sure you are using student record template file</span>
                                </div>
                                <div class="flex items-center justify-center w-full">
                                    <label for="template" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">CSV, XLSX</p>
                                        </div>
                                        <input type="file" class="d-none file-input-handler" id="template" name="filename" accept=".csv,.xlsx">
                                    </label>
                                </div>
                                <div class="uploaded">
                                    <div class="flex border rounded overflow-hidden items-center justify-between file-input-name" style="display: none;">
                                        <span class="text-green-400 p-2 border-r bg-white"><iconify-icon icon="ic:twotone-check" color="green"></iconify-icon></span>
                                        <span class="px-4 mr-auto content">Hello world</span>
                                        <span class="p-2"><iconify-icon icon="ant-design:clear-outlined"></iconify-icon></span>
                                    </div>
                                </div>
                                <div class="flex gap-2 items-center flex-wrap">
                                    <button onclick="prevTab()" type="button" class="py-2 px-3 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 flex gap-1 items-center justify-between"> <iconify-icon icon="solar:arrow-left-line-duotone" class="text-blue-500"></iconify-icon></button>
                                    <button id="submitBtn" type="submit"  class="text-white bg-gradient-to-b from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li id="confirm-tab" style="display: none;">
                    <div class="border-b">
                        <div class="p-3 bg-white text-dark flex justify-between items-center">
                            <h6 class="card-title m-0">Confirmation</h6>
                        </div>
                    </div>
                    <div class="card-body bg-gray-50 ">
                        <div class="max-w-5xl mx-auto">
                            <div class="form flex flex-col gap-4" >
                                <div class="text-center mx-auto">
                                    <h2 class="text-2xl">Almost Done!</h1>
                                    <span class="text-green-500">You are about to import the following Students into class:
                                    </span>
                                </div>

                                <div class="uploaded" id="showStudents">

                                </div>
                                <div class="flex gap-2 items-center">
                                    <button onclick="prevTab()" type="button" class="py-2 px-3 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 flex gap-1 items-center justify-between"> <iconify-icon icon="solar:arrow-left-line-duotone" class="text-blue-500"></iconify-icon></button>
                                    <button type="button" id="saveBtn" class="text-white bg-gradient-to-b from-orange-500 via-orange-600 to-orange-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Save Data</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>
        </form>


    </div>
</div>

<script>
    var steps = $('#steps li')
    var stepsdisplay = $('#steps-display li')
    displaySteps();
    var originalData = [];
    var tempData = [];
    function displaySteps(){
        steps.each(function(index,el){
            if(el.classList.contains('active')){
                var activeId = el.id
                stepsdisplay.each(function(index,elem){
                    if(`${activeId}-tab` == elem.id){
                        $(elem).show()
                    }else{
                        $(elem).hide()
                    }
                })
            }
        })
    }
    function getInputFilename(){
        $('.file-input-handler').change(function(el){
            var file = $(this)[0].files[0];
            var displayName = $('.file-input-name')
            displayName.show()
            var displayNameContent = $('.file-input-name .content')
            displayNameContent.text(file.name);
        })
    }
    function stepCheck(step_id=null){
        return true;
    }
    function findActiveStep(){
        var data ={};
        steps.each(function(index,el){
            if(el.classList.contains('active')){
                data = {'index':index,'id':el.id}
            }
        })
        return data;
    }

    function removeActiveStep(step_index){
        steps.each(function (index,el) {
            if(index == step_index){
                $(el).removeClass('active');
            }
         })
    }
    function setActive(step_index){
        steps.each(function(index,el){
            if(index==step_index){
                $(el).addClass('active');
            }
        })
    }

    function nextTab(){
        if(stepCheck()){
            var currentStep=findActiveStep();
            removeActiveStep(currentStep.index);
            setActive(currentStep.index+1);
            displaySteps();
        }else{
            // send error
            return;}
        // check if step is done
    }
    function prevTab(){
        var currentStep=findActiveStep();
        removeActiveStep(currentStep.index);
        setActive(currentStep.index-1);
        displaySteps();
    }

    function selectClass(class_id){
        console.log(class_id)

    }
    function removeRecord(index){
        var newArray = tempData.slice(0, index).concat(tempData.slice(index + 1));
        tempData = newArray;
        displayStudent(newArray);
    }
    function resetRecord(){
        displayStudent(originalData)
    }
    function displayStudent(_array){
        var recordContainer = $('#showStudents')
        var ul = $('<div class="grid grid-cols-1 gap-2"></div>')
        $.each(_array,function(index,data){
            ul.append(
                `
                    <div class="flex border rounded overflow-hidden items-center justify-between">
                        <span class="text-green-400 p-2 border-r bg-white"><iconify-icon icon="ic:twotone-check" color="green"></iconify-icon></span>
                        <span class="px-4 mr-auto">${data.firstName} ${data.lastName}</span>
                        <button type="button" class="p-2 ripple-dark" onclick="removeRecord(${index})"><iconify-icon icon="ep:delete"></iconify-icon></button>
                    </div>
                `
            )
        })
        recordContainer.html(ul);
    }
    $(document).ready(function(){
        var form = $('#importForm')
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        getInputFilename();
        $('#class_id').change(function(e,el){
            var selected = $(this).val();
            var url = '/students/class'
            form.attr('action',url);
            form.attr('method','post');
            form.submit();
        })
        $('#submitBtn').on('click',async(ev)=>{
            var btn = form.find('button[type=submit]');
            var data = new FormData(form[0]);
            data.append('_token',csrfToken)
            data._token = csrfToken;
            disableBtn(btn);
            await $.ajax({
                url:`/students/save/import`,
                method:"post",
                processData:false,
                dataType:'json',
                contentType:false,
                data:data,
                success:(data)=>{
                    originalData = tempData = data.student_records;
                    enableBtn(btn);
                    enableBtn($('#saveBtn'));
                    nextTab();
                    displayStudent(data.student_records);
                    // flash({msg :'something Went Wrong. Please Try Again', type : 'warning'});

                },
                error:function(e,status,error){
                    enableBtn(btn);
                    if(e.status == 500){
                        displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
                    }
                }
            })
        })
        $('#saveBtn').on('click',function(){
            var btn = $(this);
            var data = new FormData(form[0]);
            data.append('_token',csrfToken)
            data.delete('filename')
            data._token = csrfToken;
            data.append('student_record',JSON.stringify(tempData));
            disableBtn(btn);
            $.ajax({
                url:`/students/save/import`,
                method:"post",
                processData:false,
                dataType:'json',
                contentType:false,
                data:data,
                success:(data)=>{
                    // enableBtn(btn);
                    pop({msg :'Import Complete: Successfull', type : 'success'});
                    originalData = tempData = [];
                    displayStudent(tempData)
                },
                error:function(xhr,status,error){
                    console.log(xhr,status,error)
                    enableBtn(btn);
                    if(xhr.status == 500){
                        displayAjaxErr([xhr.status + ' ' + xhr.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
                    }
                }
            })
        })
    })

</script>

@endsection
