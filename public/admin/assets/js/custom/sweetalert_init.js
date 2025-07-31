$(function(){
        $(document).on('click', '#delete', function(e){
          e.preventDefault();
          var link = $(this).attr("href");

      Swal.fire({
          title: 'Are you sure?',
          text: "You want to delete, because it cannot be reverted!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = link
            Swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )
          }
        })

        });
      });

$(function(){
        $(document).on('click', '#create', function(e){
          e.preventDefault();
          var link = $(this).attr("href");

      Swal.fire({
          title: 'Are you sure?',
          text: "You are about to create a new financial year",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Go ahead!'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = link
            Swal.fire(
              'Create!',
              'Year Created!',
              'success'
            )
          }
        })

        });
      });