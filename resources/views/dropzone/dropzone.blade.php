<!DOCTYPE html>
<html>
<head>
      <title>Drag and Drop file upload with Dropzone in Laravel 8</title>

      <!-- Meta -->
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta charset="utf-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <meta name="_token" content="{{csrf_token()}}" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>

</head>
<body>

        
    
       <div class='content'>
              <!-- Dropzone -->
             <form action="{{route('upload_image')}}" class='dropzone' >
            </form> 
       </div>

       <!-- Script -->
       <script>
        const myArray = [];
            var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            Dropzone.autoDiscover = false;
            var myDropzone = new Dropzone(".dropzone",{ 
                    maxFilesize: 2, // 2 mb
                    acceptedFiles: ".jpeg,.jpg,.png,.pdf",
                    addRemoveLinks: true,
                    parallelUploads: 5,
                    maxFiles: 15,
                    maxFilesize: 1,
                    acceptedFiles: 'image/*',
            });
            myDropzone.on("sending", function(file, xhr, formData) {
                    formData.append("_token", CSRF_TOKEN);
            }); 
            myDropzone.on("success", function(file, response) {

            if(response.success == 0){ // Error
                  alert(response.error);
            }
            if(response.success == 1){ // Error
                alert(response.file_name);
                myArray.push(response.file_name);
                var jsonString = JSON.stringify(myArray);
                document.getElementById("myHiddenInput").value = jsonString;
            }

       });
       </script>

</body>
</html>