{{-- opd_patient_search.blade.php --}}
@if(!is_null($users) && $users->isNotEmpty())
    <div class="search-results mt-4">
        <h4>Search Results</h4>
        <table class="table table-striped">
            <thead class="bg-warning text-black">
                <tr>
                    <th class="fw-bold">Name</th>
                    <th class="fw-bold">Phone</th>
                    <th class="fw-bold">Insurance Number</th>
                    <th class="fw-bold">Email</th>
                    <th class="fw-bold">Location</th>
                    <th class="fw-bold">Department</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->insurance_number }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->location }}</td>
                        <td>{{ $user->department ? $user->department->name : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@elseif(!is_null($users))
    <p>No results found for your search.</p>
@endif
