@if(Qs::userIsTeamSAT())
    <div class="card shadow-none">
        <div class="card-header header-elements-inline bg-body-tertiary text-dark py-3">
            <h6 class="card-title font-weight-bold">Exam Comments</h6>
        </div>
        <div class="max-md:p-2 md:p-4">
            <form class="ajax-update" method="post" action="{{ route('marks.comment_update', $exr->id) }}">
                @csrf @method('PUT')

                @if(Qs::userIsTeamSAT())
                    <div class="form-group grid md:grid-cols-3 ">
                        <label class=" font-weight-semibold whitespace-nowrap">Teacher's Comment</label>
                        <div class="md:col-span-2">
                            <select name="t_comment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select Teacher's Comment</option>
                                @foreach ($remarks as $r)
                                    @php
                                        $nameArray = explode(' ', $sr->user->name);
                                        if(count($nameArray)<2){
                                            $firstName = $nameArray[0];
                                        }else{
                                            $firstName = $nameArray[1];
                                        }
                                        $updatedRemark = str_replace('[student]', $firstName, $r->remark);
                                    @endphp       
                                    @if($r->userType == 'teacher')                         
                                    <option {{ $exr->t_comment == $updatedRemark?'selected':'' }} value="{{ $updatedRemark }}">{{ $updatedRemark }}</option>   
                                    @endif             
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if(Qs::userIsTeamSA())
                    <div class="form-group grid md:grid-cols-3">
                        <label class=" font-weight-semibold whitespace-nowrap">Head Teacher's Comment</label>
                        <div class="md:col-span-2">
                            <select name="p_comment" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select Head Teacher's Comment</option>
                                @foreach ($remarks as $r)
                                    @php
                                        $nameArray = explode(' ', $sr->user->name);
                                        if(count($nameArray)<2){
                                            $firstName = $nameArray[0];
                                        }else{
                                            $firstName = $nameArray[1];
                                        }
                                        $updatedRemark = str_replace('[student]', $firstName, $r->remark);
                                    @endphp       
                                    @if($r->userType !== 'teacher')                         
                                    <option {{ $exr->p_comment == $updatedRemark?'selected':'' }} value="{{ $updatedRemark }}">{{ $updatedRemark }}</option>   
                                    @endif             
                                @endforeach
                            </select>                          
                        </div>
                    </div>
                @endif

                <div class="">
                    <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                </div>
            </form>
        </div>
    </div>
@endif
