// add new transaction
$(document).ready(function () {
    $('#newTransictionModal-submit-button').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission
        console.log('New Transaction')
        // Get form values
        const type = $('#categoryType').val();
        const categoryId = $('#parentCategory').val();
        const description = $('#description').val();
        const commission = $('#commission').val();
        const amount = parseFloat($('#amount').val());

        // Validate amount
        if (amount <= 1) {
            alert('Amount must be greater than 1.');
            return;
        }

        // Prepare data for the request
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const postUrl = $('#newTransictionForm').attr('action');
        console.log(postUrl)
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
            success: function (response) {
                // Handle success
                alert('Transaction added successfully!');
                location.reload(); // Reload the page or update the table dynamically
            },
            error: function (xhr) {
                // Handle errors
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    for (let key in errors) {
                        alert(`${key}: ${errors[key].join(', ')}`);
                    }
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });
});



$(document).ready(function() {
    // عند النقر على زر تعديل
    $('.btn-edit').on('click', function() {
        // استرجاع القيم من الزر
        const transactionId = $(this).data('id');
        const description = $(this).data('description');
        const amount = $(this).data('amount');
        const type = $(this).data('type');
        const commission = $(this).data('commission');
        const category = $(this).data('category');

        // تعبئة الحقول في النموذج
        $('#editTransactionForm').attr('action',
            `/transications/${transactionId}`); // قم بتحديث URL النموذج
        $('#editTransactionDescription').val(description);
        $('#editTransactionAmount').val(amount);
        $('#editTransactionCategory').val(category);
        $('#editTransactionType').val(type);
        $('#editTransactionCommission').val(commission);
        $('#editTransactionId').val(transactionId); // إضافة ID الترانزكشن
    });

    // عند النقر على زر حفظ التعديل
    $('#editTransactionForm-submit-button').on('click', function() {
        console.log('Button clicked');
    });
});

$(document).ready(function() {
    $('.btn-delete').on('click', function() {
        const transactionId = $(this).data('id');
        const transactionName = $(this).data('name');

        Swal.fire({
            title: `Are you sure you want to delete "${transactionName}"?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/transications/${transactionId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Transaction has been deleted.',
                            'success'
                        );
                        // Optional: Reload the page or remove the row
                        location.reload();
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Failed to delete the transaction.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});