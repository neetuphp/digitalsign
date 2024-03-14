@extends('layout')
  
  @section('style')
  <style>
    #clear{
      display:none;
    }
 /* #upload-button{
    display:none;
  }*/
</style>
  @endsection('style')
@section('content')
<main>
<div class="container">
    <div class="row justify-content-center p-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload file</div>
  
                <div class="card-body">
                     <label for="file-input" id="upload-button" class=" btn btn-primary custom-button">Upload pdf</label><a href="'+data.images+'"  class ="download" id ="download-button" download="document" style="display: none;">Download</a><input type="file" id="file-input" data-id ="'+data.ordernumber+'" accept=".png,.pdf" style="display: none;"><div id="image-preview"></div>
                     <button id="clear" class="btn btn-danger btn-sm">Clear Signature</button>


                    <!--  <div id="signature-pad" class="m-signature-pad">
                      <div class="m-signature-pad--body">
                        <canvas id="signature-canvas"></canvas>
                      </div>
                      <div class="m-signature-pad--footer">
                        <button type="button" class="btn btn-danger btn-sm" id="clear">Clear Signature</button>
                      </div>
                    </div> -->
                    <!--  <div class="col-md-12">
                                                <label class="" for=""> Upload Signature:</label>
                                                <br/>
                                                <div id="sig" ></div>
                                                <br/>
                                                <button id="clear" class="btn btn-danger btn-sm">Clear Signature</button>
                                                <textarea id="signature64" name="digital_sign" style="display: none"></textarea>
                                            </div> -->

                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection('content')
@section('script')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js"></script>
<script>
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    $('#clear').click(function(e) {
        e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });
$(document).ready(function() {
  const imagePreview = $('#image-preview');
  const fileInput = $('#file-input'); // Define the fileInput variable

  $(document).on('click', '#upload-button', function(e) {
    //e.preventDefault();
    fileInput.trigger('click'); // Trigger the file input programmatically
  });

  $(document).on('change', '#file-input', function() {
    //var imgres;
     var order_id = $(this).attr('data-id');
     var option = 'file';
    const files = this.files;
    if (files.length > 0) {
      const file = files[0];
      const fileType = file.type.toLowerCase();

      if (fileType === 'image/png') {
        var imgres;
        // Create a FileReader to read and display the selected image
        const reader = new FileReader();
        reader.onload = function(e) {
          // Clear any previous previews
          $('#image-preview').html('');

          // Preview the selected image
          const img = $('<img>');
         imgres = e.target.result;
           console.log(imgres);
          img.attr('src', e.target.result);
          img.css('max-width', '50%');
          img.css('height', 'auto');
          img.css('margin-top', '5px');
          $('#image-preview').append(img);

          
        };
        

        reader.readAsDataURL(file);
         
        
        //console.log(file);


      } else if (fileType === 'application/pdf') {
        // Preview PDF files
        const pdf = $('<embed>');
        pdf.attr('src', URL.createObjectURL(file));
        pdf.attr('type', 'application/pdf');
        pdf.css('width', '100%');
        pdf.css('height', '100vh');
        pdf.css('margin-top', '5px'); // Adjust the height as needed
        $('#image-preview').html(''); // Clear any previous previews
        $('#image-preview').append(pdf);
        const reader = new FileReader();
        reader.onload = function(e) {
        const pdfBase64 = e.target.result.split(',')[1];
        imgres = 'data:application/pdf;base64,' + pdfBase64;

          //console.log(pdfBase64);
        
    };
    reader.readAsDataURL(file);
      } else {
        //alert('dfghjkl');
        // Display an error message for unsupported file types
        alert('Unsupported file format. Please select a JPEG image.');
      }
    }
  });
});
</script>
@endsection
