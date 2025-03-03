/**
 * Admin JavaScript for Elyamani Slider
 */
(function ($) {
    'use strict';

    // Initialize tabs
    function initTabs() {
        $('.elyamani-slider-tabs').tabs();
    }

    // Initialize slide selection
    function initSlideSelection() {
        // Toggle slide selection
        $('.elyamani-slide-item').on('click', function () {
            $(this).toggleClass('selected');
            updateSelectedSlides();
        });

        // Initialize selected slides
        updateSelectedSlides();

        // Make selected slides sortable
        $('.elyamani-selected-slides-list').sortable({
            update: function () {
                updateSelectedSlides();
            }
        });

        // Remove selected slide
        $(document).on('click', '.elyamani-selected-slide .remove', function (e) {
            e.preventDefault();
            const slideId = $(this).data('slide-id');

            // Deselect the slide in the selection area
            $(`.elyamani-slide-item[data-slide-id="${slideId}"]`).removeClass('selected');

            // Remove from selected area
            $(this).parent().remove();

            // Update hidden input
            updateSelectedSlides();
        });
    }

    // Update selected slides and hidden input
    function updateSelectedSlides() {
        const selectedSlides = [];
        const selectedSlidesContainer = $('.elyamani-selected-slides-list');

        // Clear the container
        selectedSlidesContainer.empty();

        // Add selected slides to the container and array
        $('.elyamani-slide-item.selected').each(function () {
            const slideId = $(this).data('slide-id');
            const slideTitle = $(this).data('slide-title');
            const slideImage = $(this).find('img').attr('src');

            selectedSlides.push(slideId);

            // Add to the selected slides container
            selectedSlidesContainer.append(`
                <div class="elyamani-selected-slide" data-slide-id="${slideId}">
                    <span class="remove" data-slide-id="${slideId}">×</span>
                    <img src="${slideImage}" alt="${slideTitle}">
                    <div class="title">${slideTitle}</div>
                </div>
            `);
        });

        // Update the hidden input
        $('#elyamani_slider_slides').val(selectedSlides.join(','));

        // Show/hide the empty message
        if (selectedSlides.length === 0) {
            $('.elyamani-selected-slides-empty').show();
        } else {
            $('.elyamani-selected-slides-empty').hide();
        }
    }

    // Initialize slider preview
    function initSliderPreview() {
        $('.elyamani-slider-preview').slick({
            dots: true,
            arrows: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            adaptiveHeight: true
        });
    }

    // Handle form submission
    function handleFormSubmission() {
        $('#elyamani-slider-form').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitButton = form.find('button[type="submit"]');
            const formData = new FormData(form[0]);

            // Add slider settings
            const settings = {
                autoplay: $('#elyamani_slider_autoplay').is(':checked'),
                autoplay_speed: parseInt($('#elyamani_slider_autoplay_speed').val()),
                speed: parseInt($('#elyamani_slider_speed').val()),
                arrows: $('#elyamani_slider_arrows').is(':checked'),
                dots: $('#elyamani_slider_dots').is(':checked'),
                infinite: $('#elyamani_slider_infinite').is(':checked'),
                fade: $('#elyamani_slider_fade').is(':checked'),
                adaptiveHeight: $('#elyamani_slider_adaptive_height').is(':checked'),
                slidesToShow: parseInt($('#elyamani_slider_slides_to_show').val()),
                slidesToScroll: parseInt($('#elyamani_slider_slides_to_scroll').val())
            };

            formData.append('settings', JSON.stringify(settings));

            // Get selected slides
            const slides = $('#elyamani_slider_slides').val().split(',').filter(Boolean).map(Number);

            // Remove existing slides entries and add as array
            formData.delete('slides');
            slides.forEach(slideId => {
                formData.append('slides[]', slideId);
            });

            // Add action and nonce
            formData.append('action', form.data('action'));
            formData.append('nonce', elyamani_slider.nonce);

            // Disable submit button
            submitButton.prop('disabled', true).text('Saving...');

            // Submit form via AJAX
            $.ajax({
                url: elyamani_slider.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        alert(response.data.message);

                        if (response.data.redirect) {
                            window.location.href = response.data.redirect;
                        }
                    } else {
                        alert(response.data.message || elyamani_slider.strings.error);
                        submitButton.prop('disabled', false).text('Save Slider');
                    }
                },
                error: function () {
                    alert(elyamani_slider.strings.error);
                    submitButton.prop('disabled', false).text('Save Slider');
                }
            });
        });
    }

    // Handle slider deletion
    function handleSliderDeletion() {
        $('.elyamani-delete-slider').on('click', function (e) {
            e.preventDefault();

            if (!confirm(elyamani_slider.strings.confirm_delete)) {
                return;
            }

            const button = $(this);
            const sliderId = button.data('slider-id');

            $.ajax({
                url: elyamani_slider.ajax_url,
                type: 'POST',
                data: {
                    action: 'elyamani_delete_slider',
                    id: sliderId,
                    nonce: elyamani_slider.nonce
                },
                success: function (response) {
                    if (response.success) {
                        alert(response.data.message);
                        button.closest('tr').remove();
                    } else {
                        alert(response.data.message || elyamani_slider.strings.error);
                    }
                },
                error: function () {
                    alert(elyamani_slider.strings.error);
                }
            });
        });
    }

    // Initialize on document ready
    $(document).ready(function () {
        initTabs();
        initSlideSelection();
        initSliderPreview();
        handleFormSubmission();
        handleSliderDeletion();
    });

})(jQuery);