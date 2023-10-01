<form method="post" action="{{ route('students.promote_selector') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <fieldset>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fc" class="col-form-label font-weight-bold">From Class:</label>
                            <select required onchange="getClassSections(this.value, '#fs')" id="fc" name="fc" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                    <option {{ ($selected && $fc == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fs" class="col-form-label font-weight-bold">From Section:</label>
                            <select required id="fs" name="fs" data-placeholder="Select Class First" class="form-control select">
                                @if($selected && $fs)
                                    <option value="{{ $fs }}">{{ $sections->where('id', $fs)->first()->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tc" class="col-form-label font-weight-bold">To Class:</label>
                            <select required onchange="getClassSections(this.value, '#ts')" id="tc" name="tc" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                    <option {{ ($selected && $tc == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ts" class="col-form-label font-weight-bold">To Section:</label>
                            <select required id="ts" name="ts" data-placeholder="Select Class First" class="form-control select">
                                @if($selected && $ts)
                                    <option value="{{ $ts }}">{{ $sections->where('id', $ts)->first()->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                </div>

            </fieldset>
        </div>
        <div class="">
            <div class=" mt-4">
                <div class="text-right mt-1">
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-400 text-white flex items-center gap-2 rounded whitespace-nowrap">Manage Promotion <i class="icon-paperplane ml-2"></i></button>
                </div>
            </div>

        </div>

    </div>

</form>
