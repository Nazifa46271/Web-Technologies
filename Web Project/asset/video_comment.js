
        $(document).ready(function() {
            // Handle comment submission
            $('#comment-form').submit(function(e) {
                e.preventDefault();
                
                var commentText = $('textarea[name="comment_text"]').val();
                
                // Ajax request to submit comment
                $.ajax({
                    url: '../controller/video_details_controller.php',
                    type: 'POST',
                    data: {
                        submit_comment: true,
                        comment_text: commentText,
                    },
                    success: function(response) {
                        // Handle successful response
                        if (response.status === 'success') {
                            // Append new comment to the comment list
                            $('#comments-list').prepend(`
                                <div class="comment-item" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                                    <p><strong>${response.first_name} ${response.last_name}:</strong> ${response.comment_text}</p>
                                    <p><small>Posted on: ${response.comment_date}</small></p>
                                </div>
                            `);
                            $('textarea[name="comment_text"]').val(''); // Clear the comment input
                        } else {
                            alert('Comment submission failed!');
                        }
                    },
                    dataType: 'json'
                });
            });
        });
