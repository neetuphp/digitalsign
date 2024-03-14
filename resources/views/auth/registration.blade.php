@extends('layout')
  @section('style')
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css" />
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
  
    <style>
        .kbw-signature { width: 100%; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
        button#clear {
            margin-top: 14px;
        }
    </style>
    @endsection
@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Registeration for New Employee</div>
                  <div class="card-body">
                      <form action="{{ route('register.post') }}" id="registrationForm"  method="POST">
                          @csrf
                          <div class="form-group row">
                              <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                              <div class="col-md-6">
                                  <input type="text" id="name" class="form-control" name="name" required autofocus>
                                  @if ($errors->has('name'))
                                      <span class="text-danger">{{ $errors->first('name') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail:</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <!-- <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password" class="form-control" name="password" required>
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                          </div> -->
                          @if ($message = Session::get('success'))
                          <div class="alert alert-success  alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert">×</button>  
                              <strong>{{ $message }}</strong>
                          </div>
                      @endif
                          <div class="form-group row">
                           <div class="col-md-6 offset-md-3 mt-5 clearsign">
                               <div class="card">
                                   <div class="card-body">
                                            <div class="col-md-12">
                                                <label class="" for=""> Upload Signature:</label>
                                                <br/>
                                                <div id="sig" ></div>
                                                <br/>
                                                <button id="clear" class="btn btn-danger btn-sm">Clear Signature</button>
                                                <textarea id="signature64" name="digital_sign" style="display: none"></textarea>
                                            </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                          <!-- <div class="form-group row">
                              <div class="col-md-6 offset-md-4">
                                  <div class="checkbox">
                                      <label>
                                          <input type="checkbox" name="remember"> Remember Me
                                      </label>
                                  </div>
                              </div>
                          </div> -->
  
                          <div class="col-md-6 offset-md-4">
                              <button type="button" class="btn btn-primary" id="submitForm">
                                  Register
                              </button>
                          </div>
                      </form>
                        
                  </div>
              </div>
          </div>
      </div>
  </div>

</main>
<div class="modal" tabindex="-1" role="dialog" id="publicKeyModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="publicKeyModalLabel">Modal Title</h5>
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <div class="modal-body" id="publicKeyModalBody">
          <!-- Modal body content goes here -->
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
<script type="text/javascript">
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    $('#clear').click(function(e) {
        e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });

    $('#submitForm').click(function () {
        var signatureImage = sig.signature('toDataURL');
        
        var signatureValue = $("#signature64").val();
        if (!signatureValue.trim()) {
        // Signature is empty, show an error or prevent form submission
            alert('Please provide a signature.');
            return;
        }
        $("#signature64").val(signatureImage);

        // Prepare form data
        var formData = new FormData($('#registrationForm')[0]);

        // Ajax request
        $.ajax({
            type: 'POST',
            url: $('#registrationForm').attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#publicKeyModalLabel').html('Registration Successful');
                $('#publicKeyModalBody').html('<textarea rows="10" cols="50">' + data + '</textarea>');
                $('#publicKeyModal').modal('show');
                setTimeout(function() {
                  $('#publicKeyModal').modal('hide');
              }, 6000);

               // window.location.href = '/'; 
            },
            error: function (data) {
               alert('Error: The email has already been taken' );
                //$('#savedata').html('Save Changes');
            }
        });
        $('#publicKeyModal').on('hidden.bs.modal', function (e) {
            window.location.href = '/'; // Redirect to the login page
        });
    });
</script>
@endsection