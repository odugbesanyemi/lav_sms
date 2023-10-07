<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id]) }}" method="post">
    @csrf @method('put')
    <div class="search py-3 max-md:px-2">
        <label for="simple-search" class="sr-only">Search</label>
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                <i class="fi fi-rr-search text-xl text-slate-300 flex"></i>
            </div>
            <input oninput="searchData(0)" type="text" id="dataSearch0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quick Search...">
        </div>
    </div>    
    <div class="overflow-x-auto">
        <table class="table table-striped table-auto w-full">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>ADM_NO</th>
                    <th>CA ({{ $mp->ca_final_score?$mp->ca_final_score:40 }})</th>
                    <th>EXAM ({{ $mp->exam_final_score?$mp->exam_final_score:60 }})</th>
                </tr>
            </thead>
            <tbody id="data-container-0">

            </tbody>
        </table>        
    </div>


    <div class="mt-2">
        <button type="submit" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2  focus:outline-none flex items-center">Update Marks <i class="icon-paperplane ml-2 flex"></i></button>
    </div>
</form>
