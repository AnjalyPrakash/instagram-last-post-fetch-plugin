jQuery(document).ready(function($) {
    // Fetch Instagram post using AJAX
    function fetchInstagramPost() {
        var accessToken = $('#instagram-post').data('access-token');
        var postLink = $('#instagram-post').data('post-link');

        $.ajax({
            url: 'https://graph.instagram.com/me/media?fields=id,media_url,caption,media_type&access_token=' + accessToken,
            type: 'GET',
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    var lastPost = response.data[0]; // Get the last post

                    // Check if the last post is a video
                    if (lastPost.media_type === 'VIDEO') {
                        // Construct HTML for displaying the video
                        var videoHTML = `<video  controls>
                                            <source src="${lastPost.media_url}" type="video/mp4">
                                        Your browser does not support the video tag.
                                        </video>`;
                        var linkHTML = `<a href="${postLink}" target="_blank">${videoHTML}</a><br><h5>${lastPost.caption}</h5>`;
                        $('#instagram-post').html(linkHTML);
                    } else if (lastPost.media_type === 'IMAGE') {
                        // Construct HTML for displaying the image
                        var imageHTML = `<img src="${lastPost.media_url}" alt="${lastPost.caption}">`;
                        var linkHTML = `<a href="${postLink}" target="_blank">${imageHTML}</a><br><p>${lastPost.caption}</p>`;
                        $('#instagram-post').html(linkHTML);
                    } else {
                        $('#instagram-post').html('Unsupported media type.');
                    }
                } else {
                    $('#instagram-post').html('No Instagram post found.');
                }
            },
            error: function(error) {
                console.error('Error fetching Instagram post:', error);
                $('#instagram-post').html('Error fetching Instagram post.');
            }
        });
    }

    // Fetch Instagram post on page load
    fetchInstagramPost();
});
