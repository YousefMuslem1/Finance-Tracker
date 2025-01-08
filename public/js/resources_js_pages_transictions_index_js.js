(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_pages_transictions_index_js"],{

/***/ "./resources/js/pages/transictions/index.js":
/*!**************************************************!*\
  !*** ./resources/js/pages/transictions/index.js ***!
  \**************************************************/
/***/ (() => {

// add new transaction
$(document).ready(function () {
  $('#newTransictionModal-submit-button').on('click', function (e) {
    e.preventDefault(); // Prevent the default form submission
    console.log('New Transaction');
    // Get form values
    var type = $('#categoryType').val();
    var categoryId = $('#parentCategory').val();
    var description = $('#description').val();
    var commission = $('#commission').val();
    var amount = parseFloat($('#amount').val());

    // Validate amount
    if (amount <= 1) {
      alert('Amount must be greater than 1.');
      return;
    }

    // Prepare data for the request
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var postUrl = $('#newTransictionForm').attr('action');
    console.log(postUrl);
    // AJAX request
    $.ajax({
      url: postUrl,
      method: 'POST',
      data: {
        _token: csrfToken,
        type: type,
        category_id: categoryId,
        description: description,
        commission: commission,
        amount: amount
      },
      success: function success(response) {
        // Handle success
        alert('Transaction added successfully!');
        location.reload(); // Reload the page or update the table dynamically
      },
      error: function error(xhr) {
        // Handle errors
        var errors = xhr.responseJSON.errors;
        if (errors) {
          for (var key in errors) {
            alert("".concat(key, ": ").concat(errors[key].join(', ')));
          }
        } else {
          alert('An error occurred. Please try again.');
        }
      }
    });
  });
});
$(document).ready(function () {
  // عند النقر على زر تعديل
  $('.btn-edit').on('click', function () {
    // استرجاع القيم من الزر
    var transactionId = $(this).data('id');
    var description = $(this).data('description');
    var amount = $(this).data('amount');
    var type = $(this).data('type');
    var commission = $(this).data('commission');
    var category = $(this).data('category');

    // تعبئة الحقول في النموذج
    $('#editTransactionForm').attr('action', "/transications/".concat(transactionId)); // قم بتحديث URL النموذج
    $('#editTransactionDescription').val(description);
    $('#editTransactionAmount').val(amount);
    $('#editTransactionCategory').val(category);
    $('#editTransactionType').val(type);
    $('#editTransactionCommission').val(commission);
    $('#editTransactionId').val(transactionId); // إضافة ID الترانزكشن
  });

  // عند النقر على زر حفظ التعديل
  $('#editTransactionForm-submit-button').on('click', function () {
    console.log('Button clicked');
  });
});
$(document).ready(function () {
  $('.btn-delete').on('click', function () {
    var transactionId = $(this).data('id');
    var transactionName = $(this).data('name');
    Swal.fire({
      title: "Are you sure you want to delete \"".concat(transactionName, "\"?"),
      text: "This action cannot be undone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: "/transications/".concat(transactionId),
          type: 'DELETE',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function success(response) {
            Swal.fire('Deleted!', 'Transaction has been deleted.', 'success');
            // Optional: Reload the page or remove the row
            location.reload();
          },
          error: function error() {
            Swal.fire('Error!', 'Failed to delete the transaction.', 'error');
          }
        });
      }
    });
  });
});

/***/ })

}]);