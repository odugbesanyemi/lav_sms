@extends('layouts.master')
@section('page_title', 'Student Payments')
@section('content')
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
            <h5 class="card-title"><i class="icon-cash2 mr-2"></i> Student Payments</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('payments.select_class') }}">
                @csrf
              <div class="">
                  <div class="md:w-7/12 mx-auto">
                  <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                      <div class="flex items-center gap-3 justify-between">
                          <div class="flex-1">
                              <div class="">
                                  <select required id="my_class_id" name="my_class_id" class="form-control select">
                                      <option value="">Select Class</option>
                                      @foreach($my_classes as $c)
                                          <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>

                          <div class="">
                              <div class="">
                                  <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                              </div>
                          </div>

                      </div>
                  </div>
              </div>

            </form>
        </div>
    </div>
    @if($selected)
    <div class="card">
        <div class="card-body overflow-x-auto">
            <input id="table-filter" type="text" placeholder="Filter">
            <table class="table table-auto">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>ADM_No</th>
                    <th>Payments</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ $s->user->photo }}" alt="photo"></td>
                        <td>{{ $s->user->name }}</td>
                        <td>{{ $s->adm_no }}</td>
                        <td>
                            <div class="dropdown">
                                <a href="#" class=" btn btn-danger" data-toggle="dropdown"> Manage Payments <i class="icon-arrow-down5"></i>
                                </a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <a href="{{ route('payments.invoice', [Qs::hash($s->user_id)]) }}" class="dropdown-item">All Payments</a>
                            @foreach(Pay::getYears($s->user_id) as $py)
                            @if($py)
                                <a href="{{ route('payments.invoice', [Qs::hash($s->user_id), $py]) }}" class="dropdown-item">{{ $py }}</a>
                            @endif
                            @endforeach
                        </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @endif
    <script>
        $(document).ready(function () {
            // Function to filter table rows based on input value
            function filterTable() {
                var filterValue = $('#table-filter').val().toLowerCase(); // Get the filter value
                $('table tbody tr').each(function () {
                    // Check each row's content
                    var rowText = $(this).text().toLowerCase();
                    if (rowText.includes(filterValue)) {
                        $(this).show(); // Show the row if it matches the filter
                    } else {
                        $(this).hide(); // Hide the row if it doesn't match
                    }
                });
            }

            // Attach an event listener to the input field for filtering
            $('#table-filter').on('input', filterTable);
        });
    </script>
@endsection
