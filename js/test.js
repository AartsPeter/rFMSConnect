        $.(function() {
            let mainWrapper = '.dragSortableItems',
                in_available_fields = '.in_available_fields',
                selectedDropzone = '.selectedDropzone',
                input_name = 'name';

            // On ready
            $(document).ready(function() {

                const dragableMultiselect = $('.dragableMultiselect');
                dragableMultiselect.length && dragableMultiselect.each((index, value) => {
                    const $this = $(value);

                    const available_fields = $.extend({}, $this.data('options'));
                    const selected_fields = $.extend([], $this.data('selected'));
                    const $input_name = $this.attr(input_name);
                    let fieldTitle = $this.data('field-title');
                    let selectedTitle = $this.data('selected-title');

                    let html = '<div class="row dragSortableItems dragSortableItem_' + index + '">\
                    <div class="col-sm-6">\
                      <div class="card">\
                        <div class="card-header">' + fieldTitle + '</div>\
                        <div class="card-body">\
                          <ul class="in_available_fields custom-scrollbar sortable-list fixed-panel ui-sortable"></ul>\
                        </div>\
                      </div>\
                    </div>\
                    <div class="col-sm-6">\
                      <div class="card primaryPanel">\
                        <div class="card-header">' + selectedTitle + '</div>\
                        <div class="card-body">\
                          <div class="alert alert-warning small text-center mb-0">No Fields Selected</div>\
                          <ul class="in_primary_fields sortable-list selectedDropzone fixed-panel"></ul>\
                        </div>\
                      </div>\
                    </div>\
                  </div>';
                    $this.replaceWith(html);
                    $dragSortableItem = $('.dragSortableItem_' + index);

                    let $mainWrapper = $dragSortableItem.closest(mainWrapper),
                        $in_available_fields = $mainWrapper.find(in_available_fields),
                        $selectedDropzone = $mainWrapper.find(selectedDropzone);


                    console.log(available_fields, selected_fields, $mainWrapper, $in_available_fields, $selectedDropzone, $input_name);

                    Object.keys(available_fields).forEach(function(key) {
                        var item = '<li class="sortable-item allowPrimary sortable-item-' + key + '" data-fid="' + key + '">'
                            + '<span class="icon-drag fas fa-grip-vertical mr-2"></span>'
                            + '<input type="checkbox" name="' + $input_name + '" class="sortable-item-input"/>'
                            + available_fields[key]
                            + '</li>';
                        $in_available_fields.append(item);
                    });

                    selected_fields.map(function(index) {
                        var item = $in_available_fields.find('.sortable-item-' + index);
                        item.find('.sortable-item-input').prop('checked', true);
                        $selectedDropzone.append(item);
                    });
                    checkFields( $mainWrapper );

                    // Set up our dropzone
                    $in_available_fields.sortable({
                        connectWith: '.sortable-list',
                        placeholder: 'placeholder',
                        start: function(event, ui) {
                            if (!$(ui.item).hasClass("allowPrimary")) {
                                $mainWrapper.find(".primaryPanel").removeClass('panel-primary').addClass("panel-danger");
                            }
                            checkFields( $mainWrapper )
                        },
                        receive: function(event, ui) {
                            $(ui.item).find('.sortable-item-input').prop('checked', false);
                        },
                        stop: function(event, ui) {
                            if (!$(ui.item).hasClass("allowPrimary")) {
                                $mainWrapper.find(".primaryPanel").removeClass("panel-danger").addClass('panel-primary');
                            }
                        },
                        change: function(event, ui) {
                            checkFields( $mainWrapper );
                        },
                        update: function(event, ui) {
                            checkFields( $mainWrapper );
                        },
                        out: function(event, ui) {
                            checkFields( $mainWrapper );
                        }
                    }).disableSelection();

                    // Enable dropzone for primary fields
                    $selectedDropzone.sortable({
                        connectWith: '.sortable-list',
                        placeholder: 'placeholder',
                        receive: function(event, ui) {
                            // If we dont allow primary fields here, cancel
                            if (!$(ui.item).hasClass("allowPrimary")) {
                                $(ui.placeholder).css('display', 'none');
                                $(ui.sender).sortable("cancel");
                            } else {
                                $(ui.item).find('.sortable-item-input').prop('checked', true);
                            }
                        },
                        over: function(event, ui) {
                            if (!$(ui.item).hasClass("allowPrimary")) {
                                $(ui.placeholder).css('display', 'none');
                            } else {
                                $(ui.placeholder).css('display', '');
                            }
                        },
                        start: function(event, ui) {
                            checkFields( $mainWrapper )
                        },
                        change: function(event, ui) {
                            checkFields( $mainWrapper );
                        },
                        update: function(event, ui) {
                            checkFields( $mainWrapper );
                        },
                        out: function(event, ui) {
                            checkFields( $mainWrapper );
                        }
                    }).disableSelection();
                });
            });

            // Checks to see if the fields section has fields selected. If not, shows a placeholder
            function checkFields( $this ) {
                if ( $this.find(selectedDropzone).find('li').length >= 1) {
                    $this.find('.primaryPanel').find('.alert').hide();
                } else {
                    $this.find('.primaryPanel').find('.alert').show();
                }
            }

        });