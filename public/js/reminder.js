$(document).ready(function() {

    // --- SEND REMINDER LOGIC ---
    $(document).on('click', '.send-reminder', function() {
        var id = $(this).data('id');
        var btn = $(this);
        var originalText = btn.html(); // Button ka purana text save karein
        
        // CSRF Token lena zaroori hai alag file mein
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Agar meta tag nahi hai to input field se try karein
        if(!csrfToken) {
             csrfToken = $('input[name="_token"]').val();
        }

        // 1. Button ko Loading Mode mein dalein
        btn.html('<i class="fa fa-spinner fa-spin"></i> Sending...');
        btn.prop('disabled', true);

        // 2. AJAX Call (API Hit via JS)
        $.ajax({
            url: '/customer/recovery/reminder', // Yeh aapka Laravel Route hai
            type: 'POST',
            data: {
                id: id,
                _token: csrfToken // Token pass karna zaroori hai
            },
            success: function(response) {
                // Agar message chala gaya
                if(response.status === 'success') {
                    btn.removeClass('btn-warning').addClass('btn-success');
                    btn.html('<i class="fa fa-check"></i> Sent');
                    
                    // 2 second baad button wapis normal ho jaye
                    setTimeout(function(){
                        btn.html(originalText);
                        btn.removeClass('btn-success').addClass('btn-warning');
                        btn.prop('disabled', false);
                    }, 3000);
                    
                    // Optional: Alert dikhana ho to
                    // alert(response.message); 
                } else {
                    handleError(btn, originalText, response.message);
                }
            },
            error: function(xhr) {
                // Agar server ya code mein error aya
                var errorMsg = 'Connection Error';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                handleError(btn, originalText, errorMsg);
            }
        });
    });

    // Helper function error handle karne ke liye
    function handleError(btn, originalText, msg) {
        alert('Error: ' + msg);
        btn.html('<i class="fa fa-times"></i> Failed');
        setTimeout(function(){
            btn.html(originalText);
            btn.prop('disabled', false);
        }, 2000);
    }
});