<form method="post" action="{{ route('marks.selector') }}">
        @csrf
        <div class="space-y-3">
            <div class="">
                <fieldset>

                    <div class="grid md:grid-cols-2 flex-wrap items-end gap-3 lg:grid-flow-col-dense">
                        <div class="">
                            <div class="">
                                <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                                <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select">
                                    @foreach($exams as $ex)
                                        <option {{ $selected && $exam_id == $ex->id ? 'selected' : '' }} value="{{ $ex->id }}">{{ $ex->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                <select required onchange="getClassSubjects(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                    <option value="">Select Class</option>
                                    @foreach($my_classes as $c)
                                        <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                                <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                   @if($selected)
                                        @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                            <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                       @endif
                                </select>
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <label for="subject_id" class="col-form-label font-weight-bold">Subject:</label>
                                <select required id="subject_id" name="subject_id" data-placeholder="Select Class First" class="form-control select-search">
                                  @if($selected)
                                        @foreach($subjects->where('my_class_id', $my_class_id) as $s)
                                            <option {{ $subject_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                      @endif
                                </select>
                            </div>
                        </div>
                    </div>

                </fieldset>
            </div>

            <div class="">
                <div class="">
                    <button type="submit" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2  focus:outline-none flex items-center">Manage Marks <i class="icon-paperplane ml-2 flex"></i></button>
                </div>
            </div>

        </div>

    </form>
