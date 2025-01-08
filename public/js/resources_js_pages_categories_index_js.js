(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_pages_categories_index_js"],{

/***/ "./resources/js/pages/categories/index.js":
/*!************************************************!*\
  !*** ./resources/js/pages/categories/index.js ***!
  \************************************************/
/***/ (() => {

//************* add new category ***************
$(document).ready(function () {
  var submitButton = $('#newCategoryModal-submit-button');
  var categoryNameInput = $('#categoryName');
  var csrfToken = $('#csrf_token').val();
  var checkNameRoute = $('#check_name_route').val();
  var typingTimer;
  var typingDelay = 500;

  // Check the field after the user stops typing
  categoryNameInput.on('input', function () {
    clearTimeout(typingTimer); // Clear the previous timeout if the user is typing
    var categoryName = $(this).val();
    if (categoryName.length > 0) {
      typingTimer = setTimeout(function () {
        // Send the request after the user stops typing
        $.ajax({
          url: checkNameRoute,
          method: 'POST',
          data: {
            _token: csrfToken,
            // تمرير CSRF Token
            category_name: categoryName
          },
          success: function success(response) {
            if (response.exists) {
              categoryNameInput.addClass('is-invalid');
              $('#nameFeedback').text('This category name is already taken.');
              submitButton.prop('disabled', true);
            } else {
              categoryNameInput.removeClass('is-invalid').addClass('is-valid');
              $('#nameFeedback').text('');
              submitButton.prop('disabled', false);
            }
          }
        });
      }, typingDelay);
    } else {
      categoryNameInput.removeClass('is-invalid is-valid');
      $('#nameFeedback').text('');
      submitButton.prop('disabled', true);
    }
  });
});

//************* edit category ***************
$(document).ready(function () {
  var submitButton = $('#editCategoryModal-submit-button');
  var categoryNameInput = $('#editCategoryName');
  var csrfToken = $('#csrf_token_edit').val();
  var checkNameRoute = $('#check_name_route_edit').val();
  var updateRouteTemplate = $('#editCategoryUpdateRoute').data('url');
  var originalCategoryName = $('#editCategoryName').val(); // تخزين الاسم الأصلي
  var typingTimer;
  var typingDelay = 500;

  // Check the field after the user stops typing
  categoryNameInput.on('input', function () {
    var categoryName = $(this).val();

    // تحقق من الاسم فقط إذا كان قد تغير عن الاسم الأصلي
    if (categoryName !== originalCategoryName) {
      clearTimeout(typingTimer); // Clear the previous timeout if the user is typing

      if (categoryName.length > 0) {
        typingTimer = setTimeout(function () {
          // Send the request after the user stops typing
          $.ajax({
            url: checkNameRoute,
            method: 'POST',
            data: {
              _token: csrfToken,
              // تمرير CSRF Token
              category_name: categoryName
            },
            success: function success(response) {
              if (response.exists) {
                categoryNameInput.addClass('is-invalid');
                $('#editNameFeedback').text('This category name is already taken.');
                submitButton.prop('disabled', true);
              } else {
                categoryNameInput.removeClass('is-invalid').addClass('is-valid');
                $('#editNameFeedback').text('');
                submitButton.prop('disabled', false);
              }
            }
          });
        }, typingDelay);
      } else {
        categoryNameInput.removeClass('is-invalid is-valid');
        $('#editNameFeedback').text('');
        submitButton.prop('disabled', true);
      }
    } else {
      // إذا تم الرجوع للاسم الأصلي، أوقف التحقق وامسح رسالة الخطأ
      categoryNameInput.removeClass('is-invalid is-valid');
      $('#editNameFeedback').text('');
      submitButton.prop('disabled', false); // تمكين الزر عند العودة للاسم الأصلي
    }
  });

  // Populate the modal with category data when editing
  $(document).on('click', '.btn-edit', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var type = $(this).data('type');
    var parentId = $(this).data('parent-id');

    // استبدال :id في الرابط الديناميكي
    var updateUrl = updateRouteTemplate.replace(':id', id);
    $('#editCategoryForm').attr('action', updateUrl);

    // Populate form fields with current category data
    $('#editCategoryName').val(name || '');
    $('#editCategoryType').val(type || '');
    $('#editParentCategory').val(parentId || '');
    $('#editCategoryId').val(id);

    // تخزين الاسم الأصلي للفئة
    originalCategoryName = name || '';

    // Show the modal
    $('#editCategoryModal').modal('show');
  });
});

//************* delete category ***************
$(document).ready(function () {
  $(document).on('click', '.btn-delete', function () {
    var categoryId = $(this).data('id');
    var categoryName = $(this).data('name');
    var deleteUrl = $(this).data('url');
    Swal.fire({
      title: "Are you sure you want to delete \"".concat(categoryName, "\"?"),
      text: "This action cannot be undone.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!"
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: deleteUrl,
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            // CSRF Token
            _method: 'DELETE'
          },
          success: function success(response) {
            Swal.fire('Deleted!', response.message, 'success');
            location.reload();
          },
          error: function error() {
            Swal.fire('Error!', 'Something went wrong. Please try again later.', 'error');
          }
        });
      }
    });
  });
});

/***/ })

}]);