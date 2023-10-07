<div class="grid md:grid-cols-2 gap-3 ">
    <div class="">
        <div class="card shadow-none flex justify-between">
            <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
                <h6 class="card-title font-weight-bold">AFFECTIVE TRAITS</h6>
                <div class="">
                    <select data-placeholder="Select" name="" id="afSelect" class="form-control select">
                        <option value=""></option>
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="p-2 md:p-4">
                <form class="ajax-update" id="afSelectForm" method="post" action="{{ route('marks.skills_update', ['AF', $exr->id]) }}">
                    @csrf @method('PUT')
                    @foreach($skills->where('skill_type', 'AF') as $af)
                        <div class="form-group row">
                            <label for="af" class="col-lg-6 col-form-label font-weight-semibold">{{ $af->name }}</label>
                            <div class="col-lg-6">
                                <select data-placeholder="Select" name="af[]" id="af" class="form-control ">
                                    <option value=""></option>
                                    @for($i=1; $i<=5; $i++)
                                        <option {{ $exr->af && explode(',', $exr->af)[$loop->index] == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>

                            </div>
                        </div>
                    @endforeach

                    <div class="">
                        <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="">
        <div class="card shadow-none">
            <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
                <h6 class="card-title font-weight-bold">PSYCHOMOTOR SKILLS</h6>
                <div class="">
                    <select data-placeholder="Select" name="" id="psSelect" class="form-control select">
                        <option value=""></option>
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="p-2">
                <form class="ajax-update" id="psSelectForm" method="post" action="{{ route('marks.skills_update', ['PS', $exr->id]) }}">
                    @csrf @method('PUT')
                    @foreach($skills->where('skill_type', 'PS') as $ps)
                        <div class="form-group row">
                            <label for="ps" class="col-lg-6 col-form-label font-weight-semibold">{{ $ps->name }}</label>
                            <div class="col-lg-6">
                                <select data-placeholder="Select" name="ps[]" id="ps" class="form-control">
                                    <option value=""></option>
                                    @for($i=1; $i<=5; $i++)
                                        <option {{ $exr->ps && explode(',', $exr->ps)[$loop->index] == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endforeach



                    <div class="">
                        <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
