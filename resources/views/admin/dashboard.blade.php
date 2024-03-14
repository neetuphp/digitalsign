
@extends('layout')
@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
@endsection
@section('content')
<main>
    <div class="container">
        <div class="row justify-content-center p-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __(' Admin Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h2>Users</h2>

                        <table id="usersTable" class="display">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Digital Sign</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ strlen($user->digital_sign) > 30 ? substr($user->digital_sign, 0, 30) . '...' : $user->digital_sign }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary view-btn" data-toggle="modal" data-target="#viewModal" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-token="{{ $user->digital_sign }}">View</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="viewContent">
                        <!-- Details will be displayed here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<!-- Latest jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Latest Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function () {
        $('#usersTable').DataTable();

        // Handle click event for the View button
        $('.view-btn').click(function () {
            var name = $(this).data('name');
            var email = $(this).data('email');
            var token = $(this).data('token');

            // Update the content of the modal
            $('#viewContent').html('<p><strong>Name:</strong> ' + name + '</p><p><strong>Email:</strong> ' + email + '</p><textarea cols="50" rows="10">Token: ' + token + '</textarea>');

            // Show the modal
            $('#viewModal').modal('show');
        });
    });
</script>
@endsection
